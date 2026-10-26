<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\PostageStamp;
use App\Models\StationeryTheme;
use App\Models\User;
use App\Models\WaxSeal;
use App\Services\LetterDeliveryService;
use App\Services\MailingCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LetterComposerController extends Controller
{
    /**
     * Show letter composer studio
     */
    public function create(Request $request): View
    {
        $user = $request->user();
        $code = $request->query('code');
        $replyToId = $request->query('reply_to');

        $recipient = null;
        if ($code) {
            $codeRecord = \App\Models\MailingCode::where('code', strtoupper(trim($code)))->first();
            if ($codeRecord) {
                $recipient = $codeRecord->user;
            }
        }

        $replyTo = null;
        if ($replyToId) {
            $replyTo = Letter::where('id', $replyToId)
                ->where('recipient_id', $user->id)
                ->first();
            if ($replyTo) {
                $recipient = $replyTo->sender;
            }
        }

        $themes = StationeryTheme::where('is_active', true)->orderBy('order_index')->get();
        $stamps = PostageStamp::where('is_active', true)->orderBy('order_index')->get();
        $seals = WaxSeal::where('is_active', true)->get();

        $letter = new Letter();
        if ($recipient) {
            $letter->recipient_id = $recipient->id;
        }
        if ($replyTo) {
            $letter->reply_to_letter_id = $replyTo->id;
            $letter->title = 'Re: ' . preg_replace('/^Re:\s*/i', '', $replyTo->title);
            $letter->greeting = "Dear {$recipient->nickname},";
        }

        return view('letters.compose', compact('letter', 'themes', 'stamps', 'seals', 'recipient', 'replyTo'));
    }

    /**
     * Edit an existing draft
     */
    public function editDraft(int $id): View|RedirectResponse
    {
        $user = auth()->user();
        $letter = Letter::where('id', $id)
            ->where('sender_id', $user->id)
            ->where('status', 'draft')
            ->firstOrFail();

        $themes = StationeryTheme::where('is_active', true)->orderBy('order_index')->get();
        $stamps = PostageStamp::where('is_active', true)->orderBy('order_index')->get();
        $seals = WaxSeal::where('is_active', true)->get();
        $recipient = $letter->recipient;
        $replyTo = $letter->replyTo;

        return view('letters.compose', compact('letter', 'themes', 'stamps', 'seals', 'recipient', 'replyTo'));
    }

    /**
     * Save draft (AJAX or form POST)
     */
    public function saveDraft(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'id' => ['nullable', 'exists:letters,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:50000'],
            'greeting' => ['nullable', 'string', 'max:100'],
            'signoff' => ['nullable', 'string', 'max:100'],
            'stationery_theme_id' => ['nullable', 'exists:stationery_themes,id'],
            'recipient_code' => ['nullable', 'string', 'max:32'],
        ]);

        $recipientId = null;
        if (!empty($validated['recipient_code'])) {
            $codeRecord = \App\Models\MailingCode::where('code', strtoupper(trim($validated['recipient_code'])))->first();
            if ($codeRecord) {
                $recipientId = $codeRecord->user_id;
            }
        }

        $letter = Letter::updateOrCreate(
            [
                'id' => $validated['id'] ?? null,
                'sender_id' => $user->id,
            ],
            [
                'title' => $validated['title'],
                'body' => $validated['body'],
                'greeting' => $validated['greeting'],
                'signoff' => $validated['signoff'],
                'stationery_theme_id' => $validated['stationery_theme_id'],
                'recipient_id' => $recipientId,
                'status' => 'draft',
            ]
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'letter_id' => $letter->id,
                'saved_at' => $letter->updated_at->format('H:i:s'),
            ]);
        }

        return redirect()->route('letters.fold', $letter->id)
            ->with('status', 'Draft saved. Now prepare your envelope and seal!');
    }

    /**
     * Fold & Seal Studio: Packaging letter inside envelope with stamp and seal
     */
    public function foldStudio(int $letterId): View|RedirectResponse
    {
        $user = auth()->user();
        $letter = Letter::where('id', $letterId)
            ->where('sender_id', $user->id)
            ->where('status', 'draft')
            ->firstOrFail();

        $themes = StationeryTheme::where('is_active', true)->get();
        $stamps = PostageStamp::where('is_active', true)->get();
        $seals = WaxSeal::where('is_active', true)->get();

        return view('letters.fold', compact('letter', 'themes', 'stamps', 'seals'));
    }

    /**
     * Send Sealed Letter
     */
    public function send(Request $request, int $letterId, MailingCodeService $codeService, LetterDeliveryService $deliveryService): RedirectResponse
    {
        $sender = $request->user();

        $letter = Letter::where('id', $letterId)
            ->where('sender_id', $sender->id)
            ->where('status', 'draft')
            ->firstOrFail();

        $validated = $request->validate([
            'recipient_code' => ['required', 'string', 'max:32'],
            'stationery_theme_id' => ['nullable', 'exists:stationery_themes,id'],
            'postage_stamp_id' => ['nullable', 'exists:postage_stamps,id'],
            'wax_seal_id' => ['nullable', 'exists:wax_seals,id'],
        ]);

        // Resolve recipient using secure mailing code service
        $recipient = $codeService->resolveRecipient($validated['recipient_code'], $sender);

        // Update aesthetics on letter
        $letter->update([
            'stationery_theme_id' => $validated['stationery_theme_id'] ?? $letter->stationery_theme_id,
            'postage_stamp_id' => $validated['postage_stamp_id'] ?? $letter->postage_stamp_id,
            'wax_seal_id' => $validated['wax_seal_id'] ?? $letter->wax_seal_id,
        ]);

        // Dispatch via transactional delivery service (enforces daily limits & records lifecycle events)
        $deliveryService->dispatchLetter($letter, $sender, $recipient);

        return redirect()->route('mailbox.sent')
            ->with('status', "Your letter has been sealed and dispatched to {$recipient->nickname} ({$validated['recipient_code']})!");
    }

    /**
     * Duplicate a draft
     */
    public function duplicateDraft(int $id): RedirectResponse
    {
        $original = Letter::where('id', $id)
            ->where('sender_id', auth()->id())
            ->where('status', 'draft')
            ->firstOrFail();

        $copy = $original->replicate(['created_at', 'updated_at']);
        $copy->title = $original->title . ' (Copy)';
        $copy->save();

        return redirect()->route('drafts.edit', $copy->id)->with('status', 'Draft duplicated.');
    }

    /**
     * Delete a draft
     */
    public function destroyDraft(int $id): RedirectResponse
    {
        $letter = Letter::where('id', $id)
            ->where('sender_id', auth()->id())
            ->where('status', 'draft')
            ->firstOrFail();

        $letter->delete();

        return redirect()->route('mailbox.drafts')->with('status', 'Draft discarded.');
    }
}
