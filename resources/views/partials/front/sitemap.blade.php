@extends('layouts.front')
@push('meta_info')
<title>Sitemap for bdtalika.com</title>
<meta property="og:title" content="Sitemap for bdtalika.com">
<meta name="description" content="Search thousands of directories in Bangladesh.">
<meta property="og:description" content="Search thousands of directories in Bangladesh.">
@endpush
@php
$area_query = 'bangladesh';
@endphp
@push('css')
<style>
.card {background: #f7f7f7 !important;padding: 30px !important;border-radius: 12px;border: 1px solid #eaeaea !important;}
.card_header h3 {font-size: 21px;color: #222;font-weight: 600;}
.category li a {font-size: 18px;color: #0074ba;display: block;border-bottom: 1px solid #ebebeb;padding: 15px 1px;}
.accordion-button {outline: none !important;box-shadow: none !important;background: transparent !important;border: none !important;padding: 15px 1px;font-size: 18px;border-bottom: 1px solid #ebebeb;color: #0074ba;}
.accordion-button:not(.collapsed) {color: #323232 !important;font-weight: 500;}
.accordion-item {background: transparent !important;border: none !important;padding: 0px 0px !important;}
.page li a {font-size: 15px;color: #0074ba;display: block;padding: 5px 0px;}
</style>
@endpush
@section('content')
<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 colz-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('front.index') }}">@lang('Home')</a></li>
<li class="breadcrumb-item"><a href="#">Sitemap</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<section class="middle">
<div class="container">
<div class="row d-flex justify-content-center">
<div class="col-xl-9">
<div class="card">
<div class="card_header">
<h3 class="card-title text-center">Sitemap</h3>
<p class="text-center">
The Bdtalika site is organised into several sections, as shown below. The sections are
broken down by category and there is also a general section where you will find pages with
useful information.
</p>
</div>
<div class="card-body p-0">
<ul class="category m-0 p-0">
@foreach ($all_categories as $key => $cat)
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => $cat->slug]) }}">{{ $cat->title }}</a></li>
@endforeach
</ul>
<div class="accordion" id="accordionExample">
<div class="accordion-item">
<h2 class="accordion-header">
<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"> Other pages </button>
</h2>
<div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
<div class="accordion-body page p-0">
<div class="row gy-2 gy-md-0">
<div class="col-md-4">
<ul class="p-0 m-0">
<li><a href="{{ route('front.listing') }}">@lang('Explore Listings')</a></li>
<li><a href="https://blog.bdtalika.com/">@lang('Blogs')</a></li>
<li><a href="{{ route('front.contact') }}">@lang('Contact')</a></li>
<li><a href="{{ route('front.category.directory') }}">@lang('Browse by Category')</a></li>
</ul>
</div>
<div class="col-md-4">
<ul class="p-0 m-0">
@foreach ($pages as $key => $page)
<li><a href="{{ route('front.page', $page->slug) }}">{{ $page->title }}</a></li>
@endforeach
<li><a href="{{ route('sitemap') }}">@lang('Sitemap')</a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection

