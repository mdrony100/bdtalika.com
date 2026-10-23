<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionInvoiceService;
use Illuminate\Support\Facades\Auth;

class TransactionInvoiceController extends Controller
{
    public function userDownload($id, TransactionInvoiceService $invoiceService)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 1)
            ->firstOrFail();

        return $invoiceService->download($transaction);
    }

    public function adminDownload($id, TransactionInvoiceService $invoiceService)
    {
        $transaction = Transaction::where('id', $id)
            ->where('status', 1)
            ->firstOrFail();

        return $invoiceService->download($transaction);
    }
}
