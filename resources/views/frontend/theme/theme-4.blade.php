@extends('layouts.front')

@push('css')

@endpush

@section('content')
@if (in_array('Banner', $home_modules))

<div class="home-banner margin-bottom-0 gray">
<div class="container">
<div class="row justify-content-between">
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
<div class="pe-3">
<div class="banner_caption text-left mb-4">
<h1 class="banner_title ft-normal mb-1"><span class="theme-cl ft-bold">{{ $ps->hero_title }}</h1>
<p class="fs-md ft-regular">{{ $ps->hero_subtitle }}</p>
</div>

<form class="main-search-wrap fl-wrap one-column" action="{{ route('front.listing') }}" method="GET">
<div class="main-search-item">
<span class="search-tag">@lang('Find')</span>
<input type="text" class="form-control radius" name="title" placeholder="@lang('Nail salons, plumbers, takeout...')">
</div>
<div class="main-search-button">
<button class="btn full-width theme-bg text-white" type="submit">@lang('Search')<i class="fas fa-search"></i></button>
</div>
</form>
<div class="Rego-over-cats-sty">
<h6 class="ft-bold mb-1">@lang('Browse categories')</h6>
<span class="small">@lang('Get Popular Categories in Your City')</span>
<ul>
@foreach ($categories as $key=>$data)
<li>
<a href="{{ route('front.listing',['category' => $data->slug]) }}" style="background-color: {{ $data->bg_color}}">
<i class="{{ $data->icon }}"></i>
</a>
</li>
@endforeach
</ul>
</div>
</div>
</div>
<div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 col-12 order-lg-3">
<div class="Rego-counter">
<ul>
@foreach ($counters as $key=>$data)
<li><div class="Rego-ylp">
<div class="Rego-ylp-first">
<h3><span class="text-success count">{{ $data->count }}</span>{{ $data->messurement }}</h3>
</div>
<div class="Rego-ylp-last">
<span>{{ $data->title }}</span>
</div>
</div>
</li>
@endforeach
</ul>
</div>
</div>
<div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12">
<div class="list-tyuhn">
<img src="{{ asset('assets/images/'.$ps->hero_photo) }}" class="img-fluid" alt="">
</div>
</div>
</div>
</div>
</div>

@endif

@if (in_array('Feature Directory', $home_modules))
<!-- ======================= All Listing ======================== -->
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

<!-- row -->
<div class="row justify-content-center">
@foreach ($featured_listing as $key=>$data)
<!-- Single -->
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
<div class="Rego-grid-wrap card">
<div class="Rego-grid-upper">
<div class="Rego-bookmark-btn">
<button type="button" class="wishList" data-listing="{{ $data->id }}" data-user={{ auth()->id() }}>
<i class="lni lni-heart{{ $data->userFavourite(auth()->id(),$data->id) ? '-filled' : ''}} position-absolute"></i>
</button>
</div>

<div class="Rego-pos ab-left">
<div class="Rego-status open me-2">{{ $data->openClose($data->id) }}</div>
@if ($data->is_feature)
<div class="Rego-featured-tag">@lang('Featured')</div>
@endif
</div>
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
<div class="Rego-author">
@if ($data->user_id == NULL && $data->admin_id == NULL)
<a href="{{ route('front.author.details','admin') }}">
<img src="{{ asset('assets/images/'.getAdmin()->photo)}}" class="img-fluid circle" alt="">
</a>
@else
<a href="{{ route('front.author.details',$data->user->username) }}">
<img src="{{ asset('assets/images/'.$data->user->photo)}}" class="img-fluid circle" alt="">
</a>
@endif
</div>

<div class="Rego-cates multi">
<a href="{{ route('front.listing',['category' => $data->category->slug]) }}" class="cats-2">{{ $data->category->title }}</a>
</div>

