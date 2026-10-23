@extends('layouts.front')

@push('css')

@endpush

@section('content')
@if (in_array('Banner', $home_modules))

<div class="home-banner margin-bottom-0" style="background:#f41b3b url({{ asset('assets/images/'.$ps->hero_photo) }}) no-repeat;" data-overlay="5">
<div class="container">
<div class="row justify-content-center">
<div class="col-xl-11 col-lg-12 col-md-12 col-sm-12 col-12">

<div class="banner_caption text-center mb-5">
<h1 class="banner_title ft-bold mb-1">{{ $ps->hero_title }}</h1>
<p class="fs-md ft-medium">{{ $ps->hero_subtitle }}</p>
</div>

<form class="main-search-wrap fl-wrap" action="{{ route('front.listing') }}" method="GET">
<div class="main-search-item">
<span class="search-tag"><i class="lni lni-keyboard"></i></span>
<input type="text" class="form-control radius" name="title" placeholder="@lang('Nail salons, plumbers, takeout...')">
</div>
<div class="main-search-item">
<span class="search-tag"><i class="lni lni-map-marker"></i></span>
<input type="text" class="form-control" name="real_address" placeholder="@lang('San Francisco, CA')">
</div>
<div class="main-search-item">
<span class="search-tag"><i class="lni lni-briefcase"></i></span>
<select class="form-control" name="category[]">
<option value="">@lang('Choose Categories')</option>
@foreach ($categories as $key => $category)
<option value="{{ $category->slug }}">{{ $category->title }}</option>
@endforeach
</select>
</div>
<div class="main-search-button">
<button class="btn full-width theme-bg text-white" type="submit">@lang('Search')</button>
</div>
</form>

</div>
</div>
</div>
</div>

@endif

@if (in_array('Partners', $home_modules))
<!-- ======================= Our Partner Start ============================ -->
<section class="gray">
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
@foreach ($partners as $key=>$data)
<div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
<div class="empl-thumb text-center px-3 py-4">
<img src="{{ asset('assets/images/'.$data->photo)}}" class="img-fluid mx-auto" alt="">
</div>
</div>
@endforeach
</div>

</div>
</section>
<!-- ======================= Our Partner Start ============================ -->
@endif

@if (in_array('Feature Directory', $home_modules))
<section class="space min">
<div class="container">

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="theme-cl mb-0">{{ $ps->directory_title }}</h6>
<h2 class="ft-bold">{{ $ps->directory_subtitle }}</h2>
</div>
</div>
</div>

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
<ul class="nav nav-tabs small-tab mb-3" id="myTab" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="restaurants-tab" data-bs-toggle="tab" data-bs-target="#restaurants" type="button" role="tab" aria-controls="restaurants" aria-selected="true">@lang('Restaurant')</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="hotels-tab" data-bs-toggle="tab" data-bs-target="#hotels" type="button" role="tab" aria-controls="hotels" aria-selected="false">@lang('Hotel')</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="beauty-tab" data-bs-toggle="tab" data-bs-target="#beauty" type="button" role="tab" aria-controls="beauty" aria-selected="false">@lang('Beauty')</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="real-tab" data-bs-toggle="tab" data-bs-target="#real" type="button" role="tab" aria-controls="real_estate" aria-selected="true">@lang('Real estate')</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="doctor-tab" data-bs-toggle="tab" data-bs-target="#doctor" type="button" role="tab" aria-controls="doctor" aria-selected="false">@lang('Doctor')</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="car-tab" data-bs-toggle="tab" data-bs-target="#car" type="button" role="tab" aria-controls="car" aria-selected="false">@lang('Car')</button>
</li>
</ul>
</div>
</div>

<div class="row justify-content-center">

<div class="tab-content" id="myTabContent">

