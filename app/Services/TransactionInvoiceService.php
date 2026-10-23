<?php

namespace App\Services;

use App\Models\BkashPayment;
use App\Models\EmailLog;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class TransactionInvoiceService
{
    public function download(Transaction $transaction)
    {
        return $this->pdf($transaction)->download($this->filename($transaction));
    }

    public function sendPaymentSuccess(Transaction $transaction): void
    {
        try {
            $transaction->loadMissing(['user', 'listing']);

            if (empty($transaction->email)) {
                Log::warning('Invoice email skipped: transaction has no email address', [
                    'transaction_id' => $transaction->id,
                ]);

                return;
            }

            $pdf = $this->pdf($transaction);
            $subject = 'Payment Successful - Invoice ' . $this->invoiceNumber($transaction);

            Mail::send('emails.payment-success-invoice', [
                'transaction' => $transaction,
                'invoiceNumber' => $this->invoiceNumber($transaction),
            ], function ($message) use ($transaction, $pdf, $subject) {
                $message->to($transaction->email)
                    ->subject($subject)
                    ->attachData($pdf->output(), $this->filename($transaction), [
                        'mime' => 'application/pdf',
                    ]);
            });
        } catch (Throwable $e) {
            Log::error('Invoice email failed', [
                'transaction_id' => $transaction->id,
                'email' => $transaction->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function invoiceNumber(Transaction $transaction): string
    {
        $invoice = BkashPayment::where('transaction_id', $transaction->id)
            ->value('merchant_invoice_number');

        return $invoice ?: 'INV-' . str_pad((string) $transaction->id, 6, '0', STR_PAD_LEFT);
    }

    public function sendBannerInvoice(Transaction $transaction): void
    {
        try {
            $transaction->loadMissing(['user', 'listing']);

            if (empty($transaction->email)) {
                Log::warning('Banner invoice email skipped: transaction has no email address', [
                    'transaction_id' => $transaction->id,
                ]);

                return;
            }

            $pdf = $this->pdf($transaction);
            $subject = 'Invoice ' . $this->invoiceNumber($transaction) . ' - Banner Advertisement - bdtalika.com';

            Mail::send('emails.banner-invoice', [
                'transaction' => $transaction,
                'invoiceNumber' => $this->invoiceNumber($transaction),
            ], function ($message) use ($transaction, $pdf, $subject) {
                $message->to($transaction->email)
                    ->subject($subject)
                    ->attachData($pdf->output(), $this->filename($transaction), [
                        'mime' => 'application/pdf',
                    ]);
            });

            EmailLog::create([
                'user_id' => $transaction->user_id,
                'email' => $transaction->email,
                'subject' => $subject,
                'body' => view('emails.banner-invoice', [
                    'transaction' => $transaction,
                    'invoiceNumber' => $this->invoiceNumber($transaction),
                ])->render(),
                'mailer' => 'banner-ad-invoice',
            ]);
        } catch (Throwable $e) {
            Log::error('Banner invoice email failed', [
                'transaction_id' => $transaction->id,
                'email' => $transaction->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function pdf(Transaction $transaction)
    {
        $transaction->loadMissing(['user', 'listing']);

        $view = $transaction->type === 'banner_ad' ? 'invoices.banner' : 'invoices.transaction';

        return Pdf::loadView($view, [
            'transaction' => $transaction,
            'invoiceNumber' => $this->invoiceNumber($transaction),
        ])->setPaper('a4');
    }

    private function filename(Transaction $transaction): string
    {
        return 'invoice-' . Str::slug($this->invoiceNumber($transaction)) . '.pdf';
    }
}
