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
{{-- <h1 class="ft-medium">@lang('Listing Type')</h1> --}}
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
</li>
<li class="breadcrumb-item"><a href="#" class="">@lang('Add Listing')</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<div class="dashboard-wrap bg-light px-2 px-lg-4 pt-3 pb-5">
<a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false" aria-controls="MobNav">
<i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
</a>
<div class="collapse" id="MobNav">
@includeIf('partials.user.sidebar')
</div>

<div class="dashboard-content">
<div class="dashboard-widg-bar d-block">
{{-- <div class="row">
<div class="col-lg-12 col-md-12 col-12">
@if (now() < auth()->user()->plan_end_date)
<h3>@lang('You have') {{ auth()->user()->ad_limit }} @lang('directory left to add.')</h3>
@else
<h3>@lang('Current plan date expired!')</h3>
@endif
</div>
</div> --}}
<div class="row d-flex">
<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
<a href="{{ route('user.listing.create', ['type' => 'restaurant']) }}">
<div class="dsd-boxed-widget py-5 px-3 bg-danger rounded">
<p class="p-0 m-0 text-light fs-md">@lang('Restaurant')</p>
<i>
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tools-kitchen-2" width="55" height="55" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 3v12h-5c-.023 -3.681 .184 -7.406 5 -12zm0 12v6h-1v-3m-10 -14v17m-3 -17v3a3 3 0 1 0 6 0v-3" /></svg>
</i>
</div>
</a>
</div>
<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
<a href="{{ route('user.listing.create', ['type' => 'hotel']) }}">
<div class="dsd-boxed-widget py-5 px-3 bg-success rounded">
<p class="p-0 m-0 text-light fs-md">@lang('Hotel')</p>
<i>
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-skyscraper" width="55" height="55" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M5 21v-14l8 -4v18" /><path d="M19 21v-10l-6 -4" /><path d="M9 9l0 .01" /><path d="M9 12l0 .01" /><path d="M9 15l0 .01" /><path d="M9 18l0 .01" /></svg>
</i>
</div>
</a>
</div>
<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
<a href="{{ route('user.listing.create', ['type' => 'beauty']) }}">
<div class="dsd-boxed-widget py-5 px-3 bg-warning rounded">
<p class="p-0 m-0 text-light fs-md">@lang('Beauty')</p>
<i>
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-woman" width="55" height="55" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 16v5" /><path d="M14 16v5" /><path d="M8 16h8l-2 -7h-4z" /><path d="M5 11c1.667 -1.333 3.333 -2 5 -2" /><path d="M19 11c-1.667 -1.333 -3.333 -2 -5 -2" /><path d="M12 4m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /></svg>
</i>
</div>
</a>
</div>

<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
<a href="{{ route('user.listing.create', ['type' => 'real_estate']) }}">
<div class="dsd-boxed-widget py-5 px-3 bg-purple rounded">
<p class="p-0 m-0 text-light fs-md">@lang('Real Estate')</p>
<i>
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-estate" width="55" height="55" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21h18" /><path d="M19 21v-4" /><path d="M19 17a2 2 0 0 0 2 -2v-2a2 2 0 1 0 -4 0v2a2 2 0 0 0 2 2z" /><path d="M14 21v-14a3 3 0 0 0 -3 -3h-4a3 3 0 0 0 -3 3v14" /><path d="M9 17v4" /><path d="M8 13h2" /><path d="M8 9h2" /></svg>
</i>
</div>
</a>
</div>

<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
<a href="{{ route('user.listing.create', ['type' => 'hospitals']) }}">
<div class="dsd-boxed-widget py-5 px-3 bg-info rounded">
<p class="p-0 m-0 text-light fs-md">@lang('Hospitals')</p>
<i>
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-hospital" width="55" height="55" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /><path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" /><path d="M10 9l4 0" /><path d="M12 7l0 4" /></svg>
</i>
</div>
</a>
</div>

<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
<a href="{{ route('user.listing.create', ['type' => 'car']) }}">
<div class="dsd-boxed-widget py-5 px-3 bg-secondary rounded">
<p class="p-0 m-0 text-light fs-md">@lang('Car')</p>
<i>
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-car" width="55" height="55" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5" /></svg>
</i>
</div>
</a>
</div>

<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
<a href="{{ route('user.listing.create', ['type' => 'other']) }}">
<div class="dsd-boxed-widget py-5 px-3 bg-dark rounded">
<p class="p-0 m-0 text-light fs-md">@lang('Other')</p>
<i>
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ad" width="55" height="55" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M7 15v-4a2 2 0 0 1 4 0v4" /><path d="M7 13l4 0" /><path d="M17 9v6h-1.5a1.5 1.5 0 1 1 1.5 -1.5" /></svg>
</i>
</div>
</a>
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
