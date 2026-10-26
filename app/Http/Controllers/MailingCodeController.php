<?php

namespace App\Http\Controllers;

use App\Services\MailingCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MailingCodeController extends Controller
{
    public function show(Request $request, MailingCodeService $service): View
    {
        $user = $request->user();
        $mailingCode = $service->getOrCreateForUser($user);

        return view('mailing-code.show', compact('user', 'mailingCode'));
    }

    public function rotate(Request $request, MailingCodeService $service): RedirectResponse
    {
        $user = $request->user();
        $newCode = $service->rotateForUser($user);

        return back()->with('status', "Your mailing code has been retired and replaced with new address: {$newCode->code}");
    }

    public function updatePolicy(Request $request, MailingCodeService $service): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'permissions_policy' => ['required', 'string', 'in:anyone,accepted_connections'],
        ]);

        $mailingCode = $service->getOrCreateForUser($user);
        $mailingCode->update(['permissions_policy' => $validated['permissions_policy']]);

        return back()->with('status', 'Postal addressing policy updated successfully.');
    }
}
