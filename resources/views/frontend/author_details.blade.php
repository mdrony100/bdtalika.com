@extends('layouts.front')
@push('css')
<style>
.Rego-grid-thumb:before {
content: "";
position: absolute;
left: 0;
top: 0;
right: 0;
bottom: 0;
opacity: 0.3;
background: #202838;
z-index: 1;
}

.Rego-grid-thumb {
position: relative;
}
.listing_location_info ul li{font-size:10px;min-width: 46px;}
</style>
@endpush
@section('content')
<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 colz-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('front.index') }}" class="color_link">Home</a></li>
<li class="breadcrumb-item"><a href="#">Author Listings</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<section class="bg-white pt-4">
<div class="container">
<div class="row">
<div class="col-xl-4 col-lg-4 col-md-12">
<div class="author-wrap-ngh rounded border">
@if (isset($admin))
<div class="author-wrap-head-ngh">
<div class="author-wrap-ngh-thumb">
@if ($admin->photo)
<img src="{{ asset('assets/images/' . $admin->photo) }}" class="img-fluid circle"
alt="{{ $admin->name }}">
@else
<img src="{{ asset('assets/front/img/t-7.png') }}" class="img-fluid circle"
alt="default image">
@endif
</div>
<div class="author-wrap-ngh-info">
<h5>{{ $admin->name }}</h5>
<div class="Rego-location"><i class="fas fa-map-marker-alt"></i>{{ $admin->address }}
</div>
</div>
</div>
@endif
@if (isset($user))
<div class="author-wrap-head-ngh">
<div class="author-wrap-ngh-thumb">
@if ($user->photo)
<img src="{{ asset('assets/images/' . $user->photo) }}" class="img-fluid circle"
alt="{{ $user->name }}">
@else
<img src="{{ asset('assets/front/img/t-7.png') }}" class="img-fluid circle"
alt="default image">
@endif
</div>
<div class="author-wrap-ngh-info">
<h5>{{ $user->name }}</h5>
<div class="Rego-location"><i class="fas fa-map-marker-alt"></i>{{ $user->address }}
</div>
</div>
</div>
@endif
<div class="author-wrap-caption-ngh">
<div class="author-wrap-jhyu-ngh">
<ul>
@if (isset($user))
<li>
<div class="list-kjvr">
<div class="list-kjvr-icon bg-light-sky text-sky"><i
class="fas fa-file"></i></div>
<div class="list-kjvr-ctr">
<h6 class="count">{{ count($user->listings) }}</h6>
</div>
<div class="list-kjvr-text">@lang('Listings')</div>
</div>
</li>
<li>
<div class="list-kjvr">
<div class="list-kjvr-icon bg-light-warning text-warning"><i
class="fas fa-thumbs-up"></i></div>
<div class="list-kjvr-ctr">
<h6 class="count">
{{ DB::table('followers')->where('user_id', $user->id)->count() }}
</h6><span></span>
</div>
<div class="list-kjvr-text">@lang('Followers')</div>
</div>
</li>
<li>
<div class="list-kjvr">
<div class="list-kjvr-icon bg-light-danger text-danger"><i
class="fas fa-heart"></i></div>
<div class="list-kjvr-ctr">
<h6 class="count">
{{ DB::table('followers')->where('follower_id', $user->id)->count() }}
</h6>
</div>
<div class="list-kjvr-text">@lang('Followings')</div>
</div>
</li>
@endif
@if (isset($admin))
<li>
<div class="list-kjvr">
<div class="list-kjvr-icon bg-light-sky text-sky"><i
class="fas fa-file"></i></div>
<div class="list-kjvr-ctr">
<h6 class="count">
{{ count(DB::table('listings')->where('user_id', null)->where('admin_id', null)->get()) }}
</h6>
</div>
<div class="list-kjvr-text">@lang('Listings')</div>
</div>
</li>
<li>
<div class="list-kjvr">
<div class="list-kjvr-icon bg-light-warning text-warning"><i
class="fas fa-thumbs-up"></i></div>
<div class="list-kjvr-ctr">
<h6 class="count">
{{ DB::table('followers')->where('user_id', 'NULL')->count() }}
</h6>
<span></span>
</div>
<div class="list-kjvr-text">@lang('Followers')</div>
</div>
</li>
<li>
<div class="list-kjvr">
<div class="list-kjvr-icon bg-light-danger text-danger"><i
class="fas fa-heart"></i></div>
<div class="list-kjvr-ctr">
<h6 class="count">
{{ (int) DB::table('recent_views_listings')->where('listing_owner_id', null)->count() }}
</h6>
</div>
<div class="list-kjvr-text">@lang('views')</div>
</div>
</li>
@endif
</ul>
</div>
{{-- @if (isset($admin))
<div class="author-wrap-yuio-ngh mt-5">
<div class="row g-4">
<div class="col-12">
<a href="javascript:void(0);" class="adv-btn full-width add-follow"
data-user="{{ auth()->id() ?? $user->id   }}" data-owner=""
data-listing="">@lang('Follow Now')</a>
</div>
</div>
</div>
@endif
@if (isset($user))
<div class="author-wrap-yuio-ngh mt-5">
<div class="row g-4">
<div class="col-12">
<a href="javascript:void(0);" class="adv-btn full-width add-follow"
data-user="{{ auth()->id() ?? $user->id }}"
data-owner="{{ $user->id }}" data-listing="">@lang('Follow Now')</a>
</div>
</div>
</div>
@endif --}}
</div>
<div class="author-wrap-footer-ngh">
<ul>
@if (isset($user))
@if (!$user->hide_email)
<li>
<div class="jhk-list-inf">
<div class="jhk-list-inf-ico"><i class="fas fa-envelope"></i></div>
<div class="jhk-list-inf-caption">
<h5>@lang('Mail Us')</h5>
<p>{{ $user->email }}</p>
</div>
</div>
</li>
@endif
@if (!$user->hide_phone)
<li>
<div class="jhk-list-inf">
<div class="jhk-list-inf-ico"><i class="fas fa-phone"></i></div>
<div class="jhk-list-inf-caption">
<h5>@lang('Make Call')</h5>
<p>{{ $user->phone }}</p>
</div>
</div>
</li>
@endif

