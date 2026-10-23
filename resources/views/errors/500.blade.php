@extends('layouts.front')

@push('css')

@endpush

@section('content')

<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a></li>
<li class="breadcrumb-item active theme-cl" aria-current="page">@lang('Something Went Wrong')</li>
</ol>
</nav>
</div>
</div>
</div>
</div>


<!-- ======================= Error Section ======================== -->
<section>
<div class="container">

<div class="row justify-content-center">
<div class="col-12 col-md-10 col-lg-8 col-xl-6 text-center py-5">

<!-- Icon -->
<div class=""><img src="{{ asset('assets/images/error_505.jpg') }}" class="img-fluid" style="max-height: 260px;" alt="{{ __('Server Error') }}"/></div>

<!-- Heading -->
<h1 class="mb-3 ft-bold theme-cl">{{ !empty($gs->error_title) ? $gs->error_title : __('Oops! Something Went Wrong') }}</h1>

<!-- Text -->
<h5 class="ft-medium fs-md mb-2">{{ __('We hit an unexpected problem while loading this page.') }}</h5>
<p class="text-muted mb-5">
{{ !empty($gs->error_text)
    ? $gs->error_text
    : __('Our team has been notified and is working on it. Please try again in a few moments, or head back to the homepage. We apologise for the inconvenience.') }}
</p>

<!-- Buttons -->
<div class="d-flex flex-wrap justify-content-center" style="gap: 10px;">
<a class="btn rounded theme-bg text-light" href="{{ route('front.index') }}"><i class="fas fa-home mr-1"></i> @lang('Go To Home Page')</a>
<a class="btn rounded btn-outline-secondary" href="javascript:history.back()"><i class="fas fa-arrow-left mr-1"></i> @lang('Go Back')</a>
</div>

</div>
</div>

</div>
</section>
<!-- ======================= Error Section End ======================== -->
@endsection