<!-- Restaurant -->
<div class="tab-pane fade show active" id="restaurants" role="tabpanel" aria-labelledby="restaurants-tab">
<div class="row justify-content-center">
@if (count($resturant_listings)>0)
@foreach ($resturant_listings as $key => $restaurant)
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
<div class="Rego-grid-wrap card">
<div class="Rego-grid-upper">
<div class="Rego-bookmark-btn">
<button type="button" class="wishList" data-listing="{{ $restaurant->id }}" data-user={{ auth()->id() }}>
<i class="lni lni-heart{{ $restaurant->userFavourite(auth()->id(),$restaurant->id) ? '-filled' : ''}} position-absolute"></i>
</button>
</div>
<div class="Rego-pos ab-left">
<div class="Rego-status close me-2">{{ $restaurant->openClose($restaurant->id) }}</div>
</div>
<div class="Rego-grid-thumb">
<a href="{{ route('front.listing.details',$restaurant->slug) }}" class="d-block text-center m-auto">
<img src="{{ asset('assets/images/'.$restaurant->photo)}}" class="img-fluid" alt="">
</a>
</div>
@if (count($restaurant->reviews)>0)
<div class="Rego-rating overlay">
<div class="Rego-pr-average high">{{ $restaurant->directoryRatting($restaurant->id) }}</div>
<div class="Rego-aldeio">
<div class="Rego-rates">
@php
$rate = ceil($restaurant->directoryRatting($restaurant->id));
@endphp

@for($i=1; $i<=$rate; $i++)
<i class="fas fa-star active"></i>
@endfor

@for($i=1; $i<=(5-$rate); $i++)
<i class="fas fa-star"></i>
@endfor
</div>
<div class="Rego-all-review"><span>{{ count($restaurant->reviews) }} @lang('Reviews')</span></div>
</div>
</div>
@endif
</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-caption px-3 py-2">
<div class="Rego-author">
@if ($restaurant->user_id == NULL && $restaurant->admin_id == NULL)
<a href="{{ route('front.author.details','admin') }}">
<img src="{{ asset('assets/images/'.getAdmin()->photo)}}" class="img-fluid circle" alt="">
</a>
@else
<a href="{{ route('front.author.details',$restaurant->user->username) }}">
<img src="{{ asset('assets/images/'.$restaurant->user->photo)}}" class="img-fluid circle" alt="">
</a>
@endif
</div>

<div class="Rego-cates multi">
<a href="{{ route('front.listing',['category' => $restaurant->category->slug]) }}" class="cats-2">{{ $restaurant->category->title }}</a>
</div>

<h4 class="mb-0 ft-medium medium">
<a href="{{ route('front.listing.details',$restaurant->slug) }}" class="text-dark fs-md">
{{ $restaurant->name }}
<span class="verified-badge">
<i class="fas fa-check-circle"></i>
</span>
</a>
</h4>
<div class="Rego-middle-caption mt-3">
<div class="Rego-location"><i class="fas fa-map-marker-alt"></i>{{ $restaurant->real_address }}</div>
<div class="Rego-call"><i class="fas fa-phone-alt"></i>{{ $restaurant->phone_number }}</div>
</div>
</div>
</div>
</div>
</div>
@endforeach
@endif

</div>
</div>
<!-- /Places -->

<!-- Hotels -->
<div class="tab-pane fade" id="hotels" role="tabpanel" aria-labelledby="hotels-tab">
<div class="row justify-content-center">
@if (count($hotel_listings)>0)
@foreach ($hotel_listings as $key => $hotel)
<!-- Single -->
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
<div class="Rego-grid-wrap card">
<div class="Rego-grid-upper">
<div class="Rego-bookmark-btn">
<button type="button" class="wishList" data-listing="{{ $hotel->id }}" data-user={{ auth()->id() }}>
<i class="lni lni-heart{{ $hotel->userFavourite(auth()->id(),$hotel->id) ? '-filled' : ''}} position-absolute"></i>
</button>
</div>
@if ($hotel->is_featured)
<div class="Rego-pos ab-left">
<div class="Rego-featured-tag">@lang('Featured')</div>
</div>
@endif
<div class="Rego-grid-thumb">
<a href="{{ route('front.listing.details',$hotel->slug) }}" class="d-block text-center m-auto"><img src="{{ asset('assets/images/'.$hotel->photo)}}" class="img-fluid" alt=""></a>
</div>
@if (count($hotel->reviews)>0)
<div class="Rego-rating overlay">
<div class="Rego-pr-average high">{{ $hotel->directoryRatting($hotel->id) }}</div>
<div class="Rego-aldeio">
<div class="Rego-rates">
@php
$rate = ceil($hotel->directoryRatting($hotel->id));
@endphp

@for($i=1; $i<=$rate; $i++)
<i class="fas fa-star active"></i>
@endfor

@for($i=1; $i<=(5-$rate); $i++)
<i class="fas fa-star"></i>
@endfor
</div>
<div class="Rego-all-review"><span>{{ count($hotel->reviews)}} @lang('Reviews')</span></div>
</div>
</div>
@endif
</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-caption px-3 py-2">
<h4 class="mb-0 ft-medium medium mb-0"><a href="{{ route('front.listing.details',$hotel->slug) }}" class="text-dark fs-md">{{ $hotel->name }}<span class="verified-badge"><i class="fas fa-check-circle"></i></span></a></h4>