@if ($user->direction)
<li>
<div class="jhk-list-inf">
<div class="jhk-list-inf-ico"><i class="fas fa-map-marker-alt"></i></div>
<div class="jhk-list-inf-caption">
<h5>@lang('Get Direction')</h5>
<p>{{ $user->direction }}</p>
</div>
</div>
</li>
@endif
@if ($user->website)
<li>
<div class="jhk-list-inf">
<div class="jhk-list-inf-ico"><i class="fas fa-globe"></i></div>
<div class="jhk-list-inf-caption">
<h5>@lang('Live Web'):</h5>
<p>{{ $user->website }}</p>
</div>
</div>
</li>
@endif
@endif

@if (isset($admin))
@if (!$admin->hide_email)
<li>
<div class="jhk-list-inf">
<div class="jhk-list-inf-ico"><i class="fas fa-envelope"></i></div>
<div class="jhk-list-inf-caption">
<h5>@lang('Mail Us')</h5>
<p>{{ $admin->email }}</p>
</div>
</div>
</li>
@endif
@if (!$admin->hide_phone)
<li>
<div class="jhk-list-inf">
<div class="jhk-list-inf-ico"><i class="fas fa-phone"></i></div>
<div class="jhk-list-inf-caption">
<h5>@lang('Make Call')</h5>
<p>{{ $admin->phone }}</p>
</div>
</div>
</li>
@endif
@if ($admin->direction)
<li>
<div class="jhk-list-inf">
<div class="jhk-list-inf-ico"><i class="fas fa-map-marker-alt"></i></div>
<div class="jhk-list-inf-caption">
<h5>@lang('Get Direction')</h5>
<p>{{ $admin->direction }}</p>
</div>
</div>
</li>
@endif
@if ($admin->website)
<li>
<div class="jhk-list-inf">
<div class="jhk-list-inf-ico"><i class="fas fa-globe"></i></div>
<div class="jhk-list-inf-caption">
<h5>@lang('Live Web'):</h5>
<p>{{ $admin->website }}</p>
</div>
</div>
</li>
@endif
@endif
</ul>
</div>
</div>
</div>
<div class="col-xl-8 col-lg-8 col-md-12">
<div class="row gx-3">  


@if (count($listings) > 0)
@foreach ($listings as $key => $data)

<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3">
<div class="Rego-grid-wrap card placeholder-wave">
@if ($data->is_feature)
<div class="Rego-pos ab-left reatured_tag">
<div class="Rego-status open me-2">Featured</div>
</div>
@endif
<div class="Rego-grid-upper">
<div class="Rego-bookmark-btn">
 <button type="button" class="wishList"
data-listing="{{ $data->id }}"
data-user="{{ auth()->id() ?? $user->id ?? $admin->id }}">
<i
class="lni lni-heart{{ $data->userFavourite(auth()->id(), $data->id) ? '-filled' : '' }} position-absolute"></i>
</button> 

</div>
@if ($data->is_featured)
<div class="Rego-pos ab-left">
{{-- <div class="Rego-status open me-2">{{ $data->openClose($data->id) }}</div> --}}
<div class="Rego-featured-tag">@lang('Featured')</div>
</div>
@endif
<div class="Rego-grid-thumb">
<div class="d-block text-center m-auto">
<img src="{{ asset('assets/images/' . ($data->photo ?? 'default.png')) }}"
class="img-fluid" alt="{{ Str::limit($data->name, 25) }}">
</div>
</div>
</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-caption px-3 py-2">
<div class="Rego-cates">
<span class="placeholder">{{ $data->category->name }}</span>
</div>
<div class="mb-0 ft-medium medium">
<a href="{{ route('front.listing.details', $data->slug) }}"
class="text-dark placeholder fs-md stretched-link" @if($data->verified == 'yes') data-toggle="tooltip" title="Verified by BD Talika" @endif>{{ Str::limit($data->name, 25) }}
 @if($data->verified == 'yes') <span class="verified-badge"><i
