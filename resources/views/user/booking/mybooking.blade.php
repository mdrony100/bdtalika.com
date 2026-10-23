@extends('layouts.front')

@push('css')
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
{{-- <h1 class="ft-medium">@lang('My Bookings')</h1> --}}
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
</li>
<li class="breadcrumb-item text-muted"><a
href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<div class="dashboard-wrap px-4 pt-3 pb-5 bg-light">
<a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false"
aria-controls="MobNav">
<i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
</a>
<div class="collapse" id="MobNav">
@includeIf('partials.user.sidebar')
</div>

<div class="dashboard-content">
<div class="dashboard-widg-bar d-block">
<div class="row">
<div class="col-xl-12 col-lg-12">
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<div class="dashboard-list-wraps-head br-bottom py-3 px-3">
<div class="dashboard-list-wraps-flx">
<h4 class="mb-0 ft-medium fs-md"><i
class="fas fa-shopping-basket me-2 theme-cl fs-sm"></i>@lang('All Bookings')</h4>
</div>
</div>

<div class="dashboard-list-wraps-body py-3 px-3">
<div class="dashboard-bookings-wraps">
@foreach ($bookings as $key => $listing)
<!-- Single booking List -->
<div class="dsd-single-bookings-wraps">
<div class="dsd-single-book-thumb"><img
src="{{ asset('assets/images/' . $listing->user->photo) }}"
class="img-fluid circle" alt=""></div>
<div class="dsd-single-book-caption">
<div class="dsd-single-book-title">
<h5>{{ $listing->user->name }}<span
class="bko-dates">{{ $listing->user->created_at->format('d M Y') }}</span>
</h5>
</div>
<div class="ico-content">
<ul>
<li>
<div
class="px-2 py-1 medium bg-light-{{ $listing->status == 0 ? 'warning' : ($listing->status == 1 ? 'success' : 'danger') }} rounded text-{{ $listing->status == 0 ? 'danger' : ($listing->status == 1 ? 'success' : 'primary') }}">
@if ($listing->status == 0)
@lang('Draft')
@elseif($listing->status == 1)
@lang('Approved')
@else
@lang('Rejected')
@endif
</div>
</li>
</ul>
</div>

<div class="dsd-single-descr">
<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Listing Item'):</span>
<span class="dsd-item-info">{{ $listing->listing->name }}</span>
</div>

<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Booking Date'):</span>
<span
class="dsd-item-info">{{ Carbon\Carbon::parse($listing->checkin_date)->format('d M Y') }}
{{ $listing->type == 'hotel' ? ' -- ' . Carbon\Carbon::parse($listing->checkout_date)->format('d M Y') : '' }}</span>
</div>

@if ($listing->type == 'beauty')
<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Service Name'):</span>
<span
class="dsd-item-info">{{ $listing->service->service_name }}</span>
</div>

@if ($listing->note)
<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Note'):</span>
<span class="dsd-item-info">{{ $listing->note }}</span>
</div>
@endif
@endif

@if ($listing->type != 'beauty')
<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Member'):</span>
<span class="dsd-item-info">{{ $listing->adults }}
@lang('Adults'), {{ $listing->kids }}
@lang('Child')</span>
</div>
@endif

<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Mail'):</span>
<span class="dsd-item-info">{{ $listing->user->name }}</span>
</div>

<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Phone'):</span>
<span class="dsd-item-info">{{ $listing->user->phone }}</span>
</div>
</div>
<div class="dsd-single-book-footer">
<a href="{{ route('user.booking.conversation', $listing->id) }}"
class="btn btn-message"><i
class="fas fa-envelope me-1"></i>@lang('Message')</a>
</div>
</div>
</div>
@endforeach

</div>
</div>
</div>
</div>
</div>

</div>

<!-- footer -->
{{-- <div class="row">
<div class="col-md-12">
<div class="py-3">
@php
echo $gs->copyright;
@endphp
</div>
</div>
</div> --}}

</div>

</div>
<!-- ======================= dashboard Detail End ======================== -->
@endsection

@push('js')
@endpush
