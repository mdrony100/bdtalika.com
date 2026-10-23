<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    private array $types = [
        'domain' => 'Domain',
        'hosting' => 'Hosting',
        'software' => 'Software',
        'marketing' => 'Marketing',
        'one_time_purchase' => 'One Time Purchase',
        'other' => 'Other',
    ];

    private array $billingCycles = [
        'one_time' => 'One Time',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ];

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = Expense::query()
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->where('type', $request->type);
            })
            ->when($request->filled('billing_cycle'), function ($q) use ($request) {
                $q->where('billing_cycle', $request->billing_cycle);
            })
            ->when($request->filled('year'), function ($q) use ($request) {
                $q->whereYear('expense_date', $request->year);
            });

        $total = (clone $query)->sum('amount');
        $upcomingRenewals = Expense::whereNotNull('renewal_date')
            ->whereBetween('renewal_date', [now()->toDateString(), now()->addDays(45)->toDateString()])
            ->orderBy('renewal_date')
            ->get();

        $expenses = $query->latest('expense_date')
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $years = Expense::selectRaw('YEAR(expense_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.expense.index', [
            'expenses' => $expenses,
            'types' => $this->types,
            'billingCycles' => $this->billingCycles,
            'years' => $years,
            'total' => $total,
            'upcomingRenewals' => $upcomingRenewals,
        ]);
    }

    public function create()
    {
        return view('admin.expense.create', [
            'types' => $this->types,
            'billingCycles' => $this->billingCycles,
        ]);
    }

    public function store(Request $request)
    {
        Expense::create($this->validated($request));
        Toastr::success('Expense added successfully.');

        return redirect()->route('admin.expenses.index');
    }

    public function edit($id)
    {
        return view('admin.expense.edit', [
            'expense' => Expense::findOrFail($id),
            'types' => $this->types,
            'billingCycles' => $this->billingCycles,
        ]);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update($this->validated($request));
        Toastr::success('Expense updated successfully.');

        return redirect()->route('admin.expenses.index');
    }

    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        Toastr::success('Expense deleted successfully.');

        return redirect()->back();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'type' => ['required', Rule::in(array_keys($this->types))],
            'amount' => 'required|numeric|min:0|max:9999999999.99',
            'currency' => 'required|string|max:10',
            'billing_cycle' => ['required', Rule::in(array_keys($this->billingCycles))],
            'expense_date' => 'required|date',
            'renewal_date' => 'nullable|date',
            'vendor' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);
    }
}
