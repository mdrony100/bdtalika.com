@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('Expenses') }}</h5>
            <ol class="breadcrumb py-0 m-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Expenses') }}</li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">{{ __('Filtered Total') }}: <strong>{{ number_format($total, 2) }}</strong></h6>
                            <span class="text-muted">{{ __('Track domain, hosting, software, and one-time purchases.') }}</span>
                        </div>
                        <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('Add Expense') }}
                        </a>
                    </div>

                    @if ($upcomingRenewals->count() > 0)
                        <div class="alert alert-warning">
                            <strong>{{ __('Upcoming Renewals') }}:</strong>
                            @foreach ($upcomingRenewals as $renewal)
                                <span class="mr-2">{{ $renewal->title }} ({{ $renewal->renewal_date->format('d M Y') }})</span>
                            @endforeach
                        </div>
                    @endif

                    <form method="GET" action="{{ route('admin.expenses.index') }}" class="form-inline mb-3">
                        <select name="type" class="form-control mr-2 mb-2">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach ($types as $key => $label)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ __($label) }}</option>
                            @endforeach
                        </select>
                        <select name="billing_cycle" class="form-control mr-2 mb-2">
                            <option value="">{{ __('All Billing') }}</option>
                            @foreach ($billingCycles as $key => $label)
                                <option value="{{ $key }}" {{ request('billing_cycle') == $key ? 'selected' : '' }}>{{ __($label) }}</option>
                            @endforeach
                        </select>
                        <select name="year" class="form-control mr-2 mb-2">
                            <option value="">{{ __('All Years') }}</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary mb-2">{{ __('Filter') }}</button>
                        <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary ml-2 mb-2">{{ __('Reset') }}</a>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Billing') }}</th>
                                    <th>{{ __('Renewal') }}</th>
                                    <th>{{ __('Vendor') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expenses as $expense)
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $expense->expense_date->format('d M Y') }}</td>
                                        <td>
                                            <strong>{{ $expense->title }}</strong>
                                            @if ($expense->note)
                                                <div class="text-muted small">{{ \Illuminate\Support\Str::limit($expense->note, 80) }}</div>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-secondary">{{ $types[$expense->type] ?? ucfirst($expense->type) }}</span></td>
                                        <td>{{ $expense->currency }} {{ number_format((float) $expense->amount, 2) }}</td>
                                        <td>{{ $billingCycles[$expense->billing_cycle] ?? ucfirst($expense->billing_cycle) }}</td>
                                        <td>{{ $expense->renewal_date ? $expense->renewal_date->format('d M Y') : '-' }}</td>
                                        <td>
                                            {{ $expense->vendor ?? '-' }}
                                            @if ($expense->payment_method)
                                                <div class="text-muted small">{{ $expense->payment_method }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center" style="white-space: nowrap;">
                                            <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.expenses.delete', $expense->id) }}" class="btn btn-sm btn-danger"
                                                onclick="return confirm('{{ __('Delete this expense?') }}')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">{{ __('No expenses found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-3 pb-3">
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
