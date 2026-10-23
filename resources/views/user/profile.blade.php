@extends('layouts.front')
@section('title')
@endsection
@section('content')
<div class="bg_light px-4 py-2">
<div class="dashboard-tlbar d-block">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('user.profile.index') }}"
class="">@lang('My Profile')</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<div class="dashboard-wrap px-4 pt-3 pb-5 bg-light">
<a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false" aria-controls="MobNav">
<i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
</a>
<div class="collapse" id="MobNav">
@includeIf('partials.user.sidebar')
</div>
<div class="dashboard-content">
<div class="dashboard-widg-bar d-block">
<form class="submit-form" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
@csrf
@includeIf('includes.flash')
<div class="row">
<div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 order-xl-last order-lg-last order-md-last">
<div class="d-flex bg-white rounded px-3 py-3 mb-3">
<div class="dash-figure">
<div class="dash-figure-thumb">
<img src="{{ auth()->user()->photo ? asset('assets/images/' . auth()->user()->photo) : asset('assets/front/img/t-4.png') }}" class="img-fluid rounded" alt="">
</div>
<div class="upload-photo-btn">
<div class="Uploadphoto">
<span><i class="fas fa-upload"></i> @lang('Upload Photo')</span>
<input type="file" name="photo" id="profile-image-upload" class="upload">
</div>
</div>
</div>
</div>
</div>
<div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<div class="dashboard-list-wraps-head br-bottom py-3 px-3">
<div class="dashboard-list-wraps-flx">
<h4 class="mb-0 ft-medium fs-md"><i class="fa fa-user-check me-2 theme-cl fs-sm"></i>@lang('My Profile')</h4>
</div>
</div>
<div class="dashboard-list-wraps-body py-3 px-3">
<div class="row">
@if($requiresWhatsappProfile)
    <div class="alert alert-danger" role="alert">
        Please enter your WhatsApp number to continue. We use it for important listing and payment updates.
    </div>
