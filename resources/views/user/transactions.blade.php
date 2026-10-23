@extends('layouts.front')

@push('css')
    <style>
        .table thead th {
            border-bottom: 2px solid #f3f3f3 !important;
            background: #f3f3f3 !important;
        }
    </style>
@endpush

@section('content')
    <!-- =============================== Dashboard Header ========================== -->
    {{-- @includeIf('partials.user.header') --}}
    <!-- =============================== Dashboard Header ========================== -->

    <!-- ======================= dashboard Detail ======================== -->
    <div class="bg_light px-4 py-2">
        <div class="dashboard-tlbar d-block">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}"
                                    class="color_link">@lang('Home')</a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('user.listing.index') }}"
                                    class="">@lang('Manage Transactions')</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-wrap bg-light px-2 px-lg-4 pt-3 pb-5">
        <a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false"
            aria-controls="MobNav">
            <i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
        </a>
        <div class="collapse" id="MobNav">
            @includeIf('partials.user.sidebar')
        </div>

        <div class="dashboard-content">
            <div class="">
                <div class="dashboard-widg-bar d-block">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
                                <div class="dashboard-list-wraps-head br-bottom py-3 px-3">
                                    <div class="dashboard-list-wraps-flx">
                                        <h4 class="mb-0 ft-medium fs-md"><i
                                                class="lni lni-dollar me-2 theme-cl fs-sm"></i>@lang('Transactions')</h4>
                                    </div>
                                </div>
                                <div class="dashboard-list-wraps-body">
                                    <div class="dashboard-widg-bar d-block">
                                        <div class="table-responsive table--mobile-lg">
                                            <table class="table bg--body">
                                                @if (isset($transactions) && $transactions->count() > 0)
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('Listing Name')</th>
                                                            <th>@lang('Status')</th>
                                                            <th>@lang('Phone Number')</th>
                                                            <th>@lang('Transaction ID')</th>
                                                            <th>@lang('Plan Name')</th>
                                                            <th>@lang('Amount')</th>
                                                            <th>@lang('Start Date')</th>
                                                            <th>@lang('Plan Duration')</th>
                                                            <th>@lang('Invoice')</th>
                                                        </tr>
                                                    </thead>
                                                @endif
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
                                                                        onclick="return confirm('Your listing is not active yet. It will go live after review')"
                                                                        class="color_link" target="_blank">
                                                                        {{ $transaction->listing?->name }}
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($transaction->listing?->status == '0')
                                                                    <span class="badge bg-warning">Pending</span>
                                                                @elseif($transaction->listing?->status == '1')
                                                                    <span class="badge bg-success">Approved</span>
                                                                @elseif($transaction->listing?->status == '2')
                                                                    <span class="badge bg-danger">Reject</span>
                                                                @else
                                                                    <span class="badge bg-danger">Requires review</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $transaction->phone }}</td>
                                                            <td>{{ $transaction->txnid }}</td>
                                                            <td>{{ $transaction->plan_name }}</td>
                                                            <td>{{ $transaction->amount }} TK</td>
                                                            <td>{{ date('d/m/Y', strtotime($transaction->listing?->plan_start_date)) }}
                                                            </td>
                                                            <td>
                                                                {{ $transaction->plan_duration }}
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('user.transaction.invoice', $transaction->id) }}"
                                                                    class="btn btn-sm btn-primary shadow-none">
                                                                    @lang('Download')
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <div class="text-center">
                                                            <img src="{{ asset('assets/images/no-data.png') }}"
                                                                class="img-fluid" alt="">
                                                        </div>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
