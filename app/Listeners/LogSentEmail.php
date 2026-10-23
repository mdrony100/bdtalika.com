<?php

namespace App\Listeners;

use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class LogSentEmail
{
    /**
     * Handle the event.
     *
     * Persists a record of every email sent through Laravel's mailer
     * (welcome, password reset, directory status change, marketing, etc.)
     * so it can be reviewed per user in the admin panel.
     */
    public function handle(MessageSent $event): void
    {
        try {
            $message = $event->message;

            $subject = $message->getSubject();
            $body = $message->getHtmlBody() ?: $message->getTextBody();

            foreach ($message->getTo() as $address) {
                $email = $address->getAddress();

                if (!$email) {
                    continue;
                }

                $user = User::where('email', $email)->first();

                EmailLog::create([
                    'user_id' => $user?->id,
                    'email' => $email,
                    'subject' => $subject,
                    'body' => $body,
                    'mailer' => $event->data['mailer'] ?? null,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Email log write failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
