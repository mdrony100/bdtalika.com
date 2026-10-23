@extends('layouts.front')
@section('content')
<div class="bg-light py-2">
    <div class="container">
    <div class="row">
    <div class="colxl-12 colz-lg-12 col-md-12">
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="http://localhost/projects/talika/public" class="color_link">Home</a></li>
    <li class="breadcrumb-item"><a href="#">Register</a></li>
    </ol>
    </nav>
    </div>
    </div>
    </div>
    </div>
<section class="bg-white">
<div class="container">
<div class="row align-items-start justify-content-center">
<div class="col-xl-5 col-lg-8 col-md-10">
<div class="signup-screen-wrap">
<div class="signup-screen-single bg-light rounded border">
<div class="text-center mb-4">
<h4 class="m-0 ft-medium">@lang('Create An Account')</h4>
</div>
<form id="registerform" class="row gy-2 gx-sm-2" action="{{ route('user.register.submit') }}" method="POST">
@includeIf('includes.user.form-both')
@csrf
<div class="col-sm-6">
<div class="form-group">
<label class="mb-1 form-label">@lang('Your Name') <span class="text-danger">*</span></label>
<input type="text" name="name" class="form-control py-2 rounded" placeholder="@lang('Your Name')" required>
</div>
</div>
<div class="col-sm-6">
<div class="form-group">
<label class="mb-1 form-label">@lang('Email') <span class="text-danger">*</span></label>
<input type="text" name="email" class="form-control py-2 rounded" placeholder="@lang('Email')" required>
</div>
</div>
<div class="form-group">
<label class="mb-1 form-label">@lang('Username') <span class="text-danger">*</span></label>
<input type="text" name="username" class="form-control py-2 rounded" placeholder="@lang('Username')" required>
</div>
<div class="form-group">
<label class="form-label">@lang('Mobile / WhatsApp Number') <span class="text-danger">*</span></label>
<small class="mb-1 text-muted d-block mb-1 text-dark">Enter your WhatsApp number for important listing, payment and account updates.</small>
<input type="text" name="phone" class="form-control py-2 rounded" placeholder="017XXXXXXXX or +88017XXXXXXXX" required>
</div>
<div class="form-group">
<label class="mb-1 form-label">@lang('Check Consent')</label>
<div class="form-check p-3 border rounded bg-white shadow-sm">
<input class="form-check-input mt-2" type="checkbox" name="whatsapp_opt_in" value="1" id="whatsapp_opt_in">
<label class="form-check-label ft-medium fs-md text-dark" for="whatsapp_opt_in">
Send me important listing approval and payment updates from BDTalika on WhatsApp.
</label>
</div>
</div>
<div class="form-group">
<label class="mb-1 form-label">@lang('Password') <span class="text-danger">*</span></label>
<input type="password" name="password" class="form-control py-2 rounded" placeholder="@lang('Password')*" required>
</div>
<div class="form-group">
<label class="mb-1 form-label">@lang('Confirm Password') <span class="text-danger">*</span></label>
<input type="password" name="password_confirmation" class="form-control py-2 rounded" placeholder="@lang('Confirm Password')*" required>
</div>
<div class="form-group mb-3">
<div id="captcha_box">
    {!! \App\Http\Controllers\RegisterCaptchaController::input('captchafield') !!}
</div>
</div>
<div class="form-group">
<button type="submit" class="btn btn-md full-width theme-bg text-light rounded ft-medium">
@lang('Sign Up')
<div class="spinner-border formSpin" role="status"></div>
</button>
</div>
<div class="hr-text pt-2">
<span>or</span>
</div>
<div class="social_login mt-4 text-center">
<a rel="nofollow" href="{{ route('social.login', 'google') }}" class="btn">
<img src="{{ asset('assets/images/icon/google.png') }}" alt="Login with Google">
Login with Google
</a>
</div>
<div class="form-group text-center mt-4 mb-0">
<p class="mb-0">@lang('Have You Already An account')? <a href="{{ route('user.login') }}" class="ft-medium text-success">@lang('Sign In')</a></p>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</section>



@endsection
@push('js') 
<script>
    {!! \App\Http\Controllers\RegisterCaptchaController::getScript() !!}
</script>
@endpush