class="fas fa-check-circle"></i></span> @endif
</a>
</div>
<div class="listing_desc placeholder w-100 mt-2">  {!! Str::limit(strip_tags($data->description), 70) !!} </div>
{{-- 
<div class="Rego-middle-caption mt-3">
@if ($data->real_address)
<div class="Rego-location placeholder"><i
class="fas fa-map-marker-alt"></i>
{{ Str::limit($data->real_address, 30) }} </div>
@endif
@if ($data->phone_number)
<div class="Rego-call placeholder"><i
class="fas fa-phone-alt"></i>{{ $data->phone_number }}
</div>
@endif
</div>--}}

<div class="listing_location_info">
    <ul class="d-flex align-items-center p-0 mt-3">
@if (isset($data->phone_number))
<li class="placeholder"><i class="fas fa-phone-alt"></i> <span class="d-block">Phone</span></li>
@endif
@if (isset($data->email))
<li class="placeholder"> <i class="fas fa-envelope"></i> <span class="d-block">Address</span></li>
@endif
@if (isset($data->map_location))
<li class="placeholder"> <i class="fas fa-map-marker-alt"></i> <span class="d-block">Map</span></li>
@endif
@if (isset($data->website))
<li class="placeholder"><i class="fas fa-globe"></i> <span class="d-block">Website</span></li>
@endif
@if ($data->galleries->count() != 0 || $data->photo != null)
@php
$galleries    = $data->galleries->count();
$total_photos = ($data->photo != null ? 1 : 0) + $galleries;
@endphp
<li class="placeholder">
<i class="fas fa-image"></i> {{ $total_photos }}
<span class="d-block">Photos</span>
</li>
@endif
</ul>
</div>

</div>
{{-- @if (isset($data->average_rating) && $data->average_rating != null)
<div class="Rego-grid-footer py-3 px-3">
<div class="Rego-ft-first placeholder">
<div class="Rego-rating">
<div class="Rego-pr-average high">{{ $data->average_rating }}
</div>
<div class="Rego-rates ">
@for ($i = 1; $i <= 5; $i++)
@if ($i <= floor($data->average_rating))
<i class="fas fa-star text-warning"></i>
@elseif ($i == ceil($data->average_rating) && $data->average_rating - floor($data->average_rating) > 0)
<i class="fas fa-star-half-alt text-warning"></i>
@else
<i class="fas fa-star text-secondary"></i>
@endif
@endfor
</div>
</div>
</div>
</div>
@endif --}}
@if (count($data->reviews) > 0)
<div class="Rego-rating overlay position-relative">
<div class="Rego-pr-average high">
{{ $data->directoryRatting($data->id) }}</div>
<div class="Rego-aldeio">
<div class="Rego-rates">
@php
$rate = ceil($data->directoryRatting($data->id),);
@endphp
@for ($i = 1; $i <= $rate; $i++)
<i class="fas fa-star active"></i>
@endfor
@for ($i = 1; $i <= 5 - $rate; $i++)
<i class="fas fa-star"></i>
@endfor
</div>
<div class="Rego-all-review">
<span class="text-dark">{{ count($data->reviews) }} @lang('Reviews')</span>
</div>
</div>
</div>
@endif
</div>
</div>
</div>
@endforeach
@else
<div class="col-12 text-center">
<h3 class="m-0">{{ __('No Directory Found') }}</h3>
</div>
@endif
</div>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12">
@if ($listings->hasPages())
{{ $listings->links() }}
@endif
</div>
</div>
</div>
</div>
</div>
</section>
<!-- ============================ Call To Action ================================== -->
{{-- @includeIf('partials.front.cta') --}}
<!-- ============================ Call To Action End ================================== -->
@endsection
@push('js')
<script>
'use strict';
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$(document).on('click', '.wishList', function() {
let $this = $(this);
let listingId = $(this).data('listing');
let userId = $(this).data('user');
if (userId == '') {
window.location.href = mainurl + '/user/login'
}
$.ajax({
method: "POST",
url: mainurl + '/listing/wishlist',
data: {
listing_id: listingId,
user_id: userId
},
success: function(data) {
if (data.success) {
$this.children().prop('class', '');
$this.children().prop('class', 'lni lni-heart-filled  position-absolute');
toastr.success(data.success);
} else {
$this.children().prop('class', '');
$this.children().prop('class', 'lni lni-heart  position-absolute');
toastr.error(data.error);
}
}
});
})
$(document).on('click', '.add-follow', function() {
let $this = $(this);
let listingId = $(this).data('listing');
let ownerId = $(this).data('owner');
let userId = $(this).data('user');
if (userId == '') {
window.location.href = mainurl + '/user/login'
}
$.ajax({
method: "POST",
url: mainurl + '/follow',
data: {
listing_id: listingId,
owner_id: ownerId,
user_id: userId,
},
success: function(data) {
if (data.success) toastr.success(data.success);
else toastr.error(data.error);
}
});
});
</script>
@endpush
