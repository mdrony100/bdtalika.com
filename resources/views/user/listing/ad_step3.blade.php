@extends('layouts.front')
@section('stepThree', 'active')
@push('css')
@endpush
@section('content')
<!-- ======================= dashboard Detail ======================== -->
<div class="bg_light px-4 py-2">
<div class="dashboard-tlbar d-block">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
</li>
<li class="breadcrumb-item">
<a href="javascript:void(0)" class="">@lang('Create Listing')</a>
</ol>
</nav>
</div>
</div>
</div>
</div>
<div class="dashboard-wrap bg-light px-2 px-lg-4 pt-3 pb-5">
<a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false" aria-controls="MobNav">
<i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
</a>

<div class="collapse" id="MobNav">
@includeIf('partials.user.sidebar')
</div>

<div class="dashboard-content">
<div class="dashboard-widg-bar d-block">
<div class="row">
<form class="listingForm" action="{{ route('user.listing.store.step3') }}" method="POST"
enctype="multipart/form-data">
{{ csrf_field() }}

{{-- tab menu --}}
<div class="tab-menu">
@include('user.listing.tabs_menu')
</div>

<div class="dashboard-list-wraps bg-white rounded border">
<div class="dashboard-list-wraps bg-white rounded">
<div id="dynamic" class="container tab-pane"><br>
@if ($category == 'restaurants')
<div class="menu-section-area">
@includeIf('partials.user.listing.menu')
</div>
<div class="row my-2">
<div class="col-lg-12 text-center">
<a href="javascript:;" id="menud-add-btn" class="btn btn-sm btn-primary py-2 px-4 float-end"><i class="fa fa-plus"></i>
{{ __('Add More Menu') }}</a>
</div>
</div>
@endif

@if ($category == 'hotels')
<div class="room-section-area">
@includeIf('partials.user.listing.room')
</div>
<div class="row my-2">
<div class="col-lg-12 text-center">
<a href="javascript:;" id="room-add-btn" class="btn btn-primary py-2 px-4 float-end"><i class="fa fa-plus"></i>
{{ __('Add More Room') }}</a>
</div>
</div>
@endif

@if ($category == 'beauty-spa' || $category == 'universities' || $category == 'lawyers' || $category == 'ac-repair-services')
<div class="beauty-section-area">
@includeIf('partials.user.listing.beauty')
</div>

<div class="row my-2">
<div class="col-lg-12 text-center">
<a href="javascript:;" id="beauty-add-btn" class="btn btn-primary py-2 px-4 float-end"><i class="fa fa-plus"></i>
{{ __('Add More Service') }}</a>
</div>
</div>
@endif

@if ($category == 'real-estate')
@includeIf('partials.user.listing.real_estate')
@endif

@if ($category == 'car')
@includeIf('partials.user.listing.car')
@endif

</div>

<div id="faq" class="container tab-pane">
<div class="faq-section-area">
@includeIf('partials.user.listing.faq')
</div>

<div class="pb-3 overflow-hidden">
<a href="javascript:;" id="faq-add-btn" class="btn btn-sm btn-primary py-2 px-4 float-end"><i class="fa fa-plus"></i>
{{ __('Add More Faq') }}</a>
</div>
</div>
</div>
</div>
<div class="card-footer border-0 text-center pt-3 pb-3 bg-white">
<button type="submit" class="btn theme-bg rounded text-light">
Save & Finish
</button>
</div>
</form>
</div>
</div>
</div>
</div>
@endsection


@push('js')
<script>
'use strict';
$(document).on('click', '.remove-img', function() {
var id = $(this).find('input[type=hidden]').val();
$(this).parent().parent().remove();
});
let menuId = 2;
$('#menud-add-btn').on('click', function() {
$('.menu-section-area').append('' +
`<div class="card mb-4">
<div class="card-header py-3">
<div class="d-flex justify-content-between">
<div>
<h6 class="m-0 font-weight-bold text-primary">{{ __('Menu Items') }}</h6>
</div>
<div>
<a href="javascript:;" class="removeMenu" title="Remove"><i class="fas fa-trash-alt text-danger"></i></a>
</div>
</div>
</div>
<div class="card-body">
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Menu Name') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="menu_title[]" placeholder="{{ __('Enter Name') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Menu Price') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="number" class="input-field" name="menu_price[]" placeholder="{{ __('Enter Price') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Menu Photo') }}</h4>
<p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
</div>
</div>
<div class="col-lg-9">
<div class="wrapper-image-preview">
<div class="box">
<div class="back-preview-image" style="background-image: url({{ asset('assets/images/default.png') }});"></div>
<div class="upload-options">
<label class="img-upload-label" for="menu-items-${menuId}"><i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
<input id="menu-items-${menuId}" type="file" class="image-upload" name="menu_photo[]" accept="image/*">
</div>
</div>
</div>
</div>
</div>
</div>
</div>
` + '')
menuId++;
$('.menu-section-area').on('click', '.removeMenu', function() {
$(this).closest('.card').remove();
});
})

