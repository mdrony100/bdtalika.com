@extends('layouts.admin')

@section('show', 'show')
@section('paid_listing', 'active')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('Paid Listings') }}</h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Paid Listings') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">{{ __('Filter Paid Listings') }}</h5>
                    <form action="{{ route('admin.listing.paid') }}" id="paidListingForm" method="get">
                        <div class="row d-flex align-items-center">
                            <div class="col-sm-5 col-xl-4">
                                <label for="plan" class="form-label">{{ __('Plan') }}</label>
                                <select name="plan" id="plan" class="form-control">
                                    <option value="" class="d-none">{{ __('Select') }}</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}"
                                            {{ (string) Request::get('plan') === (string) $plan->id ? 'selected' : '' }}>
                                            {{ $plan->title }} ({{ ucfirst($plan->plan_type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-5 col-xl-4">
                                <label for="status" class="form-label">{{ __('Status') }}</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="" class="d-none">{{ __('Select') }}</option>
                                    <option value="all" {{ Request::get('status') == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                                    <option value="0" {{ Request::get('status') == '0' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                                    <option value="1" {{ Request::get('status') == '1' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                    <option value="2" {{ Request::get('status') == '2' ? 'selected' : '' }}>{{ __('Reject') }}</option>
                                    <option value="3" {{ Request::get('status') == '3' ? 'selected' : '' }}>{{ __('Requires Review') }}</option>
                                    <option value="4" {{ Request::get('status') == '4' ? 'selected' : '' }}>{{ __('Deactive') }}</option>
                                </select>
                            </div>

                            <div class="col-sm-2 col-xl-4">
                                @if (Request::get('plan') !== null || Request::get('status') !== null)
                                    <div class="align-self-center mt-3">
                                        <a href="{{ route('admin.listing.paid') }}" class="btn btn-primary">{{ __('Refresh') }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="dataTables" class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Featured Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    <th>{{ __('Paid Date') }}</th>
                                    <th>{{ __('Paid Ending Date') }}</th>
                                    <th>{{ __('Renewal') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Options') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listings as $listing)
                                    @php
                                        $paidDate = optional($listing->latestCompletedBkashPayment)->paid_at
                                            ?: optional($listing->latestTransaction)->created_at
                                            ?: $listing->plan_start_date;
                                        $endingDate = $listing->plan_end_date;
                                        $isLifetime = optional($listing->plan)->plan_type === 'lifetime';
                                        $isExpired = !$isLifetime && $endingDate && \Carbon\Carbon::parse($endingDate)->endOfDay()->lt(now());
                                        $daysLeft = (!$isLifetime && $endingDate)
                                            ? now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($endingDate)->startOfDay(), false)
                                            : null;

                                        $statusLabels = [
                                            0 => ['Draft', 'warning'],
                                            1 => ['Approved', 'success'],
                                            2 => ['Reject', 'danger'],
                                            3 => ['Requires Review', 'info'],
                                            4 => ['Deactive', 'secondary'],
                                        ];
                                        $status = $statusLabels[(int) $listing->status] ?? ['Unknown', 'secondary'];
                                    @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ $listing->photo ? url('assets/images/' . $listing->photo) : url('assets/images/default.png') }}"
                                                alt="Image" style="max-width: 90px; height: auto;">
                                        </td>
                                        <td>{{ $listing->name }}</td>
                                        <td>
                                            @if ($listing->user)
                                                <a href="{{ route('admin-user-show', $listing->user->id) }}">{{ $listing->user->name }}</a>
                                            @else
                                                {{ __('Bdtalika') }}
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ optional($listing->plan)->title ?: __('N/A') }}</strong><br>
                                            <small>{{ ucfirst(optional($listing->plan)->plan_type ?: __('N/A')) }}</small>
                                        </td>
                                        <td>
                                            @if ($paidDate)
                                                {{ \Carbon\Carbon::parse($paidDate)->format('d M Y') }}<br>
                                                <small>{{ \Carbon\Carbon::parse($paidDate)->format('h:i A') }}</small>
                                            @else
                                                <span class="text-muted">{{ __('Not found') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($isLifetime)
                                                <span class="badge badge-success px-3 py-2">{{ __('Lifetime') }}</span>
                                            @elseif ($endingDate)
                                                <strong>{{ \Carbon\Carbon::parse($endingDate)->format('d M Y') }}</strong>
                                                @if ($isExpired)
                                                    <br><span class="badge badge-danger mt-1">{{ __('Expired') }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">{{ __('Not set') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($isLifetime)
                                                <span class="text-success">{{ __('No renewal needed') }}</span>
                                            @elseif ($endingDate && $daysLeft >= 0)
                                                {{ $daysLeft }} {{ __('days left') }}
                                            @elseif ($endingDate)
                                                <span class="text-danger">{{ abs($daysLeft) }} {{ __('days overdue') }}</span>
                                            @else
                                                <span class="text-muted">{{ __('Date missing') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $status[1] }} px-3 py-2">{{ __($status[0]) }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group mb-1">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm btn-rounded dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ __('Actions') }}
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="{{ route('admin.listing.view', [$listing->id]) }}"
                                                        class="dropdown-item">{{ __('View') }}</a>
                                                    <a href="{{ route('admin.listing.edit.type', [$listing->id]) }}"
                                                        class="dropdown-item">{{ __('Edit') }}</a>
                                                    @if (!$isLifetime)
                                                        <a href="{{ route('admin.listing.mail.page', ['id' => $listing->id, 'type' => 'renewal']) }}"
                                                            class="dropdown-item">{{ __('Send Renewal Mail') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#plan, #status').change(function() {
                $('#paidListingForm').submit();
            });
        });
    </script>
@endsection
