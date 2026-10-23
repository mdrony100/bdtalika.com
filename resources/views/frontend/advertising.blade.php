@extends('layouts.front')
@push('meta_info')
<title>Advertising On bdtalika</title>
<meta property="og:title" content="Advertising on bdtalika">
<meta name="description" content="Discover all the great ways to promote your business on bdtalika">
<meta property="og:description" content="Discover all the great ways to promote your business on bdtalika">
@endpush
@push('css')
@endpush
@section('content')

<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 colz-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('front.index') }}">@lang('Home')</a></li>
<li class="breadcrumb-item"><a href="#">@lang('Advertising')</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>


<section class="pb-5 pt-5 pt-1 position-relative">
<div class="container">
<div class="advertising_content text-center">
<h1 class="mb-4">
Advertise your brand on Bdtalika – the largest local website in Bangladesh!
</h1>
<h5>Bdtalika has millions of visitors every month – which of them do you want to reach?</h5>
<p>
Along with the service of posting an ad on bdtalika.com, we now offer extra options for businesses who
want to reach new customers quickly and easily. With our banner advertising opportunities, you can
target local customers directly and advertise your brand efficiently.
</p>
</div>

<div class="advertising_product pt-5">
<h2 class="text-center mb-5">Our Advertising Products</h2>
<div class="row d-flex justify-content-center">
<div class="col-xl-8">
<ul class="nav nav-pills nav-fill mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
aria-selected="true">Leaderboard</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
data-bs-target="#pills-profile" type="button" role="tab"
aria-controls="pills-profile" aria-selected="false">Skyscraper</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
data-bs-target="#pills-contact" type="button" role="tab"
aria-controls="pills-contact" aria-selected="false">Large Mobile Banner</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link border-0" id="pills-disabled-tab" data-bs-toggle="pill"
data-bs-target="#pills-disabled" type="button" role="tab"
aria-controls="pills-disabled" aria-selected="false">Square</button>
</li>
</ul>
<div class="tab-content pt-4" id="pills-tabContent">
<div class="tab-pane fade show active" id="pills-home" role="tabpanel"
aria-labelledby="pills-home-tab" tabindex="0">
<div class="advertising_content border-0">
<div class="row gy-4 gy-md-0">
<div class="col-md-6">
<div class="advertising_image">
<img src="{{ asset('assets/images/default.png') }}" class="img-fluid rounded"
alt="Leaderboard">
</div>
</div>
<div class="col-md-6">
<div class="content">
<h4 class="mb-3">Leaderboard</h4>
<p class="mb-3">
Strategically positioned above the navigation bar on Mobile and below on
Desktop, the Leaderboard is viewed by practically every visitor of the
site. A great choice for building brand awareness for everyone or in
specific categories.
</p>
<p>
Dimensions on Desktop: <strong>970 x 90 px</strong>
</p>
<p>
Dimensions on Mobile: <strong>320 x 50 px</strong>
</p>
</div>
</div>
</div>
</div>
</div>
<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
<div class="advertising_content border-0">
<div class="row gy-4 gy-md-0">
<div class="col-md-6">
<div class="advertising_image">
<img src="{{ asset('assets/images/default.png') }}" class="img-fluid rounded"
alt="Image">
</div>
</div>
<div class="col-md-6">
<div class="content">
<h4 class="mb-3">Skyscraper</h4>
<p class="mb-3">
Strategically positioned above the navigation bar on Mobile and below on
Desktop, the Leaderboard is viewed by practically every visitor of the
site. A great choice for building brand awareness for everyone or in
specific categories.
</p>
<p>
Dimensions on Desktop: <strong>970 x 90 px</strong>
</p>
<p>
Dimensions on Mobile: <strong>320 x 50 px</strong>
</p>
</div>
</div>
</div>
</div>
</div>
<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
<div class="advertising_content border-0">
<div class="row gy-4 gy-md-0">
<div class="col-md-6">
<div class="advertising_image">
<img src="{{ asset('assets/images/default.png') }}" class="img-fluid rounded" alt="Image">
</div>
</div>
<div class="col-md-6">
<div class="content">
<h4 class="mb-3">Large Mobile Banner</h4>
<p class="mb-3">
Strategically positioned above the navigation bar on Mobile and below on
Desktop, the Leaderboard is viewed by practically every visitor of the
site. A great choice for building brand awareness for everyone or in
specific categories.
</p>
<p>
Dimensions on Desktop: <strong>970 x 90 px</strong>
</p>
<p>
Dimensions on Mobile: <strong>320 x 50 px</strong>
</p>
</div>
</div>
</div>
</div>
</div>
<div class="tab-pane fade" id="pills-disabled" role="tabpanel"
aria-labelledby="pills-disabled-tab" tabindex="0">
<div class="advertising_content border-0">
<div class="row gy-4 gy-md-0">
<div class="col-md-6">
<div class="advertising_image">
<img src="{{ asset('assets/images/default.png') }}" class="img-fluid rounded"
alt="Image">
</div>
</div>
<div class="col-md-6">
<div class="content">
<h4 class="mb-3">Square</h4>
<p class="mb-3">
Strategically positioned above the navigation bar on Mobile and below on
Desktop, the Leaderboard is viewed by practically every visitor of the
site. A great choice for building brand awareness for everyone or in
specific categories.
</p>
<p>
Dimensions on Desktop: <strong>970 x 90 px</strong>
</p>
<p>
Dimensions on Mobile: <strong>320 x 50 px</strong>
</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="advertise_contact row d-flex align-items-center justify-content-center">
<div class="col-md-7 col-lg-6 col-xl-4">
<div class="bg-light rounded p-4 text-center">
<h4>Questions? Get in touch!</h4>
<p class="d-flex align-items-center justify-content-center">
<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
stroke-linejoin="round"
class="icon icon-tabler icons-tabler-outline icon-tabler-mail-opened">
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M3 9l9 6l9 -6l-9 -6l-9 6" />
<path d="M21 9v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" />
<path d="M3 19l6 -6" />
<path d="M15 13l6 6" />
</svg>
<span class="ps-1">Email us</span>
</p>
<a href="#">support@bdtalika.com</a>
</div>
</div>
</div>

</div>
</section>
@endsection
@push('js')
@endpush