<div class="Rego-middle-caption mt-3">
<div class="Rego-location"><i class="fas fa-map-marker-alt"></i>{{ $hotel->real_address }}</div>
<div class="Rego-call"><i class="fas fa-phone-alt"></i>{{ $hotel->phone_number }}</div>
</div>
</div>

</div>
</div>
</div>
@endforeach
@endif

</div>
</div>
<!-- /Hotels -->

<!-- Beauty -->
<div class="tab-pane fade" id="beauty" role="tabpanel" aria-labelledby="beauty-tab">
<div class="row justify-content-center">
@if (count($beauty_listings)>0)
@foreach ($beauty_listings as $key => $beauty)
<!-- Single -->
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
<div class="Rego-grid-wrap card">
<div class="Rego-grid-upper">
<div class="Rego-bookmark-btn">
<button type="button" class="wishList" data-listing="{{ $beauty->id }}" data-user={{ auth()->id() }}>
<i class="lni lni-heart{{ $beauty->userFavourite(auth()->id(),$beauty->id) ? '-filled' : ''}} position-absolute"></i>
</button>
</div>
<div class="Rego-grid-thumb">
<a href="{{ route('front.listing.details',$beauty->slug) }}" class="d-block text-center m-auto"><img src="{{ asset('assets/images/'.$beauty->photo)}}" class="img-fluid" alt=""></a>
</div>
<div class="Rego-overlay-caps">
<h4 class="mb-0 ft-medium medium"><a href="{{ route('front.listing.details',$beauty->slug) }}" class="text-white fs-md">{{ $beauty->name }}</a></h4>
<div class="Rego-location text-white"><i class="fas fa-map-marker-alt me-1"></i>{{ $beauty->real_address }}</div>
</div>
</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-grid-footer py-3 px-3">
<div class="Rego-ft-first">
<div class="Rego-catsin">
{{ $beauty->category->title }}
</div>
</div>
<div class="Rego-ft-last">
<span class="small">

</span>
</div>
</div>
</div>
</div>
</div>
@endforeach
@endif

</div>
</div>
<!-- /Beauty -->

<!-- Real -->
<div class="tab-pane fade" id="real" role="tabpanel" aria-labelledby="real-tab">
<div class="row justify-content-center">
@if (count($real_listings)>0)
@foreach ($real_listings as $key => $real)
<!-- Single -->
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
<div class="Rego-grid-wrap card">
<div class="Rego-grid-upper">
<div class="Rego-bookmark-btn">
<button type="button" class="wishList" data-listing="{{ $real->id }}" data-user={{ auth()->id() }}>
<i class="lni lni-heart{{ $real->userFavourite(auth()->id(),$real->id) ? '-filled' : ''}} position-absolute"></i>
</button>
</div>
<div class="Rego-pos ab-left">
<div class="Rego-status open me-2">{{ $real->r_property_type }}</div>
@if ($real->is_featured)
<div class="Rego-featured-tag">@lang('Featured')</div>
@endif
</div>
<div class="Rego-grid-thumb">
<a href="{{ route('front.listing.details',$real->slug) }}" class="d-block text-center m-auto"><img src="{{ asset('assets/images/'.$real->photo)}}" class="img-fluid" alt=""></a>
</div>
<div class="Rego-rating overlay">
<div class="Rego-prt-price"><span>{{ showSignAmount($real->r_price) }}</span></div>
</div>
</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-caption px-3 py-2">
<div class="Rego-author">
@if ($real->user_id == NULL && $real->admin_id == NULL)
<a href="{{ route('front.author.details','admin') }}">
<img src="{{ asset('assets/images/'.getAdmin()->photo)}}" class="img-fluid circle" alt="">
</a>
@else
<a href="{{ route('front.author.details',$real->user->username) }}">
<img src="{{ asset('assets/images/'.$real->user->photo)}}" class="img-fluid circle" alt="">
</a>
@endif
</div>
<div class="Rego-cates multi">
@if ($real->reviews->count()>0)
<span class="Rego-apr-rates">
<i class="fas fa-star"></i>
{{ $real->reviews->count() }}
</span>
@endif
<a href="{{ route('front.listing',['category' => $category->slug]) }}" class="cats-1">{{ $real->category->title }}</a>
</div>
<h4 class="mb-0 ft-medium medium"><a href="{{ route('front.listing.details',$real->slug) }}" class="text-dark fs-md">{{ $real->real_address }}<span class="verified-badge"><i class="fas fa-check-circle"></i></span></a></h4>
<div class="Rego-middle-caption mt-2">
<div class="Rego-options-list">
<ul class="no-list-style">
<li><i class="fas fa-bed"></i><span>{{ $real->r_bed }} @lang('Beds')</span></li>
<li><i class="fas fa-bath"></i><span>{{ $real->r_baths }} @lang('Baths')</span></li>
<li><i class="fas fa-clone"></i><span>{{ $real->r_square }} @lang('sqft')</span></li>
<li><i class="fas fa-calendar"></i><span>{{ $real->created_at->diffForHumans() }} @lang('Days Ago')</span></li>
</ul>
</div>
</div>
</div>
<div class="Rego-grid-footer py-2 px-3">
<div class="Rego-ft-first">
<div class="Rego-location"><i class="fas fa-map-marker-alt me-1 theme-cl"></i>San Francisco, USA</div>
</div>
<div class="Rego-ft-last">
<div class="Rego-inline">
<div class="Rego-bookmark-btn"><button type="button"><i class="lni lni-envelope position-absolute"></i></button></div>
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
<!-- /Real -->

