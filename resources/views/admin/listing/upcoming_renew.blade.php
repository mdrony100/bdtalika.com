@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('Upcoming Renew') }}</h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Upcoming Renew') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.listing.upcoming.renew') }}" id="upcomingRenewForm" method="get">
                        <div class="row g-3">
                            <div class="col-sm-4 col-xl-3">
                                <label for="days" class="form-label">{{ __('Upcoming Window') }}</label>
                                <select name="days" id="days" class="form-control">
                                    <option value="7" {{ $window == 7 ? 'selected' : '' }}>{{ __('Next 7 days') }}</option>
                                    <option value="14" {{ $window == 14 ? 'selected' : '' }}>{{ __('Next 14 days') }}</option>
                                    <option value="30" {{ $window == 30 ? 'selected' : '' }}>{{ __('Next 30 days') }}</option>
                                    <option value="60" {{ $window == 60 ? 'selected' : '' }}>{{ __('Next 60 days') }}</option>
                                    <option value="90" {{ $window == 90 ? 'selected' : '' }}>{{ __('Next 90 days') }}</option>
                                </select>
                            </div>

                            <div class="col-sm-4 col-xl-3">
                                <label for="range" class="form-label">{{ __('Range') }}</label>
                                <select name="range" id="range" class="form-control">
                                    <option value="upcoming" {{ !$showExpired ? 'selected' : '' }}>{{ __('Upcoming renewals') }}</option>
                                    <option value="expired" {{ $showExpired ? 'selected' : '' }}>{{ __('Already expired') }}</option>
                                </select>
                            </div>

                            <div class="col-sm-4 col-xl-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @if ($showExpired)
                            {{ __('Expired packages') }} ({{ $listings->count() }})
                        @else
                            {{ __('Businesses to renew in next') }} {{ $window }} {{ __('days') }} ({{ $listings->count() }})
                        @endif
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="dataTables" class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('Featured Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    <th>{{ __('Paid Date') }}</th>
                                    <th>{{ __('Renewal Date') }}</th>
                                    <th>{{ __('Days Left') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Options') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($listings as $listing)
                                    @php
                                        $paidDate = optional($listing->latestCompletedBkashPayment)->paid_at
                                            ?: optional($listing->latestTransaction)->created_at
                                            ?: $listing->plan_start_date;
                                        $endingDate = $listing->plan_end_date;
                                        $ending = $endingDate ? \Carbon\Carbon::parse($endingDate)->startOfDay() : null;
                                        $isExpired = $ending && $ending->lt(now()->startOfDay());
                                        $daysLeft = $ending ? (int) now()->startOfDay()->diffInDays($ending, false) : null;

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
                                                alt="Image" style="max-width: 80px; height: auto;">
                                        </td>
                                        <td>
                                            @if ($listing->status == 1)
                                                <a href="{{ route('front.listing.details', $listing->slug) }}"
                                                    target="_blank" class="color_link">{{ $listing->name }}</a>
                                            @else
                                                {{ $listing->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($listing->user)
                                                <a href="{{ route('admin-user-show', $listing->user->id) }}">{{ $listing->user->name }}</a>
                                            @else
                                                {{ __('Bdtalika') }}
                                            @endif
                                        </td>
                                        <td>{{ $listing->phone_number ?: ($listing->user->phone ?? '') }}</td>
                                        <td>
                                            <strong>{{ optional($listing->plan)->title ?: __('N/A') }}</strong><br>
                                            <small>{{ ucfirst(optional($listing->plan)->plan_type ?: __('N/A')) }}</small>
                                        </td>
                                        <td>
                                            @if ($paidDate)
                                                {{ \Carbon\Carbon::parse($paidDate)->format('d M Y') }}
                                            @else
                                                <span class="text-muted">{{ __('Not found') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($endingDate)
                                                <strong>{{ \Carbon\Carbon::parse($endingDate)->format('d M Y') }}</strong>
                                            @else
                                                <span class="text-muted">{{ __('Not set') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($isExpired)
                                                <span class="badge badge-danger px-3 py-2">{{ abs($daysLeft) }} {{ __('days ago') }}</span>
                                            @elseif ($daysLeft !== null && $daysLeft <= 7)
                                                <span class="badge badge-danger px-3 py-2">{{ $daysLeft }} {{ __('days left') }}</span>
                                            @elseif ($daysLeft !== null && $daysLeft <= 15)
                                                <span class="badge badge-warning px-3 py-2">{{ $daysLeft }} {{ __('days left') }}</span>
                                            @elseif ($daysLeft !== null)
                                                <span class="badge badge-info px-3 py-2">{{ $daysLeft }} {{ __('days left') }}</span>
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
                                                    <a href="{{ route('admin.listing.mail.page', ['id' => $listing->id, 'type' => 'renewal']) }}"
                                                        class="dropdown-item">{{ __('Send Renewal Mail') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">{{ __('No listings found in this range') }}</td>
                                    </tr>
                                @endforelse
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
            $('#days, #range').change(function() {
                $('#upcomingRenewForm').submit();
            });
        });
    </script>
@endsection
