@extends('layouts.front')
@push('meta_info')
<title>{{ $page->meta_title }}</title>
<meta property="og:title" content="{{ $page->meta_title }}">
<meta name="description" content="{{ strip_tags($page->meta_description) }}">
<meta property="og:description" content="{{ strip_tags($page->meta_description) }}">
<meta name="keywords" content="{{ $page->meta_tag}}">
@endpush
@section('content')

<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 colz-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('front.index') }}">@lang('Home')</a></li>
<li class="breadcrumb-item"><a href="#">{{ $page->title }}</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>


<!-- ======================= About Us Detail ======================== -->
<section class="middle">
<div class="container">
<div class="row align-items-center justify-content-between">

<div class="col-xl-11 col-lg-12 col-md-6 col-sm-12">
<div class="abt_caption">
<h1 class="ft-medium mb-4">{{ $page->title }}</h1>
<p class="mb-4">
@php
echo $page->details;
@endphp
</p>
</div>
</div>

</div>
</div>
</section>
<!-- ======================= About Us End ======================== -->


{{-- @include('partials.front.cta') --}}




@endsection

@push('js')
@endpush