<h4 class="mb-0 ft-medium medium">
<a href="{{ route('front.listing.details',$data->slug) }}" class="text-dark fs-md">
{{ $data->name }}
<span class="verified-badge">
<i class="fas fa-check-circle"></i>
</span>
</a>
</h4>

<div class="Rego-middle-caption mt-2">
<p>{{ Str::limit($data->description,58) }}</p>
<div class="Rego-facilities-wrap mb-0">
<div class="Rego-facility-title">@lang('Facilities') :</div>
@if ($data->amenity_icons != NULL)
<div class="Rego-facility-list">
<ul class="no-list-style">
@foreach (json_decode($data->amenity_icons,true) as $key=>$icon)
@if($key<=4)
<li><i class="{{ $icon }}"></i></li>
@endif
@endforeach
</ul>
</div>
@endif
</div>
</div>
</div>
<div class="Rego-grid-footer py-2 px-3">
<div class="Rego-ft-first">
<div class="Rego-location">
<i class="fas fa-map-marker-alt me-1 theme-cl"></i>
{{ $data->real_address }}
</div>
</div>
</div>
</div>
</div>
</div>
@endforeach

</div>
<!-- /row -->

</div>
</section>
<!-- ======================= All Listings ======================== -->
@endif

@if (in_array('Category', $home_modules))

<section class="space min gray">
<div class="container">

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="mb-0 theme-cl">Popular Categories</h6>
<h2 class="ft-bold">Browse Top Categories</h2>
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

@if (in_array('Location', $home_modules))
<!-- ======================= Explore By Location ======================== -->
<section class="space min">
<div class="container">

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="theme-cl mb-0">Find By Location</h6>
<h2 class="ft-bold">Explore By <span class="">Top Locations</span></h2>
</div>
</div>
</div>

<!-- row -->
<div class="row justify-content-center">
@foreach ($locations as $key=>$data)
<div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
<div class="Rego-img-location-wrap">
<div class="Rego-img-location-thumb"><a href="{{ route('front.listing',['location' => $data->id]) }}"><img src="{{ asset('assets/images/'.$data->photo) }}" class="img-fluid" alt=""></a></div>
<div class="Rego-img-location-caption">
<h4 class="fs-md mb-0 ft-medium m-catrio">{{ $data->name }}</h4>
<a href="{{ route('front.listing',['location' => $data->id]) }}" class="Rego-cat-arrow"><i class="lni lni-arrow-right-circle"></i></a>
</div>
</div>
</div>
@endforeach

</div>
<!-- /row -->

</div>
</section>
<!-- ======================= Explore By Location ======================== -->
@endif

@if (in_array('Submit listing', $home_modules))
<!-- ============================ Tag & Submit listing ============================= -->
<section class="bg-cover text-center" style="background:#353535 url({{ asset('assets/images/'.$ps->listing_photo)}});" data-overlay="7">
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

@if (in_array('Review', $home_modules))
<!-- ======================= Customer Review ======================== -->
<section class="space">
<div class="container">

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="text-muted mb-0">@lang('Our Reviews')</h6>
<h2 class="ft-bold">@lang('What Our Customer') <span class="">@lang('Saying')</span></h2>
</div>
</div>
</div>

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="reviews-slide">
@foreach ($reviews as $key=>$data)
<!-- single review -->
<div class="single-list">
<div class="single_review">
<div class="sng_rev_thumb"><figure><img src="{{ asset('assets/images/'.$data->photo) }}" class="img-fluid circle" alt=""></figure></div>
<div class="rev_author">
<h4 class="mb-0 fs-md ft-medium">{{ $data->title }}</h4>
<span class="fs-sm theme-cl">{{ $data->subtitle }}</span>
</div>
<div class="sng_rev_caption text-center">
<div class="rev_desc mb-4">
<p>
@php
echo $data->details;
@endphp
</p>
</div>
</div>
</div>
</div>
@endforeach
</div>
</div>
</div>
</div>
</section>
<!-- ======================= Customer Review ======================== -->
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
