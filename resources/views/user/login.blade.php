@extends('layouts.front')
@section('content')
<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 colz-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="http://localhost/projects/talika/public" class="color_link">Home</a></li>
<li class="breadcrumb-item"><a href="#">Login</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<section class="bg-white">
<div class="container">
<div class="row align-items-start justify-content-center">
<div class="col-xl-4 col-lg-6 col-md-8">
<div class="signup-screen-wrap">
<div class="signup-screen-single bg-light rounded border">
<div class="text-center mb-4">
<h4 class="m-0 ft-medium">@lang('Login Your Account')</h4>
</div>
<form class="submit-form" id="loginform" action="{{ route('user.login.submit') }}" method="POST">
@includeIf('includes.user.form-both')
@csrf
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('Your Email')</label>
<input type="email" name="email" class="form-control py-2 rounded" placeholder="@lang('Email')*">
</div>
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('Password')</label>
<input type="password" name="password" class="form-control py-2 rounded" placeholder="@lang('Password')*">
</div>
<div class="form-group mb-3">
<div class="d-flex align-items-center justify-content-between">
<div class="flex-1">
<input id="dd" class="checkbox-custom" name="remember" type="checkbox" checked>
<label for="dd" class="checkbox-custom-label">@lang('Remember Me')</label>
</div>
<div class="eltio_k2">
<a href="{{route('user.forgot')}}" class="">@lang('Lost Your Password?')</a>
</div>
</div>
</div>
<div class="form-group mb-3">
<button type="submit" class="btn btn-md full-width theme-bg text-light rounded ft-medium">
@lang('Sign In')
<div class="spinner-border formSpin" role="status"></div>
</button>
</div>
<div class="hr-text pt-2">
<span class="bg-light">or</span>
</div>
<div class="social_login mt-4 text-center">
<a rel="nofollow" href="{{ route('social.login', 'google') }}" class="btn">
<img src="{{ asset('assets/images/icon/google.png') }}" alt="Login with Google">
Login with Google
</a>
</div>
<div class="form-group text-center mt-4 mb-0">
<p class="mb-0">@lang("You Don't have any account?") <a href="{{ route('user.register') }}" class="ft-medium text-success">@lang('Sign Up')</a></p>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</section>
{{-- @include('partials.front.cta') --}}
@endsection