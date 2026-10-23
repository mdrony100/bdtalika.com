<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BkashPayment;
use App\Models\Listing;
use App\Models\Transaction;
use App\Models\WhatsAppMessage;
use App\Services\BangladeshPhoneService;
use App\Services\MetaWhatsAppService;
use App\Services\WhatsAppAbandonedPaymentReminderService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WhatsAppMessagingController extends Controller
{
    public function __construct(
        private WhatsAppAbandonedPaymentReminderService $abandonedPayments,
        private MetaWhatsAppService $whatsApp
    ) {
    }

    public function index(Request $request)
    {
        $tab = $request->input('tab', 'pending');

        $pendingListings = $this->payableAbandonedListings()
            ->orderBy('created_at')
            ->paginate(20, ['*'], 'pending_page')
            ->appends($request->query());

        $history = WhatsAppMessage::with(['user', 'listing', 'admin'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = trim($request->q);
                $query->where(function ($q) use ($search) {
                    $q->where('recipient_phone', 'like', '%' . $search . '%')
                        ->orWhere('template_name', 'like', '%' . $search . '%')
                        ->orWhere('purpose', 'like', '%' . $search . '%')
                        ->orWhere('meta_message_id', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('listing', function ($listingQuery) use ($search) {
                            $listingQuery->where('name', 'like', '%' . $search . '%');

                            if (is_numeric($search)) {
                                $listingQuery->orWhere('id', $search);
                            }
                        });
                });
            })
            ->when($request->filled('source'), fn ($query) => $query->where('source', $request->source))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('purpose'), fn ($query) => $query->where('purpose', $request->purpose))
            ->latest()
            ->paginate(30, ['*'], 'history_page')
            ->appends($request->query());

        $cronLog = \App\Models\CronLog::where('job', WhatsAppAbandonedPaymentReminderService::JOB)
            ->latest('run_date')
            ->first();

        return view('admin.whatsapp.index', compact('pendingListings', 'history', 'tab', 'cronLog'));
    }

    public function send(Request $request, Listing $listing)
    {
        $listing->loadMissing(['user', 'plan', 'latestCompletedBkashPayment', 'latestTransaction', 'whatsAppMessages']);

        if (!$this->abandonedPayments->isStillEligible($listing)) {
            Toastr::error('This listing is no longer eligible for an abandoned-payment WhatsApp reminder.');

            return redirect()->back();
        }

        $phone = BangladeshPhoneService::normalize(optional($listing->user)->phone)
            ?: BangladeshPhoneService::normalize($listing->whatsapp_number);

        if (!$phone || !optional($listing->user)->whatsapp_opt_in) {
            Toastr::error('Valid WhatsApp number and customer consent are required.');

            return redirect()->back();
        }

        $message = WhatsAppMessage::create([
            'user_id' => $listing->user_id,
            'admin_id' => Auth::guard('admin')->id(),
            'listing_id' => $listing->id,
            'idempotency_key' => 'manual:' . $listing->id . ':' . (string) Str::uuid(),
            'recipient_phone' => $phone,
            'template_name' => config('services.meta_whatsapp.template_name', 'payment_pending_reminder'),
            'purpose' => WhatsAppAbandonedPaymentReminderService::PURPOSE,
            'source' => 'manual',
            'status' => 'pending',
        ]);

        $result = $this->whatsApp->sendPaymentPendingReminder($listing, $phone);

        if ($result['success'] ?? false) {
            $message->update([
                'status' => 'sent',
                'meta_message_id' => $result['meta_message_id'] ?? null,
                'sent_at' => now(),
            ]);

            Toastr::success('WhatsApp reminder sent successfully.');

            return redirect()->back();
        }

        $message->update([
            'status' => 'failed',
            'error_message' => $result['error_message'] ?? 'Meta WhatsApp request failed.',
        ]);

        Toastr::error('WhatsApp reminder failed. Please review message history.');

        return redirect()->back();
    }

    private function payableAbandonedListings()
    {
        return Listing::query()
            ->with(['user', 'plan', 'latestCompletedBkashPayment', 'latestTransaction', 'whatsAppMessages'])
            ->where('created_at', '>=', $this->abandonedPayments->startAt())
            ->where('created_at', '<=', now()->subMinutes($this->abandonedPayments->minimumAgeMinutes()))
            ->where('status', 0)
            ->whereDoesntHave('plan', function ($query) {
                $query->where('plan_type', 'free');
            })
            ->whereDoesntHave('latestCompletedBkashPayment')
            ->whereDoesntHave('latestTransaction')
            ->whereHas('user', function ($query) {
                $query->where('whatsapp_opt_in', 1)
                    ->whereNotNull('phone')
                    ->where('phone', '<>', '');
            })
            ->whereNotExists(function ($query) {
                $query->selectRaw(1)
                    ->from((new BkashPayment())->getTable())
                    ->whereColumn('bkash_payments.listing_id', 'listings.id')
                    ->where('payment_status', 'completed');
            })
            ->whereNotExists(function ($query) {
                $query->selectRaw(1)
                    ->from((new Transaction())->getTable())
                    ->whereColumn('transactions.ad_id', 'listings.id')
                    ->where('status', 1);
            });
    }
}
