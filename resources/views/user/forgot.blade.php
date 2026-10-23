@extends('layouts.front')
@section('content')
<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 colz-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="http://localhost/projects/talika/public"
class="color_link">Home</a></li>
<li class="breadcrumb-item"><a href="#">Forgot Password</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<section class="bg-white" style="padding:120px 0px">
<div class="container">
<div class="row align-items-start justify-content-center">
<div class="col-xl-5 col-lg-8 col-md-12">
<div class="signup-screen-wrap">
<div class="signup-screen-single bg-light">
<div class="text-center mb-4">
<h4 class="m-0 ft-medium">@lang('Forgot Password')</h4>
</div>
<form class="account-form row gy-3 gx-4 align-items-center" id="forgotform" action="{{ route('user.forgot.submit') }}" method="POST">
@includeIf('includes.user.form-both')
@csrf
<div class="form-group mb-3">
<label class="mb-1">@lang('Your Email')</label>
<input type="email" name="email" class="form-control py-2 rounded" placeholder="@lang('Email')*">
</div>
<div class="form-group mb-3">
<button type="submit" class="btn btn-md full-width theme-bg text-light rounded ft-medium">
@lang('Sign In')
<div class="spinner-border formSpin" role="status"></div>
</button>
</div>
<div class="form-group text-center mt-4 mb-0">
<p class="mb-0">@lang("Already have an account.")
<a href="{{ route('user.login') }}" class="ft-medium text-success">@lang('Sign in')</a>
</p>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</section>
@endsection