<!-- Doctor -->
<div class="tab-pane fade" id="doctor" role="tabpanel" aria-labelledby="doctor-tab">
<div class="row justify-content-center">
@if (count($doctors_listings)>0)
@foreach ($doctors_listings as $key=>$doctor)
<!-- Single -->
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
<div class="doctor-grid-view">
<div class="doctor-grid-thumb">
<a href="{{ route('front.listing.details',$doctor->slug) }}"><img src="{{ asset('assets/images/'.$doctor->photo)}}" class="img-fluid" alt=""></a>
</div>

<div class="doctor-grid-caption">
<div class="doc-title-wrap"><h4 class="doc-title verified"><a href="{{ route('front.listing.details',$doctor->slug) }}">{{ $doctor->name }}</a></h4></div>
<span class="doc-designation">{{ $doctor->designation }}</span>

<div class="doc-inner-wrap">
@if (count($doctor->reviews)>0)
<div class="doc-ratting-boxes">
<ul class="doc ratting-view">
@php
$rate = ceil($restaurant->directoryRatting($doctor->id));
@endphp

@for($i=1; $i<=$rate; $i++)
<li><i class="fas fa-star filled"></i></li>
@endfor

@for($i=1; $i<=(5-$rate); $i++)
<li><i class="fas fa-star"></i></li>
@endfor

</ul>
<span class="doctor-review-list">({{ count($doctor->reviews) }} @lang('Reviews'))</span>
</div>
@endif
<div class="doc-location"><i class="fas fa-map-marker-alt me-1 theme-cl"></i>{{ $doctor->real_address }}</div>
</div>
</div>

</div>
</div>
@endforeach
@endif
</div>
</div>
<!-- /Doctor -->

<!-- car -->
<div class="tab-pane fade" id="car" role="tabpanel" aria-labelledby="car-tab">
<div class="row justify-content-center">
@if (count($cars_listings)>0)
@foreach ($cars_listings as $key => $car)
<!-- Single -->
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
<div class="Rego-grid-wrap card">
<div class="Rego-grid-upper">
<div class="Rego-bookmark-btn">
<button type="button" class="wishList" data-listing="{{ $car->id }}" data-user={{ auth()->id() }}>
<i class="lni lni-heart{{ $car->userFavourite(auth()->id(),$car->id) ? '-filled' : ''}} position-absolute"></i>
</button>
</div>
<div class="Rego-pos ab-left">
<div class="Rego-featured-tag">Featured</div>
</div>
<div class="Rego-grid-thumb">
<a href="{{ route('front.listing.details',$car->slug) }}" class="d-block text-center m-auto"><img src="{{ asset('assets/images/'.$car->photo)}}" class="img-fluid" alt=""></a>
</div>
<div class="Rego-rating overlay">
<div class="Rego-prt-price"><span>{{ showSignAmount($car->car_price) }}</span></div>
</div>
</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-caption px-3 py-2">
<div class="Rego-cates multi"><a href="{{ route('front.listing',['category' => $category->slug]) }}" class="cats-1">{{ $car->category->title }}</a></div>
<h4 class="mb-0 ft-medium medium"><a href="{{ route('front.listing.details',$car->slug) }}" class="text-dark fs-md">{{ $car->name }}</a></h4>
<div class="Rego-middle-caption mt-2">
<div class="Rego-options-list">
<ul class="no-list-style">
<li><i class="fas fa-gas-pump"></i><span>{{ $car->car_fuel_type }}</span></li>
<li><i class="far fa-calendar-alt"></i><span>{{ $car->car_manufacture_year }}</span></li>
<li><i class="fas fa-blender-phone"></i><span>{{ $car->car_mileage }}</span></li>
<li><i class="fab fa-accusoft"></i><span>{{ $car->car_engine_capacity }}</span></li>
</ul>
</div>
</div>
</div>
<div class="Rego-grid-footer py-2 px-3">
<div class="Rego-ft-first">
<div class="Rego-location"><i class="fas fa-map-marker-alt me-1 theme-cl"></i>{{ $car->real_address }}</div>
</div>
</div>
</div>
</div>
</div>
@endforeach
@endif

