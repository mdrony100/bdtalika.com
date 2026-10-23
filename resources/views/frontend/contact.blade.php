@extends('layouts.front')
@push('meta_info')
<title>Contact Us to Improve Your Experience at bdtalika.com</title>
<meta property="og:title" content="Contact Us to Improve Your Experience at bdtalika.com">
<meta name="description" content="At bdtalika.com, we are committed to facilitate clients with latest Directory Listings.">
<meta property="og:description" content="At bdtalika.com, we are committed to facilitate clients with latest Directory Listings.">
@endpush
@section('content')
<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 colz-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('front.index') }}">@lang('Home')</a></li>
<li class="breadcrumb-item"><a href="#">@lang('Contact Us')</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<section class="">
<div class="container">
<div class="row justify-content-center mb-2">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="sec_title position-relative text-center">
<h1 class="off_title">{{ $ps->side_title }}</h1>
@auth
@else
    <p class="fs-5"><a href="{{ route('user.register') }}" class="text-primary">Please create account to submit the form.</a></p>
@endauth
</div>
</div>
</div>
<div class="row align-items-start justify-content-center">
<div class="col-lg-8 col-xl-7">
<form id="contactFormId" class="row submit-form py-4 px-3 rounded bg-white mb-4" action="{{ route('front.contact.submit') }}" method="POST">
@csrf
@includeIf('includes.user.form-both')
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="small text-dark ft-medium form-label">@lang('Your Name') <span class="text-danger">*</span></label>
<input type="text" name="name" class="form-control" placeholder="@lang('Your Name')">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="small text-dark ft-medium form-label">@lang('Your Email') <span class="text-danger">*</span></label>
<input type="email" name="email" class="form-control" placeholder="@lang('Enter your email')">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="small text-dark ft-medium form-label">@lang('Your Phone') <span class="text-danger">*</span></label>
<input type="text" name="phone" class="form-control" placeholder="+880 1234-567-7890">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="small text-dark ft-medium form-label">@lang('Your Subject') <span class="text-danger">*</span></label>
<input type="text" name="subject" class="form-control" placeholder="@lang('Type Your Subject')">
</div>
</div>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="small text-dark ft-medium form-label">@lang('Your Message') <span class="text-danger">*</span></label>
<textarea name="message" id="message" class="form-control ht-80" placeholder="@lang('Your Message...')"></textarea>
</div>
</div>
<div class="mb-2">
    <div id="captcha-box">
        {!! \App\Http\Controllers\MatchCaptchaController::input('captcha') !!}
    </div>
</div>
<div class="text-center">
<div class="form-group mb-3">
{{-- <button data-sitekey="6LflA1opAAAAAFbBQYYE4VDDCZ5B7QOSyzgYo7-z" data-callback="onSubmit" data-action="submit" type="submit" class="g-recaptcha btn theme-bg text-light btn-contact">
@lang('Send Message')
<div class="spinner-border formSpin" role="status"></div>
</button> --}}
<button  type="submit" class="btn theme-bg text-light btn-contact">
    @lang('Send Message')
    <div class="spinner-border formSpin" role="status"></div>
    </button>
</div>
</div>
</form>
</div>
<div class="col-xl-10 col-lg-11 col-md-12 col-sm-12">
<div class="row d-flex justify-content-center">
<div class="col-xl-4 col-lg-4 col-md-12">
<div class="gray rounded p-3 mb-2">
<h4 class="ft-medium mb-3 theme-cl">@lang('Drop A Mail'):</h4>
<p>@lang('Drop mail we will contact you within 24 hours.')</p>
<p class="lh-1 text-dark">{{ $ps->contact_email }}</p>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection
@push('js')
{{-- <script src="https://www.google.com/recaptcha/api.js"></script> --}}
<script>
function onSubmit(token) {document.getElementById("contactFormId").submit();}
</script>
<script>
    {!! \App\Http\Controllers\MatchCaptchaController::getScript() !!}
 </script>
@endpush
