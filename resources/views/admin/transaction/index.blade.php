@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between py-3">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Transactions') }}</h5>
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Transactions') }}</a></li>
            </ol>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">{{ __('Total Earn') }}</small>
                    <h4 class="mb-0 mt-2">{{ number_format($totalEarn, 2) }} TK</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">{{ __('Month Wise Earn') }} ({{ $monthLabel }})</small>
                    <h4 class="mb-0 mt-2">{{ number_format($monthEarn, 2) }} TK</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">{{ __('Yearly Earnings') }}</small>
                    <h4 class="mb-0 mt-2">{{ number_format($yearEarn, 2) }} TK</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">{{ __('Plan Name Wise Earn') }}</small>
                    <h4 class="mb-0 mt-2">{{ $planWiseEarn->count() }} {{ __('Plans') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">{{ __('Plan Name Wise Earn') }}</h6>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('Plan Name') }}</th>
                                <th>{{ __('Earn') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($planWiseEarn as $plan)
                                <tr>
                                    <td>{{ $plan->plan_name ?: __('N/A') }}</td>
                                    <td>{{ number_format($plan->total_earn, 2) }} TK</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">{{ __('No data found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->
    <div class="row mt-3">
        <div class="col-lg-12">
            @include('includes.admin.form-success')
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <form action="{{ route('admin.transactions.index') }}" method="GET" class="row align-items-end">
                        <div class="col-md-4 col-lg-3 mb-2 mb-md-0">
                            <label for="transaction_month" class="small text-muted mb-1">{{ __('Month') }}</label>
                            <input type="month" name="month" id="transaction_month" value="{{ $selectedMonth }}"
                                class="form-control">
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                            @if ($selectedMonth)
                                <a href="{{ route('admin.transactions.index') }}" class="btn btn-light ml-2">
                                    {{ __('Reset') }}
                                </a>
                            @endif
                            <a href="{{ route('admin.transactions.banner.invoice') }}" class="btn btn-success ml-2">
                                <i class="fas fa-plus"></i> {{ __('Banner Ad Invoice') }}
                            </a>
                        </div>
                    </form>
                </div>
                <div class="table-responsive p-3">
                    <table id="transactionTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th>@lang('Listing Name')</th>
                                <th>@lang('User Name')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Phone Number')</th>
                                <th>@lang('Transaction ID')</th>
                                <th>@lang('Plan Name')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Start Date')</th>
                                <th>@lang('Created')</th>
                                <th>@lang('Plan Duration')</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td>
                                        @if ($transaction->listing?->status == '1')
                                            <a href="{{ route('front.listing.details', $transaction->listing?->slug) }}"
                                                class="color_link" target="_blank">
                                                {{ $transaction->listing?->name }}
                                            </a>
                                        @else
                                            <a href="#"
                                                onclick="return confirm('This listing is not active yet')"
                                                class="color_link" target="_blank">
                                                {{ $transaction->listing?->name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transaction->user && $transaction->user->id)
                                            <a href="{{ route('admin-user-show', $transaction->user->id) }}"
                                                class="color_link">
                                                {{ $transaction->user->username ?: $transaction->user->name }}
                                            </a>
                                        @else
                                            {{ __('N/A') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($transaction->listing?->status == '0')
                                            <span class="badge px-3 py-2 badge-warning">Pending</span>
                                        @elseif($transaction->listing?->status == '1')
                                            <span class="badge px-3 py-2 badge-success">Approved</span>
                                        @elseif($transaction->listing?->status == '2')
                                            <span class="badge px-3 py-2 badge-danger">Reject</span>
                                        @else
                                            <span class="badge px-3 py-2 badge-danger">Requires review</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->phone }}</td>
                                    <td>{{ $transaction->txnid }}</td>
                                    <td>{{ $transaction->plan_name }}</td>
                                    <td>{{ $transaction->amount }} TK</td>
                                    <td>{{ date('d/m/Y', strtotime($transaction->listing?->plan_start_date)) }} </td>
                                    <td>{{ $transaction->created_at->format('l, d M Y - h:i A') }}</td>
                                    <td>
                                        {{ $transaction->plan_duration }} 
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.transactions.invoice', $transaction->id) }}"
                                            class="dropdown-item">Download Invoice</a>
                                        <a href="{{ route('admin-transaction-delete', $transaction->id) }}"
                                            onclick="return confirm('Are you sure you want to delete this transaction?')"
                                            class="dropdown-item"> Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11">Data not found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex align-items-center justify-content-end mt-3">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Keep the server-side ordering (latest created_at first) instead of
            // letting DataTables re-sort by the first column. Client paging is
            // disabled because pagination is handled server-side by Laravel.
            $('#transactionTable').DataTable({
                order: [],
                paging: false,
                info: false
            });
        });
    </script>
@endsection