</div>
</div>
<!-- /car -->

</div>

</div>

</div>
</section>
@endif

@if (in_array('Category', $home_modules))

<section class="space min gray">
<div class="container">

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="mb-0 theme-cl">{{ $ps->category_title }}</h6>
<h2 class="ft-bold">{{ $ps->category_subtitle }}</h2>
</div>
</div>
</div>

<!-- row -->
<div class="row align-items-center">
@if ($categories)
@foreach ($categories as $key => $category)
<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
<div class="cats-wrap text-center">
<a href="{{ route('front.listing',['category' => [$category->slug]]) }}" class="Rego-catg-wrap">
<div class="Rego-catg-city">{{ $category->listings->where('status',1)->count()}} @lang('Listings')</div>
<div class="Rego-catg-icon"><i class="{{ $category->icon }}"></i></div>
<div class="Rego-catg-caption">
<h4 class="fs-md mb-0 ft-medium m-catrio">{{ $category->title }}</h4>
</div>
</a>
</div>
</div>
@endforeach
@endif
</div>
<!-- row -->

</div>
</section>

@endif

@if (in_array('Space', $home_modules))
<!-- ============================ Boo Your Room ============================= -->
<section class="space min">
<div class="container">

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="mb-0 theme-cl">Book Your Space</h6>
<h2 class="ft-bold">Book Your Room in California</h2>
</div>
</div>
</div>

<div class="row justify-content-center">
@foreach ($room_listings as $key=>$data)
<!-- Single -->
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
<div class="Rego-grid-wrap card">
<div class="Rego-grid-upper">
<div class="Rego-bookmark-btn">
<button type="button" class="wishList" data-listing="{{ $data->id }}" data-user={{ auth()->id() }}>
<i class="lni lni-heart{{ $data->userFavourite(auth()->id(),$data->id) ? '-filled' : ''}} position-absolute"></i>
</button>
</div>

@if ($data->is_feature)
<div class="Rego-pos ab-left">
<div class="Rego-featured-tag">@lang('Featured')</div>
</div>
@endif

<div class="Rego-grid-thumb">
<a href="{{ route('front.listing.details',$data->slug) }}" class="d-block text-center m-auto">
<img src="{{ asset('assets/images/'.$data->photo)}}" class="img-fluid" alt="">
</a>
</div>

@if (count($data->reviews)>0)
<div class="Rego-rating overlay">
<div class="Rego-pr-average high">{{ $data->directoryRatting($data->id) }}</div>
<div class="Rego-aldeio">
<div class="Rego-rates">
@php
$rate = ceil($data->directoryRatting($data->id));
@endphp

@for($i=1; $i<=$rate; $i++)
<i class="fas fa-star active"></i>
@endfor

@for($i=1; $i<=(5-$rate); $i++)
<i class="fas fa-star"></i>
@endfor
</div>
<div class="Rego-all-review"><span>{{ count($data->reviews) }} @lang('Reviews')</span></div>
</div>
</div>
@endif
</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-caption px-3 py-2">

<h4 class="mb-0 ft-medium medium mb-0">
<a href="{{ route('front.listing.details',$data->slug) }}" class="text-dark fs-md">
{{ $data->name }}
<span class="verified-badge">
<i class="fas fa-check-circle"></i>
</span>
</a>
</h4>

<div class="Rego-distance">{{ $data->distance }}</div>