let roomId = 2;
$('#room-add-btn').on('click', function() {
$('.room-section-area').append('' +
`<div class="card mb-4">
<div class="card-header py-3">
<div class="d-flex justify-content-between">
<div>
<h6 class="m-0 font-weight-bold text-primary">{{ __('Hotel room') }}</h6>
</div>
<div>
<a href="javascript:;" class="removeRoom" title="Remove"><i class="fas fa-trash-alt text-danger"></i></a>
</div>
</div>
</div>
<div class="card-body">
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room Name') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="room_name[]" placeholder="{{ __('Enter Name') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room Price') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="room_price[]" placeholder="{{ __('Enter Price') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room Photo') }}</h4>
<p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
</div>
</div>
<div class="col-lg-9">
<div class="wrapper-image-preview">
<div class="box">
<div class="back-preview-image" style="background-image: url({{ asset('assets/images/placeholder.jpg') }});"></div>
<div class="upload-options">
<label class="img-upload-label" for="room-items-${roomId}"><i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
<input id="room-items-${roomId}" type="file" class="image-upload" name="room_photo[]" accept="image/*">
</div>
</div>
</div>
</div>
</div>

<div class="row mt-3">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room amenities') }}</h4>
<p class="sub-heading">{{ __('Seperated By Comma(,)') }}</p>
</div>
</div>

<div class="col-lg-9">
<input type="text" id="tags" class="mytags tagify${roomId}" name="room_amenities[]" placeholder="{{ __('Room amenities') }}"  value="">
</div>
</div>

<div class="row mt-3">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room description') }}</h4>
</div>
</div>
<div class="col-lg-9">
<textarea class="input-field" name="room_description[]" placeholder="{{ __('Room description') }}" cols="30" rows="10"></textarea>
</div>
</div>
</div>
</div>
` +
'')
$('.tagify' + roomId).tagify({
focus: true
});
roomId++;
$('.room-section-area').on('click', '.removeRoom', function() {
$(this).closest('.card').remove();
});
})

let beautyId = 2;
$('#beauty-add-btn').on('click', function() {
$('.beauty-section-area').append('' +
`<div class="card mb-4">
<div class="card-header py-3">
<div class="d-flex justify-content-between">
<div>
<h6 class="m-0 font-weight-bold text-primary">{{ __('Service') }}</h6>
</div>
<div>
<a href="javascript:;" class="removeBeauty" title="Remove"><i class="fas fa-trash-alt text-danger"></i></a>
</div>
</div>
</div>
<div class="card-body">
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Service Name') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="service_name[]" placeholder="{{ __('Enter Name') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Service Price') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="number" class="input-field" id="service_price" name="service_price[]" placeholder="{{ __('Enter Price') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Service Photo') }}</h4>
<p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
</div>
</div>
<div class="col-lg-9">
<div class="wrapper-image-preview">
<div class="box">
<div class="back-preview-image" style="background-image: url({{ asset('assets/images/placeholder.jpg') }});"></div>
<div class="upload-options">
<label class="img-upload-label" for="service-items-${beautyId}"><i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
<input id="service-items-${beautyId}" type="file" class="image-upload" name="service_photo[]" accept="image/*">
</div>
</div>
</div>
</div>
</div>

<div class="row my-3">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Service Time') }}</h4>
</div>
</div>

<div class="col-lg-9">
<div class="row">
<div class="col-md-4">
<input type="text" class="input-field" name="service_from[]" placeholder="{{ __('Enter time') }}" value="">
</div>

<div class="col-md-5">
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('To') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="service_to[]" placeholder="{{ __('Enter time') }}" value="">
</div>
</div>
</div>
</div>
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Service Duration') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="number" class="input-field" name="service_duration[]" placeholder="{{ __('Enter Duration') }}" value="">
</div>
</div>
</div>
</div>` +
'')
beautyId++;
$('.beauty-section-area').on('click', '.removeBeauty', function() {
$(this).closest('.card').remove();
});
})

$("#faq-add-btn").on('click', function() {
$('.faq-section-area').append('' +
`<div class="card mb-4">
<div class="card-header py-3">
<div class="d-flex justify-content-between">
<div>
<h6 class="m-0 font-weight-bold text-primary">{{ __('FAQ Items') }}</h6>
</div>
<div>
<a href="javascript:;" class="removeFaq" title="Remove"><i class="fas fa-trash-alt text-danger"></i></a>
</div>
</div>
</div>
<div class="card-body">
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Name') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="faq_name[]" placeholder="{{ __('Enter Name') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Description') }}</h4>
</div>
</div>
<div class="col-lg-9">
<textarea name="faq_details[]" class="input-field" placeholder="{{ __('Description') }}" cols="30" rows="10"></textarea>
</div>
</div>

</div>
</div>` +
'')

$('.faq-section-area').on('click', '.removeFaq', function() {
$(this).closest('.card').remove();
});
})
</script>
<script>
$(document).ready(function() {
$('#service_price').on('input', function() {
var inputValue = $(this).val();
var sanitizedValue = inputValue.replace(/[^0-9_\-]/g, '');
$(this).val(sanitizedValue);
});
});

$(document).ready(function() {
$('#menu_price').on('input', function() {
var inputValue = $(this).val();
var sanitizedValue = inputValue.replace(/[^0-9_\-]/g, '');
$(this).val(sanitizedValue);
});
});
</script>
@endpush
