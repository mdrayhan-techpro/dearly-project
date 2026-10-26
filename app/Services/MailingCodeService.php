<?php

namespace App\Services;

use App\Models\CorrespondenceConnection;
use App\Models\MailingCode;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class MailingCodeService
{
    /**
     * Get or generate a mailing code for a user
     */
    public function getOrCreateForUser(User $user): MailingCode
    {
        return MailingCode::firstOrCreate(
            ['user_id' => $user->id],
            [
                'code' => MailingCode::generateUniqueCode(),
                'is_active' => true,
                'permissions_policy' => 'anyone',
            ]
        );
    }

    /**
     * Rotate a user's mailing code (reissues a fresh code)
     */
    public function rotateForUser(User $user): MailingCode
    {
        $codeRecord = $this->getOrCreateForUser($user);
        $codeRecord->update([
            'code' => MailingCode::generateUniqueCode(),
            'is_active' => true,
        ]);

        return $codeRecord;
    }

    /**
     * Resolve a mailing code to an eligible recipient for the given sender.
     * Throws ValidationException with helpful error message if resolution fails.
     */
    public function resolveRecipient(string $rawCode, User $sender): User
    {
        $cleanCode = strtoupper(trim($rawCode));

        $codeRecord = MailingCode::with('user.profile')->where('code', $cleanCode)->first();

        if (!$codeRecord || !$codeRecord->is_active || !$codeRecord->user) {
            throw ValidationException::withMessages([
                'mailing_code' => ['This mailing code could not be found or has been retired by its owner.'],
            ]);
        }

        $recipient = $codeRecord->user;

        // Cannot send letters to oneself
        if ($recipient->id === $sender->id) {
            throw ValidationException::withMessages([
                'mailing_code' => ['You cannot address a letter to your own mailing code.'],
            ]);
        }

        // Recipient is suspended
        if ($recipient->is_suspended) {
            throw ValidationException::withMessages([
                'mailing_code' => ['This postal address is temporarily not accepting correspondence.'],
            ]);
        }

        // Block checks
        if ($sender->hasBlocked($recipient->id) || $recipient->hasBlocked($sender->id)) {
            throw ValidationException::withMessages([
                'mailing_code' => ['Correspondence between these accounts is currently restricted.'],
            ]);
        }

        // Permissions policy
        if ($codeRecord->permissions_policy === 'accepted_connections') {
            if (!CorrespondenceConnection::areConnected($sender->id, $recipient->id)) {
                throw ValidationException::withMessages([
                    'mailing_code' => ['This recipient only accepts letters from accepted pen-pal connections.'],
                ]);
            }
        }

        return $recipient;
    }
}
