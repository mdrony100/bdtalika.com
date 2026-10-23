@extends('layouts.front')
@push('meta_info')
<title>{{ $data->meta_title ?? ""}}</title>
<meta property="og:title" content="{{ $data->meta_title ?? '' }}">
<meta name="description" content="{{ $data->meta_description ?? '' }}">
<meta property="og:description" content="{{ $data->meta_description  ?? '' }}">
<meta name="keywords" content="dhaka business directory, chittagong business directory, bangladesh local business listing, businesses in dhaka bangladesh, companies in bangladesh directory">
<style>
.header_card h1 {font-size: 28px;line-height: 22px;margin-bottom: 15px;font-weight: 500;color: #414141;}
.header_card .paragrap {font-size: 16px;color: #555;}
.title .ad_type {color: #1198ED;font-size: 20px;font-weight: 600;}
.cat_list li a {color: #1198ED;padding: 5px 0;line-height: 17px;display: inline-block;font-size: 16px;font-weight: 500;}
.cat_list li a:hover {color: #1198ED;}
.cat_list li a span {font-size: 15px;}
.cat_list .disabled a {cursor: auto;color: #A0A0A0;}
.cat_list li {width: 25%;float: left;position: relative;}
.cat_list {display: block;width: 100%;overflow: hidden;}
@media screen and (max-width:991px) {.cat_list li {width: 33.33%;}}
@media screen and (max-width:767px) {.cat_list li {width: 50%;}}
@media screen and (max-width:575px) {.cat_list li {width: 100%;}.header_card h1 {font-size: 20px;}}
.bottomtext p {font-size: 18px;color: #01132d;line-height: 30px;text-align: center;}
            .bottomtext h2 {text-align: center;font-size: 24px;font-weight: bold;}
</style>
@endpush
@section('content')
<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 colz-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a></li>
<li class="breadcrumb-item"><a href="#">Categories</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<section class="middle pb-3 bg-white">
<div class="container">
    @if (isset($data->title) || isset($data->sub_title)) 
<div class="header_card mb-4">
@if (isset($data->title)) 
    <h1 class="mb-2">{{ $data->title }}</h1>
@endif
@if (isset($data->sub_title)) 
    <div class="paragrap">{{ $data->sub_title }}</div>
@endif
</div>
@endif
<div class="mb-4">
<div class="listing_type_cat">
@foreach ($categoriesByType as $typeId => $categories)
@php    
$type = DB::table('listing_types')->where('id', $typeId)->pluck('name')->first();
@endphp
<h2 class="title mb-2"><a href="javascript:void(0)" class="ad_type">{{ $type }}</a></h2>
<ul class="cat_list p-0">
@foreach ($categories as $item)
<li class="{{ $item->listings->count() == 0 ? 'disabled' : '' }}">
<a href="{{ $item->listings->count() == 0 ? 'javascript:void(0)' : route('front.listing', ['area' => 'bangladesh', 'category' => $item->slug]) }}">
{{ $item->title }}
<span>({{ $item->listings->count() }})</span>
</a>
</li>
@endforeach
</ul>
@endforeach
</div>
</div>
<div class="bottomtext">
        <div class="category-seo-content">
        <h2>Explore Business Categories in Bangladesh</h2>

        <p>
        BDTalika is a comprehensive <strong>Bangladesh business directory</strong> where users can easily discover companies, services, and organizations across various industries. Our platform organizes thousands of businesses into well-structured categories so visitors can quickly find the services they need. Whether you are searching for restaurants, hospitals, hotels, lawyers, educational institutions, IT companies, or travel agencies, our directory makes it simple to explore businesses by category in Bangladesh.
        </p>

        <p>
        Each category in our <strong>online business directory Bangladesh</strong> contains detailed listings with business descriptions, contact information, addresses, and service details. This helps customers compare companies and choose trusted service providers. From healthcare and education to finance, construction, tourism, and shopping, BDTalika covers a wide range of industries that represent the growing business ecosystem of Bangladesh.
        </p>

        <p>
        If you own a company, you can also submit your business to our <strong>free business listing website in Bangladesh</strong> and reach potential customers who are actively searching for services online. By listing your company in our <strong>business listing website Bangladesh</strong>, you can improve your online visibility and connect with people looking for businesses in your category.
        </p>
        </div>
    </div>
</div>
</section>
@endsection