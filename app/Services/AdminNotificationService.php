<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AdminNotificationService
{
    private string $adminEmail = 'bdtalika@gmail.com';

    public function newUserRegistered(User $user): void
    {
        try {
            Mail::send('emails.admin-new-user', compact('user'), function ($message) use ($user) {
                $message->to($this->adminEmail)
                    ->subject('New customer registered - ' . $user->name);
            });
        } catch (Throwable $e) {
            Log::error('Admin new user notification email failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function newListingCreated(Listing $listing): void
    {
        try {
            $listing->loadMissing(['user', 'category']);

            Mail::send('emails.admin-new-listing', compact('listing'), function ($message) use ($listing) {
                $message->to($this->adminEmail)
                    ->subject('New listing created - ' . $listing->name);
            });
        } catch (Throwable $e) {
            Log::error('Admin new listing notification email failed', [
                'listing_id' => $listing->id,
                'user_id' => $listing->user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function bkashListingPaymentCompleted(Transaction $transaction): void
    {
        try {
            $transaction->loadMissing(['user', 'listing']);
            $listing = $transaction->listing;

            if ($listing) {
                $listing->loadMissing(['category']);
            }

            Mail::send('emails.admin-bkash-listing-payment', compact('transaction', 'listing'), function ($message) use ($transaction, $listing) {
                $listingName = $listing?->name ?: 'Listing #' . $transaction->ad_id;

                $message->to($this->adminEmail)
                    ->subject('bKash payment completed - ' . $listingName);
            });
        } catch (Throwable $e) {
            Log::error('Admin bKash payment notification email failed', [
                'transaction_id' => $transaction->id,
                'listing_id' => $transaction->ad_id,
                'user_id' => $transaction->user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