@endif
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('Name')</label> <span class="text-danger">*</span>
<input type="text" name="name" class="form-control rounded" placeholder="Amit Kumar" value="{{ old('name', $user->name) }}">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('Email')</label>
<input type="text" class="form-control rounded" placeholder="amitkumar@gmail.com" value="{{ $user->email }}"
readonly>
<div class="form-check pt-1">
<input class="form-check-input" name="hide_email"
{{ $user->hide_email == 1 ? 'checked' : '' }} type="checkbox" value="1" id="hide_email">
<label class="form-check-label" for="hide_email">
Hide My Email From Public Profile
</label>
</div>
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('Mobile / WhatsApp Number')</label> <span class="text-danger">*</span>
<input type="text" class="form-control rounded" placeholder="017XXXXXXXX or +88017XXXXXXXX" name="phone" value="{{ old('phone', $user->phone) }}">
<small class="mb-1 text-muted d-block">Please enter your WhatsApp number to continue. We use it for important listing and payment updates.</small>
<div class="form-check pt-1">
<input class="form-check-input" name="hide_phone"
{{ $user->hide_phone == 1 ? 'checked' : '' }} type="checkbox" value="1" id="hide_phone">
<label class="form-check-label" for="hide_phone">
Hide My Mobile From Public Profile
</label>
</div>
</div>
</div>
@if(!$user->whatsapp_opt_in)
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('Check Consent')</label>
<div class="form-check p-3 border rounded bg-white shadow-sm">
<input class="form-check-input mt-2" type="checkbox" name="whatsapp_opt_in" value="1" id="whatsapp_opt_in">
<label class="form-check-label ft-medium fs-md text-dark" for="whatsapp_opt_in">
Send me important listing approval and payment updates from BDTalika on WhatsApp.
</label>
</div>
</div>
</div>
@endif
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('City')</label>
<input type="text" class="form-control rounded" placeholder="Dhaka" name="city" value="{{ $user->city }}">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('Zip')</label>
<input type="text" class="form-control rounded" name="zip" value="{{ $user->zip }}">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('Direction')</label>
<input type="text" class="form-control rounded" placeholder="@lang('Direction')" name="direction" value="{{ $user->direction }}">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('Website')</label>
<input type="text" class="form-control rounded" name="website" value="{{ $user->website }}" placeholder="@lang('website')">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label">@lang('Address')</label>
<input type="text" class="form-control rounded" name="address" value="{{ $user->address }}" placeholder="Dhaka 1212, Bangladesh">
</div>
</div>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<div class="d-flex align-items-center justify-content-between">
<label class="form-label">
@lang('About Notes')
</label>
<div class="word_limit text-muted">0/500</div>
</div>
<textarea class="form-control rounded ht-150" name="about" placeholder="Describe your self">{{ $user->about }}</textarea>
</div>
</div>
</div>
</div>
</div>
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<div class="dashboard-list-wraps-head br-bottom py-3 px-3">
<div class="dashboard-list-wraps-flx">
<h4 class="mb-0 ft-medium fs-md"><i class="fa fa-user-friends me-2 theme-cl fs-sm"></i>@lang('My Social Links')</h4>
</div>
</div>
<div class="dashboard-list-wraps-body py-3 px-3">
<div class="row">
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label"><i class="ti-facebook theme-cl me-1"></i>@lang('Facebook')</label>
<input type="text" class="form-control rounded" placeholder="https://facebook.com/" name="fb_link" value="{{ $user->fb_link }}">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label"><i class="ti-twitter theme-cl me-1"></i>@lang('Twitter')</label>
<input type="text" class="form-control rounded" placeholder="https://twitter.com/" name="twitter_link" value="{{ $user->twitter_link }}">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label"><i class="ti-instagram theme-cl me-1"></i>@lang('Instagram')</label>
<input type="text" class="form-control rounded" placeholder="https://instagram.com/" name="instagram_link" value="{{ $user->instagram_link }}">
</div>
</div>
<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1 form-label"><i class="ti-linkedin theme-cl me-1"></i>@lang('Linkedin')</label>
<input type="text" class="form-control rounded" placeholder="https://linkedin.com/" name="linkedin_link" value="{{ $user->linkedin_link }}">
</div>
</div>
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<button type="submit" class="btn theme-bg rounded text-light">@lang('Save Changes')</button>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</form>
</div>
</div>
</div>
@endsection
@push('js')
<script>
"use strict"
var prevImg = $('.dash-figure .dash-figure-thumb').html();
function proPicURL(input) {
if (input.files && input.files[0]) {
var uploadedFile = new FileReader();
uploadedFile.onload = function(e) {
var preview = $('.dash-figure').find('.dash-figure-thumb');
preview.html(`<img src="${e.target.result}" class="img-fluid rounded" alt="user">`);
preview.addClass('image-loaded');
preview.hide();
preview.fadeIn(650);
$(".image-view").hide();
$(".remove-thumb").show();
}
uploadedFile.readAsDataURL(input.files[0]);
}
}
$("#profile-image-upload").on('change', function() {
proPicURL(this);
});
var prevImg = $('.dash-figure .dash-figure-breadcumb').html();
function proBreadURL(input) {
if (input.files && input.files[0]) {
var uploadedFile = new FileReader();
uploadedFile.onload = function(e) {
var preview = $('.dash-figure').find('.dash-figure-breadcumb');
preview.html(`<img src="${e.target.result}" class="img-fluid rounded" alt="user">`);
preview.addClass('image-loaded');
preview.hide();
preview.fadeIn(650);
$(".image-view").hide();
$(".remove-thumb").show();
}
uploadedFile.readAsDataURL(input.files[0]);
}
}
$("#profile-breadcumb-upload").on('change', function() {
proBreadURL(this);
});
$('textarea[name="about"]').on('keyup', function() {
var length = $(this).val().length;
var maxLength = 500;
if (length > maxLength) {
var trimmedText = $(this).val().substring(0, maxLength);
$(this).val(trimmedText);
$('.word_limit').text(maxLength + '/500');
} else {
$('.word_limit').text(length + '/500');
}
});
</script>
@endpush
