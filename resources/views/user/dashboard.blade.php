@extends('layouts.front')
@push('css')
<style>
.btn.theme-bg.rounded.text-light {background: #1768E0 !important;padding: 7px 18px;font-size: 14px;}
.agd-location {max-width: 700px !important;display: block;}
.dashboard-listings-row {margin-top: 0px;}
.customer-message-item {border-bottom:1px solid #edf0f5;padding:12px 0;}
.customer-message-item:last-child {border-bottom:0;}
.customer-message-meta {font-size:13px;color:#6c757d;}
.customer-message-text {font-size:14px;color:#2d3340;margin:6px 0 0;}
@media screen and (max-width:575px){.dsd-single-listing-wraps {display: block;}.dsd-single-lst-caption{padding-left:0;}.dsd-single-lst-thumb{margin-bottom:20px;}}
</style>
@endpush
@section('content')
<div class="bg_light px-4 py-2">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a></li>
<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="">@lang('Dashboard')</a></li>
</ol>
</nav>
@if(session()->has('admin_impersonating_user'))
<div class="text-end mt-2">
<a href="{{ route('admin.user.back.to.admin') }}" class="btn btn-sm btn-primary">
@lang('Back to Admin Panel')
</a>
</div>
@endif
</div>
</div>
</div>
<div class="dashboard-wrap px-4 pt-3 pb-5 bg-light">
<a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false" aria-controls="MobNav">
<i class="fas fa-bars me-2"></i>@lang('Listing')
</a>
<div class="collapse" id="MobNav">
@includeIf('partials.user.sidebar')
</div>
<div class="dashboard-content">
<div class="dashboard-widg-bar d-block">

<div class="row">
<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
<div class="dsd-boxed-widget py-3 px-3 bg-danger rounded">
<h2 class="ft-medium mb-1 fs-xl text-light count">{{ $active_listings }}</h2>
<p class="p-0 m-0 text-light fs-md">@lang('Active Listings')</p>
<i class="lni lni-empty-file"></i>
</div>
</div>
<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
<div class="dsd-boxed-widget py-3 px-3 bg-success rounded">
<h2 class="ft-medium mb-1 fs-xl text-light count">{{ $view_listings }}</h2>
<p class="p-0 m-0 text-light fs-md">@lang('Views Listing')</p>
<i class="lni lni-eye"></i>
</div>
</div>
<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
<div class="dsd-boxed-widget py-3 px-3 bg-warning rounded">
<h2 class="ft-medium mb-1 fs-xl text-light count">{{ $total_reviews }}</h2>
<p class="p-0 m-0 text-light fs-md">@lang('Total Reviews')</p>
<i class="lni lni-comments"></i>
</div>
</div>
<!-- <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
<div class="dsd-boxed-widget py-3 px-3 bg-purple rounded">
<h2 class="ft-medium mb-1 fs-xl text-light count">{{ $total_bookings }}</h2>
<p class="p-0 m-0 text-light fs-md">@lang('Total Bookings')</p>
<i class="lni lni-wallet"></i>
</div>
</div> -->
</div>
<!-- row -->
<div class="row">
<div class="col-md-4 col-sm-12">
<div class="dash-card bg-white border shadow-sm rounded-3">
<div class="dash-card-header">
<h4>Followers</h4>
</div>
@if (count($followers) == 0)
<div class="ground-list ground-hover-list">
<div class="ground ground-list-single">
<p>@lang('No Follower Found')</p>
</div>
</div>
@else
@foreach ($followers as $key=>$data)
<div class="ground-list ground-hover-list">
<div class="ground ground-list-single">
<a href="#">
<img class="ground-avatar" src="{{ asset('assets/images/'.$data->user->photo)}}" alt="...">
<span class="profile-status bg-online pull-right"></span>
</a>
<div class="ground-content">
<h6><a href="#">{{ $data->user->name }}</a></h6>
<small class="text-fade"><i class="fas fa-map-marker-alt me-1"></i>{{ $data->user->address }}</small>
</div>
</div>
</div>
@endforeach
@endif
</div>
</div>
<div class="col-md-4 col-sm-6">
<a href="{{ route('user.listing.create') }}">
<div class="dash-card bg-white border shadow-sm rounded-3">
<div class="ground-list ground-hover-list">
<div class="ground ground-list-single d-block text-center color_link" style="padding:45px 0px;">
    Add Listing
</div>
</div>
</div>
</a>
</div> 
</div>
<div class="row dashboard-listings-row">
<div class="col-xl-8 col-lg-10 col-md-12">
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<div class="dashboard-list-wraps-head br-bottom py-3 px-3">
<div class="dashboard-list-wraps-flx">
<div class="d-flex justify-content-between align-items-center">
<h4 class="mb-0 ft-medium fs-md">
<i class="fa fa-file-alt me-2 theme-cl fs-sm"></i>
@lang('My Listings')
</h4>
<a href="{{ route('user.listing.index') }}" class="btn theme-bg rounded text-light">@lang('View All')</a>
</div>
</div>
</div>
<div class="dashboard-list-wraps-body py-3 px-3">
<div class="dashboard-listing-wraps">
@forelse ($listings as $key => $data)
@include('user.listing.partials.card', ['data' => $data])
@empty
<div class="text-center mt-4">
<img src="{{ asset('assets/images/no-data.png') }}" class="img-fluid" alt="">
</div>
@endforelse
</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-xl-8 col-lg-10 col-md-12">
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<div class="dashboard-list-wraps-head br-bottom py-3 px-3">
<div class="dashboard-list-wraps-flx">
<div class="d-flex justify-content-between align-items-center">
<h4 class="mb-0 ft-medium fs-md">
<i class="fa fa-envelope me-2 theme-cl fs-sm"></i>
@lang('Customer Messages')
</h4>
<a href="{{ route('user.customer.enquiry') }}" class="btn theme-bg rounded text-light">@lang('View All')</a>
</div>
</div>
</div>
<div class="dashboard-list-wraps-body py-2 px-3">
@forelse ($customer_messages as $message)
<div class="customer-message-item">
<div class="d-flex justify-content-between flex-wrap">
<strong>{{ $message->name ?? __('Customer') }}</strong>
<span class="customer-message-meta">{{ $message->created_at->format('d M, Y h:i A') }}</span>
</div>
<div class="customer-message-meta">
{{ optional($message->listing)->name ?? __('Listing Deleted') }} | {{ $message->email }} | {{ $message->phone }}
</div>
<p class="customer-message-text">{{ \Illuminate\Support\Str::limit($message->details, 140) }}</p>
</div>
@empty
<div class="text-center py-3">@lang('No Message Found')</div>
@endforelse
</div>
</div>
</div>
</div>
 </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Listing Rejected Details</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-3">
<div id="datahtml"></div>
</div>
</div>
</div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/front/js/plugins/raphael/raphael.min.js')}}"></script>
<script src="{{ asset('assets/front/js/plugins/morris.js/morris.min.js')}}"></script>
<script src="{{ asset('assets/front/js/plugins/dashboard-2.js')}}"></script>
<script>
'use strict';
function myFunction() {
var copyText = document.getElementById("cronjobURL");
copyText.select();
copyText.setSelectionRange(0, 99999);
document.execCommand("copy");
$.notify("Referral url copied", "info");
}
</script>
<script>
$(document).ready(function() {
$("body").on('click', '.rejectView', function() {
let url = $(this).attr('href');
$.ajax({
url: url,
type: "GET",
success: function(data) {
$('#datahtml').text(data.reject_reason);
}
})
});
});
</script>
@endpush