<div class="Rego-middle-caption mt-3">
@if ($data->amenity_icons != NULL)
<div class="Rego-facilities-wrap Rego-flx mb-0">
<div class="Rego-facility-list">
<ul class="no-list-style">
@foreach (json_decode($data->amenity_icons,true) as $key=>$icon)
<li><i class="{{ $icon }}"></i></li>
@endforeach
</ul>
</div>
</div>
@endif
<div class="Rego-booking-button">
<a href="{{ route('front.listing.details',$data->slug) }}" class="Rego-btn-book">@lang('Book Now')</a>
</div>
</div>
</div>
</div>
</div>
</div>
@endforeach

</div>

</div>
</section>
<!-- ============================ Boo Your Room End ============================= -->
@endif

@if (in_array('Submit listing', $home_modules))
<!-- ============================ Tag & Submit listing ============================= -->
<section class="bg-cover text-center" style="background:#353535 url({{ asset('assets/images/'.$ps->hero_photo)}});" data-overlay="7">
<div class="container">

<div class="row justify-content-center">
<div class="col-lg-7 col-md-10 text-center">
<div class="sec-heading center">
<h2 class="text-light ft-bold">{{ $ps->listing_title }}</h2>
<h3 class="text-light ft-medium mb-4">{{ $ps->listing_subtitle }}</h3>
<p class="text-light mb-4">
@php
echo $ps->listing_details;
@endphp
</p>
</div>
</div>
</div>

<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12">
<div class="counter-link">
<a href="{{ route('front.listing') }}" class="btn btn-md text-dark ft-medium btn-light">@lang('Explore & Submit Listings')</a>
</div>
</div>
</div>

</div>
</section>
<!-- ============================ Tag & Submit listing End ============================= -->
@endif

@if (in_array('Author', $home_modules))
<!-- ============================ Top Author Lists ============================= -->
<section>
<div class="container">
<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="mb-0 theme-cl">Featured Authors</h6>
<h2 class="ft-bold">Meet Top Authors in Las Vegas</h2>
</div>
</div>
</div>

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">

<div class="slide_items">
@foreach ($authors as $key=>$author)
<div class="single-list">
<div class="Rego-author-wrap">
<div class="Rego-author-lists">{{ count($author->listings) }} @lang('Listings')</div>
<div class="Rego-author-thumb">
<a href="{{ route('front.author.details',$author->username) }}">
<img src="{{ asset('assets/images/'.$author->photo ) }}" class="img-fluid circle" alt="">
</a>
</div>
<div class="Rego-author-caption">
<h4 class="fs-md mb-0 ft-medium m-catrio">
<a href="{{ route('front.author.details',$author->username) }}">{{ $author->name }}</a>
</h4>
<div class="Rego-location"><i class="fas fa-map-marker-alt me-1 theme-cl"></i>{{ $author->address }}</div>
</div>
<div class="Rego-author-links">
<ul class="Rego-social">
<li>
<a href="{{ $author->fb_link }}">
<i class="lni lni-facebook-filled"></i>
</a>
</li>

<li>
<a href="{{ $author->linkedin_link }}">
<i class="lni lni-linkedin-original"></i>
</a>
</li>

<li>
<a href="{{ $author->twitter_link }}">
<i class="lni lni-twitter-original"></i>
</a>
</li>

<li>
<a href="{{ $author->instagram_link }}">
<i class="lni lni-instagram-original"></i>
</a>
</li>
</ul>
</div>
</div>
</div>
@endforeach

</div>

</div>
</div>

</div>
</section>
<!-- ============================ Top Author Lists End ============================= -->
@endif

@if (in_array('CTAs', $home_modules))

@include('partials.front.cta')

@endif


@include('partials.front.footer')

@endsection

@push('js')
<script>
'use strict';

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

$(document).on('click','.wishList',function(){
let $this = $(this);
let listingId = $(this).data('listing');
let userId = $(this).data('user');
if(userId == ''){
window.location.href = mainurl+'/user/login'
}

$.ajax({
method:"POST",
url: mainurl+'/listing/wishlist',
data: {
listing_id : listingId,
user_id : userId
},
success:function(data)
{
if(data.success){
$this.children().prop('class','');
$this.children().prop('class','lni lni-heart-filled  position-absolute');
toastr.success(data.success);
}else{
$this.children().prop('class','');
$this.children().prop('class','lni lni-heart  position-absolute');
toastr.error(data.error);
}
}

});

})

</script>
@endpush
