@extends('layouts.front')
@push('meta_info')
<title>{{ $data->meta_title ?? '' }}</title>
<meta property="og:title" content="{{ $data->meta_title ?? '' }}">
<meta name="description" content="{{ $data->meta_description ?? '' }}">
<meta property="og:description" content="{{ $data->meta_description ?? '' }}">
@endpush
@push('css')
<style>
.header_card h1 {font-size: 28px;line-height: 22px;margin-bottom: 15px;font-weight: 500;color: #414141;}
.header_card .paragrap {font-size: 15px;color: #555;}.title .ad_type {color: #1198ED;font-size: 20px;font-weight: 600;}
.cat_list li {padding: 5px 0px;}
.cat_list li a {color: #1198ED;line-height: 17px;display: inline-block;font-size: 15px;font-weight: 500;}
.cat_list li a:hover {color: #121212;}
.cat_list li a span {font-size: 15px;}.cat_list .disabled a {color: #A0A0A0;pointer-events: none;cursor: default;}
.cat_list li {width: 25%;float: left;position: relative;}
.cat_list {display: block;width: 100%;overflow: hidden;}
.city_title a {font-size: 19px;color: #1198ED;}
.disabled a {color: #A0A0A0 !important;pointer-events: none;cursor: default;}
@media screen and (max-width:991px) {.cat_list li {width: 33.33%;}}
@media screen and (max-width:767px) {.cat_list li {width: 50%;}}
@media screen and (max-width:575px) {.header_card h1 {font-size: 20px;}}
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
<li class="breadcrumb-item"><a href="#">Browse Locations</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<section class="middle">
<div class="container">
@if (isset($data->title) || isset($data->sub_title)) 
    <div class="header_card mb-4">
    @if (isset($data->title)) 
        <h1>{{ $data->title }}</h1>
    @endif
    @if (isset($data->sub_title)) 
    <div class="paragrap">{{ $data->sub_title }}</div>
    @endif
    </div>
@endif
<div class="mb-4">
<div class="listing_type_cat">
@foreach ($cities as $city)
<div class=" mb-3 border-bottom">
<h3 class="city_title mb-3 {{ $city->ads->count() == 0 ? 'disabled' : '' }}">
<a href="{{ route('front.listing', ['area' => $city->slug]) }}">{{ $city->name }} ({{ $city->ads->count() }})</a>
</h3>
<ul class="cat_list p-0">
@foreach ($city->areas as $area)
<li class="{{ $area->listings->count() == 0 ? 'disabled' : '' }}">
<a href="{{ route('front.listing', ['area' => $area->slug]) }}"> {{ $area->title }} <span>({{ $area->listings->count() }})</span></a>
</li>
@endforeach
</ul>
</div>
@endforeach
</div>
</div>
</div>
</section>
@endsection
