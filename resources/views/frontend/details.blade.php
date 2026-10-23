@extends('layouts.front')
@php
$area_query = $area_query ?? 'bangladesh'; $get_category_query = $get_category_query ?? null;
$plan    = getListingPlan($data->plan_id);
$placeholder = isset($data->status) && $data->status == '4' ? 'disabled_text' : '';
@endphp 
@push('css')
<style>
.Rego-lkp-title h1 {font-size: 20px;}
@media screen and (min-width:1200px){.border_xl_end{border-right:1px solid #f2e8e8;}}
@media screen and (max-width:1199px){.border_bottom{border-bottom:1px solid #f2e8e8;}}
.disabled_text {filter: blur(6px);transition: filter 0.3s ease;cursor: not-allowed !important;!i;!;pointer-events: none;user-select: none;}
.listing_location_info ul li {font-size: 11px;}
.listing-message-btn {background:#1768E0;color:#fff;border:0;}
.listing-message-btn:hover,.listing-message-btn:focus {background:#1259c2;color:#fff;}
.listing-message-modal .modal-content {border:0;border-radius:8px;overflow:hidden;box-shadow:0 18px 60px rgba(17,24,39,.22);}
.listing-message-modal .modal-header {padding:22px 26px;border-bottom:1px solid #eef1f5;background:#fff;}
.listing-message-modal .modal-title {font-size:22px;font-weight:700;color:#172033;}
.listing-message-modal .btn-close {box-shadow:none;opacity:.65;}
.listing-message-modal .modal-body {padding:24px 26px 12px;background:#fff;}
.listing-message-modal .form-group {margin-bottom:18px;}
.listing-message-modal label {font-size:14px;font-weight:600;color:#344054;margin-bottom:7px;}
.listing-message-modal .form-control {height:46px;border:1px solid #dfe5ee;border-radius:6px;color:#172033;box-shadow:none;}
.listing-message-modal textarea.form-control {height:auto;min-height:140px;resize:vertical;}
.listing-message-modal .form-control:focus {border-color:#1768E0;box-shadow:0 0 0 3px rgba(23,104,224,.12);}
.listing-message-captcha {height:46px;display:flex;align-items:center;justify-content:center;border:1px dashed #b7c3d4;border-radius:6px;background:#f7f9fc;font-weight:700;color:#172033;}
.listing-message-modal .modal-footer {padding:18px 26px 22px;border-top:1px solid #eef1f5;background:#f8fafc;}
.listing-message-modal .modal-footer .btn {height:46px;border-radius:6px;font-weight:600;padding:0 26px;}
.listing-message-modal .btn-light {background:#fff;color:#667085;border:1px solid #dfe5ee;}
@media screen and (max-width:575px){.listing-message-modal .modal-header,.listing-message-modal .modal-body,.listing-message-modal .modal-footer{padding-left:18px;padding-right:18px;}.listing-message-modal .modal-footer{display:grid;grid-template-columns:1fr;}.listing-message-modal .modal-footer .btn{width:100%;}}
</style>
@endpush
@push('meta_info')
<title>{{ $data->meta_title ??  $data->name}} </title>
<meta name="Developer" content="developed by bdtalika">
<meta property="og:title" content="{{ $data->meta_title ??  $data->name}}">
<meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($data->meta_description ?? $data->description), 160, ' ...') }}">
<meta name="keywords" content="{{ $data->meta_keywords ?? ''}}">
<meta property="og:site_name" content="bdtalika.com">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($data->meta_description ?? $data->description), 160, ' ...') }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('front.listing.details', $data->slug) }}">
<meta property="og:image" name="og_image" content="{{ asset('assets/images/' . $data->photo) }}">
<meta property="og:image:width" content="600">
<meta property="og:image:height" content="315">
<meta name="robots" content="index">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
@php
    $bizSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => $data->name,
        'url' => route('front.listing.details', $data->slug),
    ];
    if (!empty($data->photo)) { $bizSchema['image'] = asset('assets/images/' . $data->photo); }
    $bizDescription = $data->meta_description ?? $data->description ?? '';
    if (trim(strip_tags((string) $bizDescription)) !== '') { $bizSchema['description'] = \Illuminate\Support\Str::limit(strip_tags($bizDescription), 300); }
    if (!empty($data->real_address)) { $bizSchema['address'] = ['@type' => 'PostalAddress', 'streetAddress' => $data->real_address, 'addressCountry' => 'BD']; }
    if (!empty($data->phone_number)) { $bizSchema['telephone'] = $data->phone_number; }
    if ($data->latitude !== null && $data->latitude !== '' && $data->longitude !== null && $data->longitude !== '') {
        $bizSchema['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => (string) $data->latitude, 'longitude' => (string) $data->longitude];
    }
@endphp
<script type="application/ld+json">{!! json_encode($bizSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
@section('content')
<div class="bg-light py-2">
<div class="container">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a class="color_link" href="{{ route('front.index') }}">Listing</a></li>
<li class="breadcrumb-item"><a class="color_link" href="{{ route('front.listing', ['area' => 'bangladesh', 'category' => $data->category->slug]) }}">{{ $data->category->title }}</a>
</li>
<li class="breadcrumb-item"><span>{{ $data->name }}</span></li>
</ol>
</nav>
</div>
</div>
<section class="bg-white position-relative">
<div class="container">
@if ($data->galleries->count() != 0)
<div class="featured-slick">
<div class="featured-slick-slide">
<div class="dlf-flew">
<a href="{{ asset('assets/images/' . $data->photo) }}" class="mfp-gallery" title="{{ $data->name }}"><img src="{{ asset('assets/images/' . $data->photo) }}" class="img-fluid mx-auto" alt="{{ $data->name }}"></a>
</div>
@if ($data->galleries)
@foreach ($data->galleries as $key => $gallery)
<div class="dlf-flew">
<a href="{{ asset('assets/images/' . $gallery->photo) }}" class="mfp-gallery" title="{{ $data->name }}"><img src="{{ asset('assets/images/' . $gallery->photo) }}" class="img-fluid mx-auto" alt="{{ $data->name }}"></a>
</div>
@endforeach
@endif
</div>
</div>
@endif
<div class="row g-0">
<div class="col-xl-7">
<div class="bg-light p-3 position-relative border_bottom border_xl_end h-100" style="min-height: 266px;">
<div class="wishlist_tag">
<button type="button" class="wishList" data-listing="{{ $data->id }}" data-user={{ auth()->id() ?? $data->id }}>
<i class="lni lni-heart{{ $data->userFavourite(auth()->id(), $data->id) ? '-filled' : '' }} position-absolute"></i>
</button>
</div>
<div class=""> <div class="listing_img mb-3 mb-sm-0"><div>
<div class="Rego-lkp-title mb-2"><h1 class="mb-0 ft-bold" @if($data->verified == 'yes') data-toggle="tooltip" title="Verified by BD Talika" @endif >{{ $data->name }} @if($data->verified == 'yes') <span class="verified-badge"><i class="fas fa-check-circle"></i></span> @endif</h1></div>
@if ($data->distance)
<div class="ist-lioe pb-3"><span>Distance: {{ $data->distance }}</span></div>
@endif
@if (isset($data->average_rating) && $data->average_rating != null)
<span class="me-2 bg bg-success p-1 rounded text-white px-2" style="background: #058a07 !important;">{{ $data->average_rating }}</span>
<span class="me-2">
@for ($i = 1; $i <= 5; $i++)
@if ($i <= floor($data->average_rating))
<i class="fas fa-star text-warning"></i>
@elseif ($i == ceil($data->average_rating) && $data->average_rating - floor($data->average_rating) > 0)
<i class="fas fa-star-half-alt text-warning"></i>
@else
<i class="fas fa-star text-secondary"></i>
@endif
@endfor
</span>
@endif 
@if ($data->total_review)
<span class="">{{ $data->total_review }} Reviews</span>
@endif
<div class="jb-apply-form mt-4">

 
<div class="uli-list-info">
{{------custom message------}}
@if($isFreePlan && $isOwner)
<div class="alert alert-warning border-0 shadow-sm rounded-3 mt-4 p-4 position-relative overflow-hidden">
    <div class="d-flex align-items-start">
        <div class="me-3">
            <div style="
                width:60px;
                height:60px;
                background:#fff3cd;
                border-radius:50%;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:28px;
                color:#ff9800;
                border:2px solid #ffe08a;
            ">
                <i class="fas fa-lock"></i>
            </div>
        </div>
        <div class="flex-grow-1">
            <h4 class="fw-bold text-dark mb-2">
                কন্টাক্ট তথ্য বর্তমানে Hidden আছে
            </h4>
            <p class="mb-3 text-dark" style="font-size:15px; line-height:1.8;">
                আপনার ব্যবসার <strong>Phone Number</strong>,
                <strong>Website</strong>,
                <strong>Email</strong>,
                <strong>WhatsApp</strong> এবং
                <strong>Google Map</strong> বর্তমানে Visitors দেখতে পাচ্ছে না।
                <br>
                এগুলো Customers কে দেখাতে এখনই একটি
                <strong class="text-danger">Paid Plan</strong> নিন।
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('user.listing.plan', $data->id) }}"
                   class="btn btn-danger px-4 py-2 fw-bold">

                    <i class="fas fa-crown me-2"></i>
                    এখনই Paid Plan নিন
                </a>
                @if(!empty($gs->support_email))
                <a href="mailto:{{ $gs->support_email }}"
                   class="btn btn-dark px-4 py-2 fw-semibold">

                    <i class="fas fa-envelope me-2"></i>
                    Support Team
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
<div class="row">
@if(!empty($data->website) && isset($plan) && $plan->plan_type !== 'free')
<div class="col-sm-6 col-md-4 mb-2">
<div class="list-uiyt {{ $placeholder }}">
<div class="list-uiyt-capt">
<a href="{{ $data->website }}" rel="{{ isset($plan->follow) && $plan->follow == '1' ? 'dofollow' : 'nofollow' }}" target="{{ $data->website }}" class="adv-btn ms-2 me-2 full-width bg-secondary text-light track-listing-action" data-event="website" data-listing="{{ $data->id }}"><i class="fas fa-globe"></i>
Visit Website</a>
</div>
</div>
</div>
@endif
@if (isset($data->latitude) && isset($data->longitude))
<div class="col-sm-6 col-md-4 mb-2">
    <div class="list-uiyt {{ $placeholder }}">
        <div class="list-uiyt-capt">
            <a href="https://www.google.com/maps/search/?api=1&query={{ $data->latitude }},{{ $data->longitude }}" 
               target="_blank"
               class="adv-btn ms-2 me-2 full-width bg-info text-light">
                <i class="fas fa-location-arrow me-2"></i>
                Get Direction
            </a>
        </div>
    </div>
</div>
@endif

@if(!empty($data->phone_number) && isset($plan) && $plan->plan_type !== 'free')
<div class="col-sm-6 col-md-4 mb-2">
<div class="list-uiyt {{ $placeholder }}">
<div class="list-uiyt-capt">
    <a href="tel:{{ $data->phone_number }}" class="adv-btn ms-2 me-2 full-width bg-success text-light track-listing-action" data-event="phone" data-listing="{{ $data->id }}"><i class="fas fa-phone-alt"></i>@lang('Call Now')</a>
</div>

</div>
</div>
@endif
@if(!empty($data->whatsapp_number) && isset($plan) && $plan->plan_type !== 'free')
<div class="col-sm-6 col-md-4">
    <div class="list-uiyt {{ $placeholder }}">
        <div class="list-uiyt-capt">
            <a style="background-color: #128c7e;" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->whatsapp_number) }}"
               target="_blank"
               class="adv-btn ms-2 me-2 full-width text-light track-listing-action" data-event="whatsapp" data-listing="{{ $data->id }}">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
        </div>
    </div>
</div>
@endif
@if (isset($data->real_address))
<div class="col-lg-6 mt-3">
<div class="list-uiyt {{ $placeholder }}">
<div class="list-uiyt-capt">
<h2>@lang('Address')</h2>
<p>{{ $data->real_address }}, <a href="{{ route('front.listing', ['area' => $data->division->slug, 'category' => $get_category_query]) }}"
class="color_link"> {{ $data->division->name }}</a>, Bangladesh </p>
</div>
</div>
</div>
@endif
@if (isset($data->email))
<div class="col-sm-6 col-md-4 mt-3">
<div class="list-uiyt {{ $placeholder }}">
<div class="list-uiyt-capt">
<h2>@lang('Drop a Mail')</h2>
<p><a href="mailto:{{ $data->email }}" class="track-listing-action" data-event="email" data-listing="{{ $data->id }}">{{ $data->email }}</a></p>
</div>
</div>
</div>
@endif
<div class="col-lg-6 mt-3">
<div class="list-uiyt {{ $placeholder }}">
<div class="list-uiyt-capt">
<h2> @lang('Listed in Categories') </h2>
<p>
{{-- <a class="text-link" href="{{ route('front.type.listing', $data->listingType->slug) }}">{{ ucfirst($data->listingType->name) }}</a>, --}}
<a class="text-link color_link" href="{{ route('front.listing', ['area' => $area_query, 'category' => $data->category->slug]) }}">{{ ucfirst($data->category->title) }}</a>
</p>
</div>
</div>
</div>
</div>
</div>

<ul class="p-0 d-flex mb-0 pt-2 social_list">
@if ($data->facebook)
<li class="pe-2 {{ $placeholder }}"><a target="_blank" href="{{ $data->facebook }}"><i class="fab fa-facebook"></i></a></li>
@endif

@if ($data->twitter)
<li class="pe-2 {{ $placeholder }}"><a target="_blank" href="{{ $data->twitter }}"><i class="fab fa-twitter"></i></a></li>
@endif

@if ($data->linkedin)
<li class="pe-2 {{ $placeholder }}"><a target="_blank" href="{{ $data->linkedin }}"><i class="fab fa-linkedin"></i></a></li>
@endif

@if ($data->youtube)
<li class="pe-2 {{ $placeholder }}"><a target="_blank" href="{{ $data->youtube }}"><i class="fab fa-youtube"></i></a></li>
@endif
@if ($data->instagram)
<li class="{{ $placeholder }}"><a target="_blank" href="{{ $data->instagram }}"><i class="fab fa-instagram"></i></a></li>
@endif
</ul>
</div>
</div>
</div>
</div>
</div>


</div>
<div class="col-xl-5">
<div class="bg-light p-3 h-100" style="min-height: 266px;">
<div class="user_info pb-3 d-flex  position-relative position-relative border-left">
@if ($data->user_id != null)
@if (isset($data->user->photo) && !empty($data->user->photo))
<img src="{{ asset('assets/images/' . $data->user->photo) }}" class="flex-shrink-0 me-3 rounded-circle" width="50" height="50" alt="{{ $data->user->name }}">
@else
<img src="{{ asset('assets/front/img/t-7.png') }}" class="flex-shrink-0 me-3 rounded-circle" width="50" height="50" alt="default image">
@endif
<div>
<h2 class="m-0">{{ $data->user->name ?? '' }}</h2>
<p>{{ $data->user->address ?? '' }}</p>
<ul class="m-0 p-0 d-flex">
<li>
@if (isset($data->user->id))
{{ DB::table('recent_views_listings')->where('listing_owner_id', $data->user->id)->count() }}
@endif
<span> @lang('Views')</span>
</li>
@if (isset($data->user->listings))
<li>{{ count($data->user->listings) }}<span> @lang('Listings')</span></li>
@endif
@if (isset($data->user->id))
<li>{{ DB::table('followers')->where('user_id', $data->user->id)->count() }}<span> @lang('Followers')</span></li>
@endif
</ul>
</div>
@else
<img src="{{ asset('assets/images/' . getAdmin()->photo) }}" class="flex-shrink-0 me-3 rounded-circle" width="50" height="50" alt="{{ getAdmin()->name }}">
<div>
<h2 class="m-0">{{ getAdmin()->name }}</h2>
<p>{{ getAdmin()->address }}</p>
<ul class="m-0 p-0 d-flex">
<li>{{ DB::table('recent_views_listings')->where('listing_owner_id', 'NULL')->count() }}<span>
@lang('Views')</span></li>
<li>{{ DB::table('listings')->where('user_id', $data->user_id)->count() }}<span>
@lang('Listings')</span></li>
<li>{{ DB::table('followers')->where('user_id', 'NULL')->count() }}<span>
@lang('Followers')</span></li>
</ul>
</div>
@endif
</div>
<div class="">
<div class="agent-cnt-info d-flex mt-1">
<a href="javascript:void(0);" class="adv-btn ms-2 me-2 full-width add-follow" data-user="{{ auth()->id() ?? $data->user_id }}" data-owner="{{ $data->user_id }}" data-listing="{{ $data->id }}">@lang('Follow Now')</a>
@if (isset($data->user->username) && !empty($data->user->username))
<a href="{{ route('front.author.details', $data->user->username) }}" class="adv-btn ms-2 me-2 full-width theme-bg text-light">@lang('View Profile')</a>
@else
<a href="{{ route('front.author.details', 'admin') }}" class="adv-btn ms-2 me-2 full-width theme-bg text-light">@lang('View Profile')</a>
@endif
</div>
<div class="mt-3 px-2">
<button type="button" class="adv-btn full-width listing-message-btn" data-bs-toggle="modal" data-bs-target="#listingMessageModal">
<i class="fas fa-envelope me-2"></i>@lang('Send Message')
</button>
</div>
</div>
</div>
</div>
</div>
<div class="row mt-4">
<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
@if (isset($data->description) || $data->galleries->count() == 0)
<div class="bg-white rounded mb-4">
<div class="jbd-01 px-4 py-4 border rounded">
<div class="jbd-details">
<h2 class="ft-bold fs-lg">@lang('About the Business')</h2>
<div class="d-block mt-3 overflow-hidden">
@if ($data->galleries->count() == 0)
<div class="left_img">
<div class="mb-2">
<a href="{{ asset('assets/images/' . ($data->photo ?? 'default.png')) }}" class="mfp-gallery" title="{{ $data->name }}">
@if ($data->photo)
<div class="float-start me-3 mb-3">
<img src="{{ asset('assets/images/' . $data->photo) }}" class="img-fluid mx-auto" width="120" alt="{{ $data->name }}">
</div>
@else
<div class="float-start me-3 mb-3">
<img src="{{ asset('assets/images/default.png') }}" width="120" class="img-fluid mx-auto" alt="{{ $data->name }}">
</div>
@endif
</a>
</div>
</div>
@endif
@if ($data->description)
<p>
{!! nl2br($data->description) !!}
</p>
@endif
</div>
</div>
</div>
</div>
@endif
@if ($categoryType->slug = 'restaurants')
@if (isset($data->menus) && $data->menus->count() > 0)
<div class="bg-white rounded mb-4">
<div class="jbd-01 px-4 py-4 border rounded">
<div class="jbd-details mb-2">
<h2 class="ft-bold fs-lg">@lang('Food Menus')</h2>
<div class="d-block mt-3">
<div class="row g-3">
<!-- Single Menu -->
@foreach ($data->menus as $key => $menu)
@if (!empty($menu->menu_title))
<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
<div class="Rego-sng-menu">
<div class="Rego-sng-menu-thumb">
<img src="{{ asset('assets/images/' . ($menu->menu_photo ?? 'default.png')) }}" class="img-fluid" alt="{{ $menu->menu_title }}">
</div>
<div class="Rego-sng-menu-caption">
<h4 class="ft-medium fs-md mb-2">{{ $menu->menu_title }}</h4>
<div class="lkji-oiyt">
@if (!empty($menu->menu_price) && $menu->menu_price > 0) 
<h5 class="theme-cl ft-bold p-0 m-0">
{{ showSignAmount($menu->menu_price) }}
</h5>
@endif
</div>
</div>
</div>
</div>
@endif
@endforeach
</div>
</div>
</div>
</div>
</div>
@endif
@endif
@if ($categoryType->slug = 'hotels')
@if (isset($data->rooms) && count($data->rooms) > 0)
<div class="bg-white rounded mb-4">
<div class="jbd-01 px-4 py-4 border rounded">
<div class="jbd-details mb-2">
<h2 class="ft-bold fs-lg">@lang('Hotel Room')</h2>
<div class="d-block mt-3">
<div class="row g-3">
@foreach ($data->rooms as $key => $room)
<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
<div class="Rego-sng-menu">
<div class="Rego-sng-menu-thumb">
<img src="{{ asset('assets/images/' . ($room->room_photo ?? 'default.png')) }}" class="img-fluid" alt="{{ $room->room_name }}">
</div><div class="Rego-sng-menu-caption">
<h4 class="ft-medium fs-md">{{ $room->room_name }}</h4>
<p>{{ $room->room_description }}</p>
<div class="row">
@foreach (explode(',', $room->room_amenities) as $key => $amenity)
<div class="col-lg-4 col-md-4 col-xl-4">{{ $amenity }}</div>
@endforeach
</div>
<div class="lkji-oiyt mt-3">
@if (!empty($room->room_price) && $room->room_price > 0) 
<h2 class="theme-cl ft-bold">{{ $room->currency_symbol == 'tk' ? '৳' : '$' }} {{ $room->room_price }}</h2>
@endif
</div>
</div>
</div>
</div>
@endforeach
</div>
</div>
</div>
</div>
</div>
@endif
@endif
@if (
$categoryType->slug =
'beauty-spa' ||
($categoryType->slug =
'universities' || ($categoryType->slug = 'lawyers' || ($categoryType->slug = 'ac-repair-services'))))
@if (count($data->services) > 0)
<div class="bg-white rounded mb-4">
<div class="jbd-01 px-4 py-4 border rounded">
<div class="jbd-details mb-2">
<h2 class="ft-bold fs-lg">@lang('Products & Services')</h2>
<div class="d-block mt-3">
<div class="row g-3">
<!-- Single Menu -->
@foreach ($data->services as $key => $service)
<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
<div class="Rego-sng-menu">
<div class="Rego-sng-menu-thumb">
<img src="{{ asset('assets/images/' . ($service->service_photo ?? 'default.png')) }}" class="img-fluid" alt="{{ $service->service_name }}"></div>
<div class="Rego-sng-menu-caption">
<h4 class="ft-medium fs-md">{{ $service->service_name }}</h4>
<p>@lang('Availability '): {{ $service->service_from }} - {{ $service->service_to }}</p>
<p>@lang('Duration '): {{ $service->service_duration }} @lang('Minute')</p>
<div class="lkji-oiyt">
{{-- <span>@lang('Start From')</span> --}}
@if (!empty($service->service_price) && $service->service_price > 0) 
<h2 class="theme-cl ft-bold">{{ showSignAmount($service->service_price) }}</h2>
@endif
</div>
</div>
</div>
</div>
@endforeach
</div>
</div>
</div>
</div>
</div>
@endif
@endif
@if (isset($amenities) && is_array($amenities) && count($amenities) > 0)
<div class="bg-white rounded mb-4">
<div class="jbd-01 px-4 py-4 border rounded">
<div class="jbd-details">
<h2 class="ft-bold fs-lg">@lang('Amenities and More')</h2>
<div class="Rego-all-features-list mt-3">
<ul>
@foreach ($amenities as $amenity)
@php
$icon = DB::table('amenities')->where('name', $amenity)->value('icon');
@endphp
<li>
<div class="Rego-afl-pace">
<i class="{{ $icon }}"></i>
<span>{{ $amenity }}</span>
</div>
</li>
@endforeach
</ul>
</div>
</div>
</div>
</div>
@endif
@if (isset($data->faqs) && count($data->faqs) > 0)
<div class="d-block mb-2">
<div class="jbd-01 py-2">
<div class="jbd-details">
<h2 class="ft-bold fs-lg">@lang('Frequently Asked Questions')</h2>
<div class="d-block mt-3">
<div id="accordion2" class="accordion">
@foreach ($data->faqs as $key => $faq)
<div class="card">
<div class="card-header" id="h{{ $key }}">
<h2 class="mb-0">
<button class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#ord{{ $key }}" aria-expanded="true" aria-controls="ord{{ $key }}">
{{ $faq->faq_name }}
</button>
</h2>
</div>
<div id="ord{{ $key }}" class="collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="h{{ $key }}" data-parent="#accordion2">
<div class="card-body">{{ $faq->faq_details }}</div>
</div>
</div>
@endforeach
</div>
</div>
</div>
</div>
</div>
@endif
@if (isset($reviews) && $reviews->count() > 0)
<div class="bg-white rounded mb-4 w-100">
<div class="jbd-01 px-4 py-4 border rounded">
<div class="jbd-details mb-4">
<h2 class="ft-bold fs-lg m-0"> @lang('Reviews') <small class="text-warning">from google</small></h2>
<div class="reviews-comments-wrap">
@if (count($reviews) == 0)
<div class="col-12 text-center">
<p class="m-0">{{ __('No Review Found') }}</p>
</div>
@else
@foreach ($reviews as $key => $review)
<div class="reviews-comments-item w-100 d-sm-flex">
<div class="review-comments-avatar mb-3 mb-sm-0 me-0 me-sm-4">
<img src="{{ $review->user_photo ?? asset('assets/images/default-user.png') }}" width="100" class="img-fluid" alt="">
</div>
<div class="reviews-comments-item-text">
<h4> {{ $review->name }} </h4>
<span>{{ $review->reviewed_at }}</span>
<div class="listing-rating high">
@for ($i = 1; $i <= 5; $i++)
@if ($i <= $review->rate)
<i class="fas fa-star text-warning"></i>
@else
<i class="fas fa-star text-secondary"></i>
@endif
@endfor
</div>
<div class="clearfix"></div>
<p> {{ $review->message }}</p>
</div>
</div>
@endforeach
<div class="text-center">
<a href="https://www.google.com/search?q={{ $data->slug }}" target="_blank" class="btn btn-success px-4 py-2 rounded">Write Review</a>
<a href="https://www.google.com/search?q={{ $data->slug }}" target="_blank" class="btn btn-secondary px-4 py-2 rounded">View More</a>
</div>
@endif
</div>
</div>
</div>
</div>
@endif
</div>
<div class="col-xl-4 col-lg-4 col-sm-12">
<div class="bg-white rounded mb-4">
<div class="jbd-01 px-4 py-4 border rounded">
<div class="jbd-details mb-4">
<h2 class="ft-bold fs-lg">@lang('Location & Hours')</h2>
<div class="Rego-lot-wrap d-block">
<div class="list-map-lot">
@if(!empty($data->map_location) && isset($plan) && $plan->plan_type !== 'free')
{!! $data->map_location !!}
@endif
</div>
<table class="table mt-3 table-borderless">
<tbody>

<tr>
<th scope="row">@lang('Mon')</th>
@if ($schedules['mon_open'] != 'closed' && $schedules['mon_close'])
<td>{{ $schedules['mon_open'] }} - {{ $schedules['mon_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Tue')</td>
@if ($schedules['tue_open'] != 'closed' && $schedules['tue_close'])
<td>{{ $schedules['tue_open'] }} - {{ $schedules['tue_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Wed')</td>
@if ($schedules['wed_open'] != 'closed' && $schedules['wed_close'])
<td>{{ $schedules['wed_open'] }} - {{ $schedules['wed_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Thu')</td>
@if ($schedules['thu_open'] != 'closed' && $schedules['thu_close'])
<td>{{ $schedules['thu_open'] }} - {{ $schedules['thu_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Fri')</td>
@if ($schedules['fri_open'] != 'closed' && $schedules['fri_close'])
<td>{{ $schedules['fri_open'] }} - {{ $schedules['fri_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Sat')</td>
@if ($schedules['sat_open'] != 'closed' && $schedules['sat_close'])
<td>{{ $schedules['sat_open'] }} - {{ $schedules['sat_close'] }}
</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Sun')</td>
@if ($schedules['sun_open'] != 'closed' && $schedules['sun_close'])
<td>{{ $schedules['sun_open'] }} - {{ $schedules['sun_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
@if(!empty($data->listing_video) && isset($plan) && $plan->plan_type !== 'free')
<div class="bg-white rounded mb-4">
<div class="jbd-01 px-4 py-4 border rounded">
<div class="jbd-details mb-4">
<div id="getYouTubeVideoId"></div>
</div>
</div>
</div>
<script>
function getYouTubeVideoId(url) {
var regex =
/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
var match = url.match(regex);
return match ? match[1] : null;
}
var videoUrl = '{{ $data->listing_video }}';
var videoId = getYouTubeVideoId(videoUrl);
if (videoId) {
var iframe = document.createElement("iframe");
iframe.width = "100%";
iframe.height = "315";
iframe.src = "https://www.youtube.com/embed/" + videoId;
iframe.frameBorder = "0";
iframe.allowFullscreen = true;
document.getElementById("getYouTubeVideoId").appendChild(iframe);
}
</script>
@endif
</div>
{{--<div class="col-12">
        <div class="type_wise_list">
        <h2><strong>{{ $data->category->title }} in {{ $data->division->name }}</strong></h2>
        <div class="list">
        @php $areas = typeAreaMatchInListings($dhaka_areas, 'car'); @endphp
        @if (count($areas) > 0)
        <ul class="m-0 p-0 moreList">
        @foreach ($areas as $area)
        <li class="term-item"> <a href="{{ route('front.listing.page', ['type' => 'car', 'area' => $area]) }}"
        title="Best Rent a Car in {{ Str::ucfirst($area) }}"> Rent a car in {{ Str::ucfirst($area) }} </a> </li>
        @endforeach
        </ul>
        @endif
        </div>
    </div>
</div> --}}
<div class="col-12">
<div class="property_verify overflow-hidden">
<div class="v_business text-center">
<div class="v_gap"></div>
<div class="v_header">
<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-exclamation-circle">
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
<path d="M12 9v4" />
<path d="M12 16v.01" />
</svg>
<h3 class="text-white">Claim Your Business</h3>
</div>
<div class="text">
Are you owner of this business ? Claim your access to update the business information. If
you think the business information are wrong, feel free report against the business.
</div>
<div class="text pt-3">
@auth
<a href="#" rel="nofollow,noindex" class="blue_button text-white" data-bs-toggle="modal" data-bs-target="#cliamBusiness">Claim Your Business</a>
<a href="#" rel="nofollow,noindex" class="report_button" data-bs-toggle="modal" data-bs-target="#reportBusiness">Report This Business</a>
@else
<a href="{{ route('user.login') }}" class="blue_button text-white" >Claim Your Business</a>
<a href="{{ route('user.login') }}" class="report_button">Report This Business</a>
@endauth

</div>
</div>
</div>
</div>
</div>
</div>
</section>
<div class="modal fade" id="cliamBusiness" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cliamBusinessLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title fs-5" id="cliamBusinessLabel">Claim Your Business</div>
<button type="button" class="btn-close shadow-none opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-4">
<form action="{{ route('front.claim.business', $data->id) }}" method="post">
@csrf
<div class="mb-2">
<label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
<input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Enter your full name">
</div>
<div class="mb-2">
<label for="phone" class="form-label">Whatsapp Number <span class="text-danger">*</span></label>
<div class="input-group">
<span class="input-group-text">+880</span>
<input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required placeholder="Enter your whatsapp number">
</div>
</div>
<div class="mb-2">
<label for="email" class="form-label">Your Email <span class="text-danger">*</span></label>
<input type="email" name="email" id="email" class="form-control" value="{{ auth()->check() ? auth()->user()->email : old('email') }}" {{ auth()->check() ? 'readonly' : '' }} required placeholder="Enter your email address">
</div>
<div class="mb-2">
<label for="details" class="form-label">Details <span class="text-danger">*</span></label>
<textarea name="details" id="details" cols="30" rows="5" class="form-control" required placeholder="Write details">{{ old('details') }}</textarea>
</div>
<div class="mb-2">
<div class="form-group my-2">
<div id="captcha_box">
{!! \App\Http\Controllers\CaptchaController::input('captchafield') !!}
</div>
</div>
</div>
<div class="form-group text-center">
<button type="submit" class="btn btn-primary py-2 px-4 rounded">Send</button>
</div>
</form>
</div>
</div>
</div>
</div>

<div class="modal fade" id="reportBusiness" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reportBusinessLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<div class="modal-title fs-5" id="reportBusinessLabel">Report This Business</div>
<button type="button" class="btn-close shadow-none opacity-100" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-4">
<form action="{{ route('front.report.business', $data->id) }}" method="post">
@csrf
<div class="mb-2">
<label for="reason" class="form-label">Report Reason <span class="text-danger">*</span></label>
<select name="reason" id="reason" class="form-control" required>
<option value="" class="d-none">Choose Reason</option>
<option value="Item sold/unavailable">Item sold/unavailable</option>
<option value="Fraud">Fraud</option>
<option value="Duplicate">Duplicate</option>
<option value="Spam">Spam</option>
<option value="Wrong category">Wrong category</option>
<option value="Offensive">Offensive</option>
<option value="Other">Other</option>
</select>
</div>
<div class="mb-2">
<label for="email" class="form-label">Your Email <span class="text-danger">*</span></label>
<input type="email" name="email" id="email" class="form-control" value="{{ auth()->check() ? auth()->user()->email : old('email') }}" {{ auth()->check() ? 'readonly' : '' }} required placeholder="Your email">
</div>
<div class="mb-2">
<label for="reportmessage" class="form-label">Message <span class="text-danger">*</span></label>
<textarea name="message" id="reportmessage" cols="30" rows="5" class="form-control" required placeholder="Message"></textarea>
</div>
<div class="mb-2">
<div id="captcha-box">
{!! \App\Http\Controllers\MatchCaptchaController::input('captcha') !!}
</div>
</div>
<div class="form-group text-center">
<button type="submit" class="btn btn-primary py-2 px-4 rounded">Send</button>
</div>
</form>
</div>
</div>
</div>
</div>
@if (isset($relatedListings) && count($relatedListings) > 0)
<section class="space min">
<div class="container">
<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="theme-cl mb-0">@lang('Related Listing')</h6>
<h3 class="ft-bold">@lang('You May Also Like')</h3>
</div>
</div>
</div>
<!-- row -->
<div class="row justify-content-center">
@foreach ($relatedListings as $key => $relatedData)
<!-- Single -->
@if (isset($relatedData) && $relatedData !== null)
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
<div class="Rego-grid-wrap card placeholder-wave position-relative overflow-hidden">
<div class="Rego-grid-upper">
<div class="Rego-bookmark-btn">
<button type="button" class="wishList" data-listing="{{ $relatedData->id }}" data-user={{ auth()->id() ?? $relatedData->id }}>
<i class="lni lni-heart{{ $relatedData->userFavourite(auth()->id(), $relatedData->id) ? '-filled' : '' }} position-absolute"></i>
</button>
</div>
<div class="Rego-grid-thumb">
<a href="{{ route('front.listing.details', $relatedData->slug) }}" class="d-block text-center m-auto">
<img src="{{ asset('assets/images/' . ($relatedData->photo ?? 'default.png')) }}" class="img-fluid placeholder" alt="{{ $relatedData->name }}">
</a>
</div>
@if (isset($relatedData->average_rating) && $relatedData->average_rating != null)
<div class="Rego-rating overlay">
<div class="Rego-pr-average high">
{{ $relatedData->average_rating }}
</div>
<div class="Rego-aldeio">
<div class="Rego-rates">
@for ($i = 1; $i <= 5; $i++)
@if ($i <= floor($relatedData->average_rating))
<i class="fas fa-star text-warning"></i>
@elseif ($i == ceil($relatedData->average_rating) && $relatedData->average_rating - floor($relatedData->average_rating) > 0)
<i class="fas fa-star-half-alt text-warning"></i>
@else
<i class="fas fa-star text-secondary"></i>
@endif
@endfor
</div>
</div>
</div>
@endif
</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-caption px-3 py-2">
<div class="mb-0 ft-medium medium">
<a href="{{ route('front.listing.details', $relatedData->slug) }}" class="text-dark stretched-link fs-md placeholder" @if($relatedData->verified == 'yes') data-toggle="tooltip" title="Verified by BD Talika" @endif>{{ $relatedData->name }}</a> @if($relatedData->verified == 'yes') <span class="verified-badge"><i
class="fas fa-check-circle"></i></span> @endif
</div>
<div class="Rego-cates multi placeholder">
<a href="{{ route('front.listing', ['category' => $relatedData->category->slug]) }}" class="cats-2">{{ $relatedData->category->title }}</a>
</div>
<div class="listing_desc placeholder w-100 mt-2">  {!! Str::limit(strip_tags($relatedData->description), 70) !!} </div>
<div class="listing_location_info">
    <ul class="d-flex align-items-center p-0 mt-3">
@if (isset($relatedData->phone_number))
<li class="placeholder"><i class="fas fa-phone-alt"></i> <span class="d-block">Phone</span></li>
@endif
@if (isset($relatedData->email))
<li class="placeholder"> <i class="fas fa-envelope"></i> <span class="d-block">Address</span></li>
@endif
@if (isset($relatedData->map_location))
<li class="placeholder"> <i class="fas fa-map-marker-alt"></i> <span class="d-block">Map</span></li>
@endif
@if (isset($relatedData->website))
<li class="placeholder"><i class="fas fa-globe"></i> <span class="d-block">Website</span></li>
@endif
@if ($relatedData->galleries->count() != 0 || $relatedData->photo != null)
@php
$galleries    = $relatedData->galleries->count();
$total_photos = ($relatedData->photo != null ? 1 : 0) + $galleries;
@endphp
<li class="placeholder">
<i class="fas fa-image"></i> {{ $total_photos }}
<span class="d-block">Photos</span>
</li>
@endif
</ul>
</div>

</div>
</div>
</div>
</div>
@endif
@endforeach
</div>
<!-- row -->
</div>
</section>
@endif
<div class="modal fade listing-message-modal" id="listingMessageModal" tabindex="-1" aria-labelledby="listingMessageModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="listingMessageModalLabel">@lang('Send Message')</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('front.listing.enquiry') }}" method="post">
@csrf
<input type="hidden" name="listing_id" value="{{ $data->id }}">
<div class="modal-body">
@if ($errors->any() && session('open_listing_message_modal'))
<div class="alert alert-danger">
@foreach ($errors->all() as $error)
<div>{{ $error }}</div>
@endforeach
</div>
@endif
<div class="row">
<div class="col-md-6">
<div class="form-group">
<label class="mb-1">@lang('Name')</label>
<input type="text" name="name" value="{{ old('name') }}" class="form-control border rounded ps-3" placeholder="@lang('Enter your name')" required>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label class="mb-1">@lang('Email')</label>
<input type="email" name="email" value="{{ old('email') }}" class="form-control border rounded ps-3" placeholder="@lang('Enter your email')" required>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label class="mb-1">@lang('Phone')</label>
<input type="text" name="phone" value="{{ old('phone') }}" class="form-control border rounded ps-3" placeholder="@lang('Enter your phone number')" required>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label class="mb-1">@lang('Captcha')</label>
<div class="row g-2">
<div class="col-5">
<div class="listing-message-captcha">{{ $messageCaptchaFirst }} + {{ $messageCaptchaSecond }}</div>
</div>
<div class="col-7">
<input type="number" name="captcha_answer" value="{{ old('captcha_answer') }}" class="form-control border rounded ps-3" placeholder="@lang('Answer')" required>
</div>
</div>
</div>
</div>
<div class="col-12">
<div class="form-group">
<label class="mb-1">@lang('Message')</label>
<textarea name="details" rows="5" class="form-control border rounded ps-3" placeholder="@lang('Write your message')" required>{{ old('details') }}</textarea>
</div>
</div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-light border" data-bs-dismiss="modal">@lang('Close')</button>
<button type="submit" class="btn listing-message-btn px-4">@lang('Send Message')</button>
</div>
</form>
</div>
</div>
</div>
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
<script>
$(document).ready(function() {
$("#rateYo").rateYo({
starWidth: '30px',
fullStar: true,
mormalFill: 'yellow',
ratedFill: 'orange',
onSet: function(rating, rateYoInstance) {
$('#rating').val(rating);
}
});
});
</script>
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
})
$(document).on('click', '.track-listing-action', function() {
var token = $('meta[name="csrf-token"]').attr('content');
var formData = new FormData();
formData.append('_token', token);
formData.append('listing_id', $(this).data('listing'));
formData.append('event_type', $(this).data('event'));

if (navigator.sendBeacon) {
    navigator.sendBeacon('{{ route('front.listing.analytics.track') }}', formData);
    return;
}

fetch('{{ route('front.listing.analytics.track') }}', {
    method: 'POST',
    body: formData,
    keepalive: true
});
});
</script>
@if (session('open_listing_message_modal'))
<script>
document.addEventListener('DOMContentLoaded', function() {
var modal = new bootstrap.Modal(document.getElementById('listingMessageModal'));
modal.show();
});
</script>
@endif
<script>
{!! \App\Http\Controllers\MatchCaptchaController::getScript() !!}
{!! \App\Http\Controllers\CaptchaController::getScript() !!}
</script>
@endpush
