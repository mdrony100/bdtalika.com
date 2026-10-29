@extends('layouts.front')
@php
$area_query = $area_query ?? 'bangladesh';
$get_category_query = $get_category_query ?? null;
@endphp
@push('css')
<style>.disabled {color: #6c757d !important;pointer-events: none;cursor: default;}
.homepricing .home_hotkeybox ul {display: block;}
.homepricing .pricing .plan-features ul li{text-align: left;}
.homepricing .price-header .badge {padding: 3px 8px;font-size: 15px;}
.listing_location_info ul li{font-size:11px;}
.footertext p {
    font-size: 18px;
    color: #01132d;
    line-height: 30px;
    text-align: center;
}
</style>
@endpush
@section('content')
@if (in_array('Banner', $home_modules))
<div class="banner_section">
<div class="container pt-2 pb-2">
<h1>{!! $ps->hero_title ?: 'Bangladesh Business Directory <span>& Yellow Pages – Free Business Listing Website</span>' !!}</h1>
<p style="font-size:18px; line-height: 30px; color:#000000;">
BDTalika is one of the best business listing websites in Bangladesh where you can find thousands of companies, shops, hospitals, restaurants, and service providers. Our Bangladesh business directory list helps users discover businesses easily across Dhaka and all districts. You can also submit your company for free in our online directory website Bangladesh.
</p>
<form action="{{ route('front.listing', ['area' => 'bangladesh']) }}" method="get">
<div class="d-lg-flex align-items-center pb-2">
<div class="d-flex">
<div class="mb-2 mb-lg-0">
<button type="button" class="modal_btn d-flex align-items-center me-2 me-sm-0" data-bs-toggle="modal" data-bs-target="#location_modal">
<img src="{{ asset('assets/images/icon/location.svg') }}" class="pe-1" alt="Location">
<span>All of Bangladesh</span>
</button>
</div>
<div class="mb-2 mb-lg-0">
<button type="button" class="modal_btn d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#category_modal">
<img src="{{ asset('assets/images/icon/tag.svg') }}" class="pe-1" alt="Location">
<span>Select Category</span>
</button>
</div>
</div>
<div class="from-group">
<div class="input-group">
<input type="text" class="form-control" required name="keywords" placeholder="Search by business name" style="padding-right: 50px;">
<button type="submit" class="input-group-text"><img src="{{ asset('assets/images/icon/search.svg') }}" alt="Location"></button>
</div>
</div>
</div>
</form>
<div class="carousel_section mt-2 placeholder-wave">
<div class="row">
@if (isset($sliders) && $sliders->count() > 0)
<div class="col-xl-6 mb-3">
<div class="slider">
<div id="carouselExampleIndicators" class="carousel slide">
<div class="carousel-indicators">
@foreach ($sliders as $key => $nav)
<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-label="Slide {{ $key + 1 }}"></button>
@endforeach
</div>
<div class="carousel-inner rounded overflow-hidden">
@foreach ($sliders as $key => $item)
<div class="carousel-item slider_img {{ $key == 0 ? 'active' : '' }}">
<a href="{{ $item->link }}" class="sliderClick" data-id="{{ $item->id }}"><img src="{{ asset($item->image) }}" class="d-block rounded w-100" alt="{{ $item->link }}"></a>
</div>
@endforeach
</div>
<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
<span class="carousel-control-prev-icon" aria-hidden="true"></span>
</button>
<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
<span class="carousel-control-next-icon" aria-hidden="true"></span>
</button>
</div>
</div>
</div>
@endif
<div class="col-xl-6">
<div class="row gy-3 gy-md-0 placeholder-wave">
@if (isset($grid_categories) && $grid_categories->count() > 0)
@foreach ($grid_categories as $key => $item)
<div class="col-6 col-sm-4 col-md-3">
<div class="grid_card home_banner_li_effect" style="background-color: {{ $key == 0 ? '#0F7DD8' : ($key == 1 ? 'rgb(38, 84, 161)' : ($key == 2 ? 'rgb(103, 105, 208)' : ($key == 3 ? 'rgb(0, 172, 125)' : ''))) }}">
<h2 class="home_banner_text">{{ $item->title }}</h2>
<div class="home_banner_explore">
<h6 class="m-0 home_banner_exploretxt"><a href="{{ route('front.listing', ['area' => $area_query, 'category' => $item->slug]) }}">Explore</a></h6>
<i class="fa fa-angle-right"></i>
</div>
<div class="home_banner_img_btm">
<a href="{{ route('front.listing', ['area' => $area_query, 'category' => $item->slug]) }}"> <img src="{{ asset('assets/images/' . $item->photo) }}" alt="{{ $item->title }}"></a>
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
@endif
@if (in_array('Category', $home_modules))
<section class="pt-2 p-0">
<div class="container">
<!-- row -->
<div class="row align-items-center">
@if ($categories)
<div class="home_hotkeybox p-0 placeholder-wave">
<ul class="p-0">
@foreach ($categories as $key => $category)
<li>
<a href="{{ route('front.listing', ['area' => $area_query, 'category' => $category->slug]) }}" class="home_hotkeybox_li">
<div class="home_hotkeyimg"><img src="{{ asset('/assets/images/' . $category->photo) }}" alt="{{ $category->title }}"></div>
<div class="Rego-catg-caption">
<h2 class="fs-md mb-0 ft-medium m-catrio placeholder">{{ $category->title }} <span>({{ $category->listings->count() }})</span></h2>
</div>
</a>
</li>
@endforeach
</ul>
</div>
@endif
</div>
</div>
</section>
@endif

@if (in_array('Feature Directory', $home_modules))
<section class="space min pt-4">
<div class="container">
<!-- row -->
<div class="row gy-4 gy-lg-0">
<div class="col-lg-9">
@if (isset($latestListings) && $latestListings->count() > 0)
<div class="mb-4">
<div class="sec_title position-relative mb-3">
<h3 class="ft-bold">Latest and Updated Business Listings in Bangladesh</h3>
</div>
<div class="row gy-3">
@foreach ($latestListings as $key => $item)
<div class="col-sm-6 col-xl-4">
<div class="Rego-grid-wrap card placeholder-wave">
<div class="Rego-grid-upper">
<div class="Rego-pos ab-left reatured_tag">
<div class="Rego-status open me-2">Updated <small>{{ date('d M, y', strtotime($item->updated_at)) }}</small></div>
</div>
<div class="Rego-bookmark-btn">
<button type="button" class="wishList" data-listing="{{ $item->id }}" data-user={{ auth()->id() ?? $item->id }}>
<i class="lni lni-heart{{ $item->userFavourite(auth()->id(), $item->id) ? '-filled' : '' }} position-absolute"></i>
</button>
</div>
<div class="Rego-grid-thumb">
<a href="{{ route('front.listing.details', $item->slug) }}" class="d-block text-center m-auto">
<img src="{{ asset('assets/images/' . $item->photo) }}" class="img-fluid placeholder" alt="{{ Str::limit($item->name, 30) }}">
</a>
</div>
@if (count($item->reviews) > 0)
<div class="Rego-rating overlay">
<div class="Rego-pr-average high">{{ $item->directoryRatting($item->id) }}</div>
<div class="Rego-aldeio">
<div class="Rego-rates">
@php
$rate = ceil($item->directoryRatting($item->id),);
@endphp
@for ($i = 1; $i <= $rate; $i++)
<i class="fas fa-star active"></i>
@endfor
@for ($i = 1; $i <= 5 - $rate; $i++)
<i class="fas fa-star"></i>
@endfor
</div>
<div class="Rego-all-review"><span>{{ count($item->reviews) }} @lang('Reviews')</span></div>
</div>
</div>
@endif

</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-caption px-3 py-2">
<div class="mb-0 ft-medium medium mb-2">
<a href="{{ route('front.listing.details', $item->slug) }}" @if($item->verified == 'yes') data-toggle="tooltip" title="Verified by BD Talika" @endif class="text-dark fs-md stretched-link placeholder">
{{ Str::limit($item->name, 30) }}  @if($item->verified == 'yes') <span class="verified-badge"><i class="fas fa-check-circle"></i></span>@endif</a>
</div>
<div class="Rego-cates multi placeholder">
<a href="{{ route('front.listing', ['category' => $item->category->slug]) }}" class="cats-2">{{ $item->category->title }}</a>
</div>
<div class="listing_desc placeholder w-100 mt-2">{!! Str::limit(strip_tags($item->description), 70) !!}</div>

<div class="listing_location_info">
    <ul class="d-flex align-items-center p-0 mt-3">
@if (isset($item->phone_number))
<li class="placeholder"><i class="fas fa-phone-alt"></i> <span class="d-block">Phone</span></li>
@endif
@if (isset($item->email))
<li class="placeholder"> <i class="fas fa-envelope"></i> <span class="d-block">Address</span></li>
@endif
@if (isset($item->map_location))
<li class="placeholder"> <i class="fas fa-map-marker-alt"></i> <span class="d-block">Map</span></li>
@endif
@if (isset($item->website))
<li class="placeholder"><i class="fas fa-globe"></i> <span class="d-block">Website</span></li>
@endif
@if ($item->galleries->count() != 0 || $item->photo != null)
@php
$galleries    = $item->galleries->count();
$total_photos = ($item->photo != null ? 1 : 0) + $galleries;
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
@endforeach
</div>
</div>
@endif

@if (count($features_products) > 0)
<div class="sec_title position-relative mb-3">
<h3 class="ft-bold">{{ $ps->directory_title }}</h3>
</div>
<div class="row gy-3">
@foreach ($features_products as $key => $product)
<div class="col-sm-6 col-xl-4">
<div class="Rego-grid-wrap card placeholder-wave">
<div class="Rego-grid-upper">
@if ($product->is_feature)
<div class="Rego-pos ab-left reatured_tag"><div class="Rego-status open me-2">@lang('Featured')</div></div>
@endif
<div class="Rego-bookmark-btn">
<button type="button" class="wishList" data-listing="{{ $product->id }}" data-user={{ auth()->id() ?? $product->id }}>
<i class="lni lni-heart{{ $product->userFavourite(auth()->id(), $product->id) ? '-filled' : '' }} position-absolute"></i>
</button>
</div>
<div class="Rego-grid-thumb">
<a href="{{ route('front.listing.details', $product->slug) }}" class="d-block text-center m-auto">
<img src="{{ asset('assets/images/' . $product->photo) }}" class="img-fluid placeholder" alt="{{ Str::limit($product->name, 30) }}">
</a>
</div>
@if (count($product->reviews) > 0)
<div class="Rego-rating overlay">
<div class="Rego-pr-average high">{{ $product->directoryRatting($product->id) }}</div>
<div class="Rego-aldeio">
<div class="Rego-rates">
@php
$rate = ceil($product->directoryRatting($product->id),);
@endphp
@for ($i = 1; $i <= $rate; $i++)
<i class="fas fa-star active"></i>
@endfor
@for ($i = 1; $i <= 5 - $rate; $i++)
<i class="fas fa-star"></i>
@endfor
</div>
<div class="Rego-all-review"><span>{{ count($product->reviews) }} @lang('Reviews')</span></div>
</div>
</div>
@endif
</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-caption px-3 py-2">
<div class="mb-0 ft-medium medium mb-2">
<a href="{{ route('front.listing.details', $product->slug) }}" @if($product->verified == 'yes') data-toggle="tooltip" title="Verified by BD Talika" @endif class="text-dark fs-md stretched-link placeholder">
{{ Str::limit($product->name, 30) }} @if($product->verified == 'yes') <span class="verified-badge"><i class="fas fa-check-circle"></i></span> @endif</a>
</div>
<div class="Rego-cates multi placeholder">
<a href="{{ route('front.listing', ['category' => $product->category->slug]) }}" class="cats-2">{{ $product->category->title }}</a>
</div>
<div class="listing_desc placeholder w-100 mt-2"> {!! Str::limit(strip_tags($product->description), 70) !!}</div>

{{-- 
<div class="Rego-middle-caption mt-3">
@if ($product->real_address)
<div class="Rego-location placeholder">
<i class="fas fa-map-marker-alt"></i>
<span>{{ Str::limit($product->real_address, 30) }}</span>
</div>
@endif
@if ($product->phone_number)
<div class="Rego-call placeholder">
<i class="fas fa-phone-alt"></i>
<span>{{ Str::limit($product->phone_number, 28) }}</span>
</div>
@endif
</div>
--}}

<div class="listing_location_info">
    <ul class="d-flex align-items-center p-0 mt-3">
@if (isset($product->phone_number))
<li class="placeholder"><i class="fas fa-phone-alt"></i> <span class="d-block">Phone</span></li>
@endif
@if (isset($product->email))
<li class="placeholder"> <i class="fas fa-envelope"></i> <span class="d-block">Address</span></li>
@endif
@if (isset($product->map_location))
<li class="placeholder"> <i class="fas fa-map-marker-alt"></i> <span class="d-block">Map</span></li>
@endif
@if (isset($product->website))
<li class="placeholder"><i class="fas fa-globe"></i> <span class="d-block">Website</span></li>
@endif
@if ($product->galleries->count() != 0 || $product->photo != null)
@php
$galleries    = $product->galleries->count();
$total_photos = ($product->photo != null ? 1 : 0) + $galleries;
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
@endforeach
</div>
@endif
</div>
@if (isset($recent_added) && $recent_added->count() > 0)
<div class="col-lg-3">
<div class="sec_title position-relative mb-3"><h3 class="ft-bold">Recently Added</h3></div>
<div class="row">
@foreach ($recent_added as $product)
<div class="recent_ads mb-3 rounded col-sm-6 col-lg-12 placeholder-wave">
<div class="d-flex position-relative box-shadow1">
<div class="me-2 recent_img">
<img src="{{ asset('assets/images/' . ($product->photo ?? 'default.png')) }}" class="flex-shrink-0" width="75" height="75" alt="{{ Str::limit($product->name, 28) }}">
</div>
<div class="media-body">
<div class="media-heading placeholder">{{ Str::limit($product->name, 28) }}</div>
@if (isset($product->real_address))
<div class="location placeholder"><i class="fas fa-map-marker-alt me-1"></i>{{ Str::limit($product->real_address, 50) }}</div>
@endif
<a href="{{ route('front.listing.details', $product->slug) }}" class="stretched-link"></a></div></div></div>
@endforeach
</div>
</div>
@endif
</div>
</div>
</section>
@endif
{{--<section class="pt-2 p-0 mt-3 mb-3 homepricing">
<div class="container">
<!-- row -->
<div class="row align-items-center">
<div class="home_hotkeybox p-0 placeholder-wave">
   <div class="sec_title position-relative mb-3">
	<h3 class="ft-bold">Simple Pricing. Real Business Growth.</h3>
	<p style="font-size:18px; line-height: 30px; color:#000000;">You can cancel your subscription at any time</p>
   </div>
   <div class="row gy-4 gy-xl-0 d-flex justify-content-center">
    @forelse ($plans as $plan)
        @php
            if (isset($plan->plan_type) && $plan->plan_type == 'free' || $plan->plan_type == 'lifetime') {
                $plan->post_duration = 'Lifetime';
            } else{
                $plan->post_duration =  $plan->post_duration.' days';
            }
            $freePlan = isset($plan->plan_type) && $plan->plan_type == 'free';
            $ad_id  = session('ad_id') ;
        @endphp 
        <div class="col-md-4">
            <div class="pricing pe-lg-5">
                <div class="price-header">
                    <div class="price-name"> {{ $plan->title }}</div>
                    <div class="price mb-3"> @if($plan->regular_price >0)<i style="font-size:18px; color:#ff0000;"><del>{{ $plan->regular_price }} BDT</del></i> @endif{{ $plan->price }} BDT <br>
                 @if(isset($plan->plan_type) && $plan->plan_type == 'monthly')
                 <span class="badge bg-info">Monthly Fee</span></div>
                 @elseif(isset($plan->plan_type) && $plan->plan_type == 'yearly')
                 <span class="badge bg-info">Yearly Fee</span></div>
                 @elseif(isset($plan->plan_type) && $plan->plan_type == 'lifetime')
                 <span class="badge bg-info">One Time Fee</span></div>
                 @else
                  <span class="badge bg-info">Lifetime Free</span></div>
                  <span class="text-danger"> <i>phone, whatsapp, website, map, video won't see visitors.</i></span>
                 @endif
                </div>

                <div class="plan-features mb-3">
                    <ul class="m-0 p-0">
                        <li>
                            Published for {{ $plan->post_duration }}
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Your business will be published for {{ $plan->post_duration }} on our website.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li
                            class="{{ isset($plan->google_review) && $plan->google_review == '1' ? '' : 'close' }}">
                            Import Google Reviews
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="If you have Google My Business reviews, we will import them and display them on your business details page.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li
                            class="{{ isset($plan->google_map) && $plan->google_map == '1' ? '' : 'close' }}">
                            Google Map
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="This feature will allow you to embed google business map in your business details page.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li
                            class="{{ isset($plan->video_url) && $plan->video_url == '1' ? '' : 'close' }}">
                            Video Url
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="This feature will allow you to add any video link of your business.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li
                            class="{{ isset($plan->public_profile) && $plan->public_profile == '1' ? '' : 'close' }}">
                            Public Branding Page
                            <button type="button" class="bg-transparent border-0"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="A personalized public page to show all your business listings.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li class="{{ isset($plan->features) && $plan->features == '1' ? '' : 'close' }}">
                            Featured Badge
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="You will get a special green badge that will increase your customer trust.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li class="{{ isset($plan->listing) && $plan->listing == '1' ? '' : 'close' }}">
                            Appear on the Top Position of the Listing Page
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Most visitors come to the listing page. It’s a chance to get more customers.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li class="{{ isset($plan->home) && $plan->home == '1' ? '' : 'close' }}">
                            Appear on the Home Page
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="80% of visitors land on the home page. It’s a chance to get more customers.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li class="{{ isset($plan->follow) && $plan->follow == '1' ? '' : 'close' }}">
                            Do-Follow Attribute
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Get credit from Google for SEO.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li
                            class="{{ isset($plan->analytics) && $plan->analytics == '1' ? '' : 'close' }}">
                            Analytics
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="In the user dashboard, you will get an analytics menu to track customers.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li class="{{ isset($plan->indexing) && $plan->indexing == '1' ? '' : 'close' }}">
                            Urgent Indexing
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="We will request in Google Search Console for indexing.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li
                            class="{{ isset($plan->notification) && $plan->notification == '1' ? '' : 'close' }}">
                            Monthly Visitors Notification
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="You will get monthly analytics reports in your email.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="btn_primary">
                    <a href="{{route('user.login')}}" class="btn btn-sm btn-primary shadow-none w-100" >
                        Start Listing
                    </a> 
                </div>
            </div>
        </div>
    @empty
    @endforelse
</div>
</div>
</div>
</div>
</section>--}}
<section class="about_section space min pt-3">
<div class="container">
<div class="about_content mt-4">
@if (isset($about_info))<div class="mb-4">{!! $about_info !!}</div> @endif
</div>
@if (in_array('Category', $home_modules))
<div class="popular_categories">
<h4><strong>Browse Bangladesh Business Directory Categories</strong></h4>
<div class="list">
<ul class="m-0 p-0">
@foreach ($popular_categories as $key => $cat)
<li><a class="{{ $cat->listings->count() == '0' ? 'disabled' : '' }}" href="{{ route('front.listing', ['area' => $area_query, 'category' => $cat->slug]) }}">
{{ $cat->title }} ({{ $cat->listings->count() }})</a></li>
@endforeach
<li><a href="{{ route('front.category.directory') }}" class="d-flex align-items-center"><span class="pe-1">View All Categories</span><i class="fa fa-angle-right"></i></a></li>
</ul>
</div>
</div>
@endif
@if (isset($listing_divisions) && $listing_divisions->count() > 0)
<div class="popular_cities mt-2 mb-2">
<h4><strong>Listing by Divisions</strong></h4>
<div class="list">
<ul class="m-0 p-0">
@foreach ($listing_divisions as $key => $division)
<li><a class="{{ $division->listings->count() == '0' ? 'disabled' : '' }}" href="{{ route('front.listing', ['area' => $division->slug, 'category' => $get_category_query]) }}">{{ $division->name }} ({{ $division->listings->count() }})</a></li>
@endforeach
</ul>
</div>
</div>
@endif
@if (isset($listing_cities) && $listing_cities->count() > 0)
<div class="popular_cities mt-2 mb-2">
<h4><strong>Listing by Cities</strong></h4>
<div class="list">
<ul class="m-0 p-0">
@foreach ($listing_cities as $key => $city)
<li><a class="{{ $city->ads->count() == '0' ? 'disabled' : '' }}" href="{{ route('front.listing', ['area' => $city->slug, 'category' => $get_category_query]) }}"> {{ $city->name }} ({{ $city->ads->count() }})</a></li>
@endforeach
<li><a href="{{ route('front.cities.directory') }}" class="d-flex align-items-center"><span class="pe-1">View All Cities</span><i class="fa fa-angle-right"></i></a></li>
</ul>
</div>
</div>
@endif
@if (isset($listing_areas) && $listing_areas->count() > 0)
<div class="popular_cities mt-2 mb-4">
<h4><strong>Listing by Area</strong></h4>
<div class="list">
<ul class="m-0 p-0">
@foreach ($listing_areas as $key => $area)
<li><a class="{{ $area->listings->count() == '0' ? 'disabled' : '' }}" href="{{ route('front.listing', ['area' => $area->slug, 'category' => $get_category_query]) }}">{{ $area->title }} ({{ $area->listings->count() }})</a></li>
@endforeach
<a href="{{ route('front.cities.directory') }}" class="d-flex align-items-center"><span class="pe-1">View All Areas</span><i class="fa fa-angle-right"></i></a>
</ul>
</div>
</div>
@endif
<div class="row gy-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6">
<div class="col">
<div class="type_wise_list">
<h2><strong>Restaurants in Dhaka</strong></h2>
<div class="list">
@php
$areas = typeAreaMatchInListings($dhaka_areas, 'restaurant');
@endphp
@if (count($areas) > 0)
<ul class="m-0 p-0 moreList">
@foreach ($areas as $area)
<li class="term-item"><a href="{{ route('front.listing.page', ['type' => 'restaurant', 'area' => $area]) }}" title="Popular Restaurants in {{ Str::ucfirst($area) }}">Restaurants in {{ Str::ucfirst($area) }}</a></li>
@endforeach
</ul>
@endif
</div>
</div>
</div>
<div class="col">
<div class="type_wise_list">
<h2><strong>Hotels in Dhaka</strong></h2>
<div class="list">
@php $areas = typeAreaMatchInListings($dhaka_areas, 'hotel'); @endphp
@if (count($areas) > 0)
<ul class="m-0 p-0 moreList">
@foreach ($areas as $area)
<li class="term-item"><a href="{{ route('front.listing.page', ['type' => 'hotel', 'area' => $area]) }}"
title="Top Hotels in {{ Str::ucfirst($area) }}">Hotels in {{ Str::ucfirst($area) }}</a></li>
@endforeach
</ul>
@endif
</div>
</div>
</div>
<div class="col">
<div class="type_wise_list">
<h2><strong>Parlour & Spa in Dhaka</strong></h2>
<div class="list">
@php $areas = typeAreaMatchInListings($dhaka_areas, 'beauty'); @endphp
@if (count($areas) > 0)
<ul class="m-0 p-0 moreList">
@foreach ($areas as $area)
<li class="term-item"><a href="{{ route('front.listing.page', ['type' => 'beauty', 'area' => $area]) }}"
title="Best Parlour & Spa in {{ Str::ucfirst($area) }}">Parlour & Spa in {{ Str::ucfirst($area) }}</a></li>
@endforeach
</ul>
@endif
</div>
</div>
</div>
<div class="col">
<div class="type_wise_list">
<h2><strong>Real Estates in Dhaka</strong></h2>
<div class="list">
@php $areas = typeAreaMatchInListings($dhaka_areas, 'real_estate'); @endphp
@if (count($areas) > 0)
<ul class="m-0 p-0 moreList">
@foreach ($areas as $area)
<li class="term-item"><a href="{{ route('front.listing.page', ['type' => 'real_estate', 'area' => $area]) }}"
title="Top Real Estates in {{ Str::ucfirst($area) }}">Real Estates in {{ Str::ucfirst($area) }}</a></li>
@endforeach
</ul>
@endif
</div>
</div>
</div>
<div class="col">
<div class="type_wise_list">
<h2><strong>Hospitals in Dhaka</strong></h2>
<div class="list">
@php $areas = typeAreaMatchInListings($dhaka_areas, 'hospitals'); @endphp
@if (count($areas) > 0)
<ul class="m-0 p-0 moreList">
@foreach ($areas as $area)
<li class="term-item"><a href="{{ route('front.listing.page', ['type' => 'hospitals', 'area' => $area]) }}"
title="Top Hospitals in {{ Str::ucfirst($area) }}">Hospitals in {{ Str::ucfirst($area) }}</a></li>
@endforeach
</ul>
@endif
</div>
</div>
</div>
<div class="col">
<div class="type_wise_list">
<h2><strong>Rent a car in Dhaka</strong></h2>
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
</div>
</div>
</div>
</section>
@if (in_array('Partners', $home_modules))
<section class="pt-0">
<div class="container">
<div class="row justify-content-center">
<div class="col-xl-5 col-lg-7 col-md-9 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="text-muted mb-0">{{ $ps->partner_title }}</h6>
<h2 class="ft-bold">{{ $ps->partner_subtitle }}</h2>
</div>
</div>
</div>
<div class="row justify-content-center">
@foreach ($partners as $key => $data)
<div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
<div class="empl-thumb text-center px-3 py-4">
<img src="{{ asset('assets/images/' . $data->photo) }}" class="img-fluid mx-auto" alt="{{ asset('assets/images/' . $data->photo) }}">
</div>
</div>
@endforeach
</div>
</div>
</section>
@endif
@if (in_array('Blogs', $home_modules))
<section class="space min pt-0">
<div class="container">
<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-4">
<h6 class="theme-cl mb-0">{{ $ps->blog_title }}</h6>
<h2 class="ft-bold">{{ $ps->blog_subtitle }}</h2>
</div>
</div>
</div>
<div class="row justify-content-center">
@if (count($blogs) > 0)
@foreach ($blogs as $key => $blog)
<!-- Single Item -->
<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
<div class="blg_grid_box">
<div class="blg_grid_thumb">
<a href="{{ route('blog.details', $blog->slug) }}"><img src="{{ asset('assets/images/' . $blog->photo) }}" class="img-fluid" alt="{{ $blog->title }}"></a>
</div>
<div class="blg_grid_caption">
<div class="blg_tag">
<span>{{ $blog->category->name }}</span>
</div>
<div class="blg_title"><h3><a href="{{ route('blog.details', $blog->slug) }}">{{ $blog->title }}</a></h3></div>
<div class="blg_desc">{{ $blog->details }}</div></div>
<div class="crs_grid_foot">
<div class="crs_flex d-flex align-items-center justify-content-between br-top px-3 py-2">
<div class="crs_fl_first">
<div class="crs_tutor">
<div class="crs_tutor_thumb">
<a href="javascript:void(0);"><img src="{{ asset('assets/images/' . getAdmin()->photo) }}" class="img-fluid circle" width="35" alt="{{ asset('assets/images/' . getAdmin()->photo) }}"></a>
</div>
</div>
</div>
<div class="crs_fl_last">
<div class="foot_list_info">
<ul>
<li><div class="elsio_ic"><i class="fa fa-eye text-success"></i></div><div class="elsio_tx">{{ $blog->views }} @lang('Views')</div></li>
<li><div class="elsio_ic"><i class="fa fa-clock text-warning"></i></div><div class="elsio_tx">{{ $blog->created_at->format('d M Y') }}</div></li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
@endforeach
@endif
</div>
</div>
</section>
@endif
@if (in_array('Packages', $home_modules))
<section class="space min gray">
<div class="container">
<div class="row justify-content-center">
<div class="col-xl-5 col-lg-7 col-md-9 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="theme-cl mb-0">{{ $ps->plan_title }}</h6>
<h2 class="ft-bold">{{ $ps->plan_subtitle }}</h2>
</div>
</div>
</div>
<div class="row">
@if (count($plans) > 0)
@foreach ($plans as $key => $data)
<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
<div class="Rego-price-wrap">
<div class="Rego-author-header">
<div class="Rego-price-currency">
<h3><span class="Rego-new-price" style="color:{{ $data->price_color }}">{{ globalCurrency()->sign }}<del>{{ showAmount($data->price) }}</del></span>
@if ($data->prev_price > 0)
<span class="Rego-old-price">{{ globalCurrency()->sign }}<del>{{ showAmount($data->prev_price) }}</del></span>
@endif
</h3>
</div>
<div class="Rego-price-title">
<div class="Rego-price-tlt">
<h4>{{ $data->title }}</h4>
</div>
</div>
<div class="Rego-price-subtitle">{{ $data->subtitle }}</div></div>
<div class="Rego-price-body">
<ul class="price__features">
@if ($data->attribute != null)
@foreach (json_decode($data->attribute) as $key => $attribute)
<li><i class="fa fa-angle-right"></i>{{ $attribute }}</li>
@endforeach
@endif
</ul>
</div>
<div class="Rego-price-bottom">
<a class="Rego-price-btn" href="{{ route('user.package.subscription', $data->id) }}"><i class="fas fa-shopping-basket"></i> @lang('Purchase Now')</a>
</div>
</div>
</div>
@endforeach
@endif
</div>
</div>
</section>
@endif

@if (in_array('Apps', $home_modules))
<section>
<div class="container">
<div class="row align-items-center justify-content-between">
<div class="col-lg-6 col-md-12 col-sm-12 content-column">
<div class="content_block_2 pr-3 py-4">
<div class="content-box">
<div class="sec-title light">
<p class="theme-cl px-3 py-1 rounded bg-light-danger d-inline-flex">
{{ $ps->download_title }}</p>
<h2 class="ft-bold">{{ $ps->download_subtitle }}</h2>
</div>
<div class="text mb-3">
<p>{{ $ps->download_text }}</p>
</div>
<div class="btn-box clearfix mt-5">
<a href="{{ $ps->app_store_link }}" class="download-btn play-store me-1 d-inline-flex"><img src="{{ asset('assets/front/img/ios.png') }}" width="200" alt="{{ $ps->app_store_link }}"></a>
<a href="{{ $ps->google_play_link }}" class="download-btn play-store ms-2 mb-1 d-inline-flex"><img src="{{ asset('assets/front/img/and.png') }}" width="200" alt="{{ $ps->google_play_link }}"></a>
</div>
</div>
</div>
</div>
<div class="col-lg-5 col-md-12 col-sm-12 image-column">
<div class="image-box">
<figure class="image"><img src="{{ asset('assets/images/' . $ps->download_photo) }}" class="img-fluid" alt="{{ asset('assets/images/' . $ps->download_photo) }}"></figure>
</div>
</div>
</div>
</div>
</section> 
@endif
@if (isset($blogs) && $blogs->count() > 0) 
<div class="blog_section">
<div class="container">
<div class="mb-4">
    <h2 class="title m-0">Popular News, Tips, Events in Bangladesh</h2>
</div>
<div class="row justify-content-center">

<!-- Single Item -->
@foreach($blogs as $blog)
<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
<div class="blg_grid_box shadow-sm border-0">
<div class="blg_grid_thumb position-relative">
<a href="{{ route('blog.details', $blog->slug) }}"> 
     <img src="{{asset('assets/images/'.$blog->photo)}}" class="img-fluid" alt="{{ $blog->title }}">
</a>
</div>
<div class="blg_grid_caption">
<div class="blg_tag mb-2 placeholder">
    <a href="{{ route('front.blogcategory', $blog->category->slug) }}" class="text-danger">
        {{ $blog->category->name }}
    </a>
</div>
<div class="blg_title">
<a href="{{ route('blog.details', $blog->slug) }}">
    <h3 class="placeholder">{{ $blog->title }}</h3>
</a>
</div>
<div class="blg_desc">
<p class="placeholder"> 
{{ Str::limit(strip_tags($blog->details), 250) }}
</p>
</div>
<div class="readmore mt-3">
    <a class="btn btn-default" href="{{ route('blog.details', $blog->slug) }}">Read More..</a>
</div>
</div>
</div>
</div>
@endforeach

</div>
</div>
<div class="text-center pt-5 pb-3"><a target="_blank" href="{{ route('front.blog') }}" class="btn btn-primary btn-sm rounded-3">See More News, Tips, Events </a></div>
</div>
@endif
<div class="content_section pb-4">
<div class="container">
<div class="mb-4">
<h2>Popular Business Listing Website to Enlist Companies</h2>
<p>
At <a class="color_link" target="_blank" href="{{ route('front.index') }}">bdtalika.com</a>, we recognize the crucial need for reliable and up-to-date information right at your fingertips. Our mission is to deliver a comprehensive directory that spans a wide range of categories, ensuring you can swiftly and efficiently find exactly what you need. Whether you're searching for local businesses, popular restaurants, essential services, or the latest events, bdtalika.com has everything covered. You can find businesses by category or location. Additionally, you can search for your desired business through keyword search. If you own a business, you can <a class="color_link" target="_blank" href="{{ route('user.register') }}">enlist</a> it on our platform to get more visitors and views.
</p>
</div>
<div class="mb-4">
<h2>Famous Restaurants in Bangladesh</h2>
<p>
Bangladesh is a food lover's paradise, offering a rich tapestry of flavors and cuisines. At bdtalika.com, we have meticulously curated a list of <a class="color_link" target="_blank" href="https://bdtalika.com/listing/bangladesh/restaurants">popular restaurants</a> nationwide to cater to every palate.Whether you're in the mood for traditional Bangladeshi dishes, Indian spices, Chinese delicacies, or Western favorites, our restaurant listings will guide you to the best dining spots in your city.
You can explore fine dining experiences in Dhaka at places like Pan Pacific Sonargaon and Westin Dhaka, or enjoy a cozy meal at Holey Artisan Bakery. For those who love street food, the bustling streets of Old Dhaka offer a variety of delicious snacks like fuchka, chotpoti, and jhalmuri. Each restaurant <a class="color_link" target="_blank" href="https://bdtalika.com/listing/dhaka/restaurants">listing</a>  on bdtalika.com includes detailed information such as menu options, price ranges, customer reviews, and contact details, making it easier to make an informed choice.
</p>
</div>
<div class="mb-4">
<h2>Popular Schools, Colleges & Universities in Bangladesh</h2>
<p>
Education is a cornerstone of development, and choosing the right institution is crucial for students and parents. bdtalika.com provides a comprehensive directory of schools, colleges, and universities across Bangladesh, ensuring you have access to the best educational options available.
Our listings cover many educational institutions, from renowned schools like <a class="color_link" target="_blank" href="https://bdtalika.com/details/scholastica-school">Scholastica</a> and <a class="color_link" target="_blank" href="https://bdtalika.com/details/sunbeams-school">Sunbeams</a> to top-tier universities such as <a class="color_link" target="_blank" href="https://bdtalika.com/details/dhaka-university">Dhaka University</a>, <a class="color_link" target="_blank" href="https://bdtalika.com/details/north-south-university">North South University</a>, and <a class="color_link" target="_blank" href="https://bdtalika.com/details/brac-university">BRAC University</a>. Each entry includes information about the institution's history, courses offered, admission requirements, and facilities, helping you make an informed decision about your educational journey
</p>
</div>
<div class="mb-4">
<h2>Best Business Listings and Yellow Pages in Bangladesh</h2>
<p>
Finding reliable business services is essential for both individuals and companies. Our business <a class="color_link" target="_blank" href="{{ route('front.listing') }}">listings</a> and Yellow Pages provide comprehensive information on various businesses across Bangladesh.Whether you're looking for a reputable contractor, a trustworthy <a class="color_link" target="_blank" href="https://bdtalika.com/listing/bangladesh/lawyers">legal advisor</a>, or a reliable <a class="color_link" target="_blank" href="https://bdtalika.com/listing/bangladesh/hospitals-clinics">healthcare</a> provider, bdtalika.com has got you covered.Our directory includes detailed profiles of businesses, complete with contact information, services offered, operating hours, and customer reviews. This ensures that you can make informed decisions when selecting a service provider.Additionally, businesses can benefit from increased visibility by <a class="color_link" target="_blank"  href="https://bdtalika.com/user/dashboard">listing</a> on bdtalika.com, reaching a broader audience and attracting more customers.
</p>
</div>
<div class="mb-4">
<h2>Famous Lawyers in Dhaka, Bangladesh</h2>
<p>
Legal matters require the expertise of experienced professionals, and finding the right <a class="color_link" target="_blank" href="https://bdtalika.com/listing/bangladesh/lawyers">lawyer</a> is crucial. At bdtalika.com, we have compiled a directory of <a href="https://bdtalika.com/listing/dhaka/lawyers" class="color_link" target="_blank">famous lawyers in Dhaka</a> to help you find the legal support you need.Our listings include detailed profiles of lawyers specializing in various fields, such as family law, criminal defense, corporate law, and property law. Each profile provides information about the lawyer's qualifications, experience, areas of expertise, and contact details. Whether you need assistance drafting a will, handling a business dispute, or seeking legal advice, our directory makes it easy to find a qualified lawyer in Dhaka.
</p>
</div>
<div class="mb-4">
<h2>Top Hotels in Dhaka</h2>
<p>
Planning a stay in Dhaka? Finding suitable accommodation is essential for a comfortable and enjoyable trip. bdtalika.com offers a curated list of <a href="https://bdtalika.com/listing/dhaka/hotels" class="color_link" target="_blank">top hotels in Dhaka</a> to suit every budget and preference. From luxury hotels like The Westin Dhaka and Radisson Blu Dhaka Water Garden to budget-friendly options like Hotel 71 and FARS Hotel & Resorts, our <a href="https://bdtalika.com/listing/bangladesh/hotels" class="color_link" target="_blank">listings</a> provide detailed information about each hotel, including room rates, amenities, customer reviews, and booking options. Whether traveling for business or leisure, bdtalika.com helps you find the perfect place to stay in Dhaka.
</p>
</div>
<div class="mb-4">
<h2>Best Shopping Centers in Dhaka</h2>
<p>
<a href="https://bdtalika.com/listing/dhaka/shopping-center" class="color_link" target="_blank">Shopping in Dhaka</a> offers a unique experience, with a mix of modern malls and traditional markets. bdtalika.com features a guide to the best shopping centers in Dhaka, ensuring you find everything you need in one place. <a href="https://bdtalika.com/listing/bangladesh/shopping-center" class="color_link" target="_blank">Popular shopping</a> destinations like <a href="https://bdtalika.com/details/bashundhara-city" class="color_link" target="_blank">Bashundhara City</a>, <a href="https://bdtalika.com/details/jamuna-future-park" class="color_link" target="_blank">Jamuna Future Park</a>, and Gulshan Pink City offer various products, from fashion and electronics to groceries and home goods. For a more traditional shopping experience, explore the bustling markets of New Market and Karwan Bazar. Our directory provides information about each shopping center, including store listings, opening hours, and special promotions.
</p>
</div>
<div class="mb-4">
<h2>Popular Gyms in Dhaka</h2>
<p>
Maintaining a healthy lifestyle is essential; finding the right gym is vital to achieving your fitness goals. bdtalika.com offers a comprehensive directory of <a href="https://bdtalika.com/listing/dhaka/gyms" class="color_link" target="_blank">popular gym centres in Dhaka</a> to help you stay fit and active. From high-end fitness centers like Gold's Gym and Fitness Plus to community gyms like Oxygen Gym and Planet Fitness, our listings provide detailed information about each gym, including membership options, facilities, class schedules, and trainer profiles. Whether you're a fitness enthusiast or just starting your journey, bdtalika.com helps you find the best gym in Dhaka to meet your needs.
</p>
</div>
<div class="mb-4">
<h2>Best Dentists in Dhaka</h2>
<p>
Oral health is essential, and finding a reliable dentist is crucial for maintaining it. bdtalika.com features a directory of Dhaka's best dentists, providing top-notch dental care and services. Our <a href="https://bdtalika.com/listing/bangladesh/dentists" class="color_link" target="_blank">listings</a> include detailed profiles of dentists specializing in various fields, such as orthodontics, periodontics, and cosmetic dentistry. Each profile provides information about the dentist's qualifications, experience, services, and contact details. Whether you need a routine check-up, orthodontic treatment, or cosmetic dental procedures, <strong>bdtalika.com</strong> makes it easy to find a qualified dentist in Dhaka.
</p>
</div>
<div class="mb-4">
<h2>Why Choose bdtalika.com?</h2>
<p>
At <a href="http://bdtalika.com/" class="color_link" target="_blank">bdtalika.com</a>, we prioritize user satisfaction by providing accurate and comprehensive information. Our user-friendly interface, detailed <a href="https://bdtalika.com/listing" class="color_link" target="_blank">listings</a>, and regular updates ensure you have access to the best resources in Bangladesh. Whether a resident or a visitor, bdtalika.com is your first choice for all your needs. Explore bdtalika.com today and discover the convenience of having all the information you need in one place. For any inquiries or support, please get in touch with us through our <a href="https://bdtalika.com/contact" class="color_link" target="_blank">contact page</a>.
</p>
</div>
</div>
</div>
<div class="container mb-5 mt-5">
<div class="row d-flex justify-content-center">
<div class="col-md-8 col-lg-6 col-xl-5">
<div class="business_card bg-white border rounded shadow-sm position-relative">
<div class="row d-flex align-items-center">
<div class="col-sm-7">
<div class="position-relative z-50">
<h4>Have a business?</h4>
<p>Start selling with us in</p>
<h5>Just 40 secs for free!</h5>
@auth<a href="{{ route('user.register') }}" class="btn btn-primary rounded btn-sm">Start Selling</a>
@else<a href="{{ route('user.register') }}" class="btn btn-primary rounded btn-sm">Register Now</a>@endauth
</div>
</div>
<div class="col-5">
<img src="{{ asset('assets/images/icon/business.png') }}" class="img-fluid" alt="">
</div>
</div>
</div>
</div>
</div>
</div>
@if (in_array('CTAs', $home_modules))
@include('partials.front.cta')
@endif
{{-- location and category modal --}}
@include('partials.front.modal.location_modal')
@include('partials.front.modal.category_modal')
@endsection
@push('js')
<script>
'use strict';
// add to wishlist
$.ajaxSetup({
headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
$(document).on('click', '.wishList', function() {
let $this = $(this);
let listingId = $(this).data('listing');
let userId = $(this).data('user');
if (userId == '') {window.location.href = mainurl + '/user/login'}
$.ajax({method: "POST",url: mainurl + '/listing/wishlist',data: {listing_id: listingId,user_id: userId},
success: function(data) {
if (data.success) {$this.children().prop('class', '');$this.children().prop('class', 'lni lni-heart-filled  position-absolute');toastr.success(data.success);} else {$this.children().prop('class', '');$this.children().prop('class', 'lni lni-heart  position-absolute');toastr.error(data.error);}}});})

// footer more content show / hide 
$('.moreList').each(function() {
var LiN = $(this).find('li').length;if (LiN > 10) {$('li', this).eq(9).nextAll().hide().addClass('toggleable');$(this).append('<li class="more">More...</li>');}});
$('.moreList').on('click', '.more', function() {if ($(this).hasClass('less')) {$(this).text('More...').removeClass('less');} else {$(this).text('Less...').addClass('less');}$(this).siblings('li.toggleable').slideToggle();});
</script>
<script>
// on click modal scroll to top
$(document).ready(function() {
$('.modal_list .nav-link').on('click', function() {
var modalContent = $('.modal-body');modalContent.animate({scrollTop: 0}, 300);});});
// on click city hide division tab
$(document).ready(function() {
$(document).on('click', '.cityClick', function() {$('.divisionTab').addClass('d-none');$('.cityTab').removeClass('d-none');});
$(document).on('click', '.divisionClick', function() {console.log('ok');$('.cityTab').addClass('d-none');$('.divisionTab').removeClass('d-none');});});
// mobile device modal how hide
$(document).on('click', '.listItem .nav-link', function() {
if ($(window).width() <= 991) {$('.listItem').hide();$('#go_back').show();$('.cityTab').show();$('.divisionTab').show();}$('.subItem').show();});
$(document).on('click', '#go_back', function() {if ($(window).width() <= 991) {$('.listItem').show();$('#go_back').hide();$('.cityTab').hide();$('.divisionTab').hide();}$('.subItem').hide();$('.subItem').empty();
});
// more city data in modal
$('.moreCity').each(function() {
var LiN = $(this).find('li').length;
if (LiN > 10) {$('li', this).eq(9).nextAll().hide().addClass('toggleable');$(this).append('<li class="more ps-2">More...</li>');}});
$('.moreCity').on('click', '.more', function() {if ($(this).hasClass('less')) {$(this).text('More...').removeClass('less');} else {$(this).text('Less...').addClass('less');}$(this).siblings('li.toggleable').slideToggle();});
</script>
<script>
// slider impression & click tracking
function trackSliderEvent(sliderId, eventType) {
if (!sliderId) return;
var formData = new FormData();
formData.append('_token', '{{ csrf_token() }}');
formData.append('slider_id', sliderId);
formData.append('event_type', eventType);

if (navigator.sendBeacon) {
navigator.sendBeacon('{{ route('front.slider.analytics.track') }}', formData);
return;
}

fetch('{{ route('front.slider.analytics.track') }}', {
method: 'POST',
body: formData,
keepalive: true
}).catch(function() {});
}

function trackSlideImpressionByIndex(index) {
var $item = $('#carouselExampleIndicators .carousel-item').eq(index).find('.sliderClick');
if ($item.length) {
trackSliderEvent($item.data('id'), 'impression');
}
}

// Impressions: only the first visible slide and manually navigated slides count.
// Auto rotation does NOT log impressions.
document.addEventListener('DOMContentLoaded', function() {
var $active = $('#carouselExampleIndicators .carousel-item.active .sliderClick');
if ($active.length) {
trackSliderEvent($active.data('id'), 'impression');
}

$('#carouselExampleIndicators .carousel-control-prev').on('click', function() {
var $items = $('#carouselExampleIndicators .carousel-item');
var idx = $items.index($items.filter('.active'));
var total = $items.length;
if (total) trackSlideImpressionByIndex(((idx - 1) % total + total) % total);
});

$('#carouselExampleIndicators .carousel-control-next').on('click', function() {
var $items = $('#carouselExampleIndicators .carousel-item');
var idx = $items.index($items.filter('.active'));
var total = $items.length;
if (total) trackSlideImpressionByIndex((idx + 1) % total);
});

$('#carouselExampleIndicators .carousel-indicators button').on('click', function() {
trackSlideImpressionByIndex($(this).data('bs-slide-to'));
});
});

$(document).on('click', '.sliderClick', function() {
trackSliderEvent($(this).data('id'), 'click');
});
</script>
@endpush
