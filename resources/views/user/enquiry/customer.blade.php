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
{{-- <h1 class="ft-medium">@lang('Customer Enquiry')</h1> --}}
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
class="fas fa-shopping-basket me-2 theme-cl fs-sm"></i>@lang('All Enquiries')
</h4>
</div>
</div>

<div class="dashboard-list-wraps-body py-3 px-3">
<div class="dashboard-bookings-wraps">
@if (count($enquires) == 0)
<h3 class="text-center">@lang('No Data Found')</h3>
@else
@foreach ($enquires as $key => $data)
<!-- Single booking List -->
<div class="dsd-single-bookings-wraps">
<div class="dsd-single-book-thumb"><img
src="{{ asset('assets/images/' . (optional($data->user)->photo ?? 'default.png')) }}"
class="img-fluid circle" alt=""></div>
<div class="dsd-single-book-caption">
<div class="dsd-single-book-title">
<h5>{{ optional($data->user)->name ?? $data->name ?? __('Customer') }}<span
class="bko-dates">{{ $data->created_at->format('d M, Y h:i A') }}</span>
</h5>
</div>
<div class="dsd-single-descr">
<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Listing Item'):</span>
<span
class="dsd-item-info">{{ $data->listing->name }}</span>
</div>

<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Mail'):</span>
<span class="dsd-item-info">{{ $data->email }}</span>
</div>

<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Phone'):</span>
<span class="dsd-item-info">{{ $data->phone }}</span>
</div>

<div class="dsd-single-item">
<span class="dsd-item-title">@lang('Message'):</span>
<span class="dsd-item-info">{{ $data->details }}</span>
</div>

</div>
</div>
</div>
@endforeach
@endif
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
