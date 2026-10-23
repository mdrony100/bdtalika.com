<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Listing;
use App\Models\Transaction;
use App\Services\TransactionInvoiceService;
use Illuminate\Http\Request;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{


    public function index(Request $request){
        $baseQuery = Transaction::where('status', 1);
        $selectedMonth = $request->query('month');
        $now = Carbon::now();

        try {
            $monthDate = $selectedMonth && preg_match('/^\d{4}-\d{2}$/', $selectedMonth)
                ? Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth()
                : $now->copy()->startOfMonth();

            if ($selectedMonth && $monthDate->format('Y-m') !== $selectedMonth) {
                $selectedMonth = null;
                $monthDate = $now->copy()->startOfMonth();
            }
        } catch (\Throwable $exception) {
            $selectedMonth = null;
            $monthDate = $now->copy()->startOfMonth();
        }

        $monthStart = $monthDate->copy()->startOfMonth();
        $monthEnd = $monthDate->copy()->endOfMonth();
        $monthLabel = $monthDate->format('F Y');

        $filteredQuery = (clone $baseQuery);

        if ($selectedMonth) {
            $filteredQuery->whereBetween('created_at', [$monthStart, $monthEnd]);
        }

        $transactions = (clone $filteredQuery)
            ->latest('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->appends($request->only('month'));

        $totalEarn = (clone $baseQuery)->sum('amount');

        $monthEarn = (clone $baseQuery)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('amount');

        $yearEarn = (clone $baseQuery)
            ->whereBetween('created_at', [$now->copy()->startOfYear(), $now->copy()->endOfYear()])
            ->sum('amount');

        $planWiseEarn = (clone $filteredQuery)
            ->select('plan_name', DB::raw('SUM(amount) as total_earn'))
            ->groupBy('plan_name')
            ->orderByDesc('total_earn')
            ->get();

        return view('admin.transaction.index', compact(
            'transactions',
            'totalEarn',
            'monthEarn',
            'yearEarn',
            'planWiseEarn',
            'selectedMonth',
            'monthLabel'
        ));
    }

    public function delete($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ((int) $transaction->status === 0) {
            return redirect()->back()->with('message', 'Transaction is already deleted.');
        }

        $transaction->status = 0;
        $transaction->save();

        return redirect()->back()->with('message', 'Transaction moved to deleted status successfully.');
    }

    public function bannerInvoiceCreate()
    {
        $listings = Listing::with('user')
            ->where('status', 1)
            ->whereNotNull('name')
            ->orderBy('name')
            ->get();

        return view('admin.transaction.banner_invoice', compact('listings'));
    }

    public function bannerInvoiceStore(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'service_name' => 'required|string|max:191',
            'duration' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:30',
            'email_invoice' => 'nullable',
        ]);

        $listing = Listing::findOrFail($request->listing_id);

        $transaction = new Transaction();
        $transaction->user_id = $listing->user_id;
        $transaction->ad_id = $listing->id;
        $transaction->email = $request->email;
        $transaction->phone = $request->phone;
        $transaction->amount = $request->amount;
        $transaction->type = 'banner_ad';
        $transaction->plan_duration = $request->duration;
        $transaction->plan_name = $request->service_name;
        $transaction->txnid = 'BANNER-' . strtoupper(Str::random(8));
        $transaction->status = 1;
        $transaction->save();

        if ($request->has('email_invoice')) {
            app(TransactionInvoiceService::class)->sendBannerInvoice($transaction);
        }

        return redirect()->route('admin.transactions.invoice', $transaction->id);
    }
}
