@extends('layouts.front')
@section('stepOne', 'active')
@php
$old_type = old('type');
$old_division = old('division_id');
$old_city = old('city_id');
$old_categories = DB::table('categories')->where('type_id', $old_type)->get();
$old_cities = DB::table('locations')->where('parent_id', $old_division)->get();
$old_areas = DB::table('areas')->where('city_id', $old_city)->get();
@endphp
@push('css')
<link href="{{ asset('assets/admin/css/select2.min.css') }}" rel="stylesheet">
<style>
.select2-container--default .select2-selection--single {background-color: #fff;border: 1px solid #aaa;border-radius: 3px !important;height: 37px !important;padding: 4px 8px;font-size: 15px;border-color: #dbdbdb;color: #2b2e30;}
.select2-container--default .select2-selection--single .select2-selection__arrow {top: 4px;}
.select2-container--default .select2-selection--single .select2-selection__rendered {line-height: 23px !important;}
.select2-search--dropdown .select2-search__field {outline: none !important;box-shadow: none !important;}
.select2-container {width: 100% !important;}
.invalid {border-color: #dc3545 !important;}
.error {color: #dc3545 !important;}
</style>


@endpush
@section('content')
<!-- =============================== Dashboard Header ========================== -->
{{-- @includeIf('partials.user.header') --}}
<!-- =============================== Dashboard Header ========================== -->

<!-- ======================= dashboard Detail ======================== -->
<div class="bg_light px-4 py-2">
<div class="dashboard-tlbar d-block">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
{{-- <h1 class="ft-medium">@lang('Add Listing')</h1> --}}
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
</li>
<li class="breadcrumb-item">
<a href="javascript:void(0)" class="">@lang('Create Listing')</a>
</li>
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
<form class="listingForm" id="needs-validation" action="{{ route('user.listing.store.step1') }}" method="POST" enctype="multipart/form-data" novalidate>
{{ csrf_field() }}

{{-- tab menu --}}
<div class="tab-menu">
@include('user.listing.tabs_menu')
</div>

<div class="dashboard-list-wraps bg-white rounded">
<div class="dashboard-list-wraps-body py-4 px-4 border">
<div class="row gy-3">
<div class="col-12">
<div class="form-group">
<div class="d-flex align-items-center justify-content-between">
<div class="">
<label class="form-label">@lang('Listing title')
<span class="text-danger">* (যে কোনো অবৈধ বা অনৈতিক ব্যবসা বাতিল করা হবে।)</span>
<span class="text-danger limit_text"></span>
</label>
</div>
<div class="">
<span class="title_limit text-muted">0/70</span>
</div>
</div>
<input type="text" name="name" id="validationCustom01" class="form-control rounded" value="{{ old('name') }}" placeholder="@lang('Listing title')">
</div>
@error('name')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
{{-- Type --}}
<div class="col-12">
<div class="form-group">
<label class="form-label">@lang('Business type') <span class="text-danger">*</span></label>
<select name="type" id="type_id" class="form-control select-placeholder">
<option value="" class="d-none"> {{ __('Please select a type') }}
</option>
@foreach ($listing_types as $key => $type)
<option value="{{ $type->id }}"
{{ old('type') == $type->id ? 'selected' : '' }}>
{{ $type->name }}
</option>
@endforeach
</select>
</div>
</div>
{{-- Categories --}}
<div class="col-12">
<div class="form-group">
<label class="form-label">@lang('Category') <span class="text-danger">*</span></label>
<select name="category_id" class="form-control select-placeholder">
<option value="" class="d-none">
{{ __('Please first select a type') }}
</option>
@foreach ($old_categories as $category)
<option value="{{ $category->id }}"
{{ old('category_id') == $category->id ? 'selected' : '' }}>
{{ $category->title }}
</option>
@endforeach
</select>
</div>
</div>
{{-- Division --}}
<div class="col-12">
<div class="form-group">
<label class="form-label">@lang('Division') <span class="text-danger">*</span></label>
<select name="division_id" id="division_id" class="form-control">
<option value="" class="d-none">
{{ __('Please select a division') }}</option>
@foreach ($divisions as $key => $division)
<option value="{{ $division->id }}"
{{ old('division_id') == $division->id ? 'selected' : '' }}>
{{ ucfirst($division->name) }}
</option>
@endforeach
</select>
</div>
</div>
{{-- city --}}
<div class="col-12">
<div class="form-group">
<label class="form-label">@lang('City') <span class="text-danger">*</span></label>
<select name="city_id" id="city_id" class="form-control">
<option value="" class="d-none">
{{ __('Please first select a division') }}
</option>
@foreach ($old_cities as $city)
<option value="{{ $city->id }}"
{{ old('city_id') == $city->id ? 'selected' : '' }}>
{{ $city->name }}
</option>
@endforeach
</select>
</div>
</div>
{{-- Area --}}
<div class="col-12">
<div class="form-group">
<label class="form-label">@lang('Area')</label>
<select name="area_id" id="area_id" class="form-control select2">
<option value="" class="d-none">
{{ __('Please first select a city') }}
</option>
@foreach ($old_areas as $area)
<option value="{{ $area->id }}"
{{ old('area_id') == $area->id ? 'selected' : '' }}>
{{ $area->title }}
</option>
@endforeach
</select>
<div id="error-container"></div>
</div>
</div>
{{-- Thubnail and images --}}
<label for="" class="form-label">
Add up to 5 photos
<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" title="Business with real photos get 10 times more views than without image. Image must be JPG or PNG format (max 5MB). Do not upload images with watermarks.">
<img src="{{ asset('assets/images/icon/question.svg') }}" alt="">
</span>
</label>
<br>
<div class="custom_image m-0">
<div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
<div class="col">
<div class="wrap-custom-file">
<input type="file" name="photo" id="photo" accept=".jpg, .png, .jpeg">
<label for="photo" class="file-label">
<span>Add a Thumbnail</span>
<button class="remove-btn" type="button" style="display: none;">
<i class="lni lni-trash-can"></i>
</button>
</label>
</div>
</div>
<div class="col">
<div class="wrap-custom-file">
<input type="file" name="gallery[]" id="images2" accept=".jpg, .png, .jpeg" disabled>
<label for="images2" class="file-label">
<span>Add a photo</span>
<button class="remove-btn" type="button" style="display: none;">
<i class="lni lni-trash-can"></i>
</button>
</label>
</div>
</div>
<div class="col">
<div class="wrap-custom-file">
<input type="file" name="gallery[]" id="images3" accept=".jpg, .png, .jpeg" disabled>
<label for="images3" class="file-label">
<span>Add a photo</span>
<button class="remove-btn" type="button" style="display: none;">
<i class="lni lni-trash-can"></i>
</button>
</label>
</div>
</div>
<div class="col">
<div class="wrap-custom-file">
<input type="file" name="gallery[]" id="images4" accept=".jpg, .png, .jpeg" disabled>
<label for="images4" class="file-label">
<span>Add a photo</span>
<button class="remove-btn" type="button" style="display: none;">
<i class="lni lni-trash-can"></i>
</button>
</label>
</div>
</div>
<div class="col">
<div class="wrap-custom-file">
<input type="file" name="gallery[]" id="images5" accept=".jpg, .png, .jpeg" disabled>
<label for="images5" class="file-label">
<span>Add a photo</span>
<button class="remove-btn" type="button" style="display: none;">
<i class="lni lni-trash-can"></i>
</button>
</label>
</div>
</div>
</div>
<div id="error-photo"></div>
</div>
{{-- <div class="custom_image m-0">
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

        <!-- FREE: Thumbnail -->
        <div class="col">
            <div class="wrap-custom-file">
                <input type="file" name="photo" id="photo" accept=".jpg, .png, .jpeg">
                <label for="photo" class="file-label">
                    <span>Add a Thumbnail</span>
                    <button class="remove-btn" type="button" style="display: none;">
                        <i class="lni lni-trash-can"></i>
                    </button>
                </label>
            </div>
        </div>

        <!-- PAID: Gallery Images -->
        <div class="col paid-locked">
            <div class="wrap-custom-file">
                <input type="file" name="gallery[]" id="images2" accept=".jpg, .png, .jpeg" disabled>
                <label for="images2" class="file-label">
                    <span>Add a photo</span>
                    <button class="remove-btn" type="button" style="display: none;">
                        <i class="lni lni-trash-can"></i>
                    </button>
                </label>
            </div>
        </div>

        <div class="col paid-locked">
            <div class="wrap-custom-file">
                <input type="file" name="gallery[]" id="images3" accept=".jpg, .png, .jpeg" disabled>
                <label for="images3" class="file-label">
                    <span>Add a photo</span>
                    <button class="remove-btn" type="button" style="display: none;">
                        <i class="lni lni-trash-can"></i>
                    </button>
                </label>
            </div>
        </div>

        <div class="col paid-locked">
            <div class="wrap-custom-file">
                <input type="file" name="gallery[]" id="images4" accept=".jpg, .png, .jpeg" disabled>
                <label for="images4" class="file-label">
                    <span>Add a photo</span>
                    <button class="remove-btn" type="button" style="display: none;">
                        <i class="lni lni-trash-can"></i>
                    </button>
                </label>
            </div>
        </div>

        <div class="col paid-locked">
            <div class="wrap-custom-file">
                <input type="file" name="gallery[]" id="images5" accept=".jpg, .png, .jpeg" disabled>
                <label for="images5" class="file-label">
                    <span>Add a photo</span>
                    <button class="remove-btn" type="button" style="display: none;">
                        <i class="lni lni-trash-can"></i>
                    </button>
                </label>
            </div>
        </div>

    </div>

    <<!-- small class="text-muted d-block mt-2">
        <i class="fa fa-info-circle"></i>
        থাম্বনেইল ফ্রি প্ল্যানে যুক্ত করা যাবে। একাধিক ছবি যোগ করতে পেইড প্ল্যান প্রয়োজন।
    </small> -->

    <div id="error-photo"></div>
</div> --}}

{{-- <p class="m-0 pt-2">You must upload at least one photo</p> --}}

{{-- description --}}
<div class="col-12">
<div class="form-group">
<div class="d-flex align-items-center justify-content-between">
<label class="form-label">
@lang('Description')
<span class="text-danger">*</span>
</label>
<div class="desc_limit text-muted ">0/5000</div>
</div>
<textarea id="descriptionField" class="form-control rounded summernote" rows="4" name="description" placeholder="@lang('Description')">{{ old('description') }}</textarea>
<span class="text-danger desc_alert"></span>
</div>
</div>

{{-- Contact Info --}}
<h4 class="mb-0 ft-medium fs-md">
<i class="fa fa-file me-2 theme-cl fs-sm"></i>@lang('Contact Info')
</h4>
{{-- <div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Listing Video Provider')</label>
<select name="listing_video_provider" class="form-control">
<option value="youtube">{{ __('Youtube') }}</option>
</select>
</div>
</div> --}}
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Real address')</label>
<input type="text" class="form-control rounded" name="real_address" value="{{ old('real_address') }}" placeholder="{{ __('Address') }}">
</div>
@error('real_address')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Email')</label>
<input type="text" name="email" class="form-control rounded" value="{{ old('email') }}" placeholder="@lang('Email')">
</div>
@error('email')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Listing Video Url')</label>
<input type="text" class="form-control rounded" name="listing_video" value="{{ old('listing_video') }}"
placeholder="https://www.youtube.com/watch?v=AXrHbrMrun0">
</div>
@error('listing_video')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Website')</label>
<input type="text" name="website" class="form-control rounded" value="{{ old('website') }}" placeholder="@lang('Website')">
</div>
@error('website')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Phone number')</label>
<input type="text" name="phone_number" class="form-control rounded" value="{{ old('phone_number') }}" placeholder="@lang('Phone number')">
</div>
@error('phone_number')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">Whatsapp Number</label>
<input type="text" name="whatsapp_number" class="form-control rounded" value="{{ old('whatsapp_number') }}" placeholder="Whatsapp Number">
</div>
@error('whatsapp_number')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-12">
<div class="form-group">
<label class="form-label">@lang('Map Location (iframe code)')</label>
<textarea class="form-control rounded" rows="5" name="map_location" placeholder="@lang('Location')">{{ old('map_location') }}</textarea>
</div>
</div> 
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Facebook')</label>
<input type="text" name="facebook" class="form-control rounded" value="{{ old('facebook') }}" placeholder="@lang('Facebook')">
</div>
@error('facebook')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Twitter')</label>
<input type="text" name="twitter" class="form-control rounded" value="{{ old('twitter') }}" placeholder="@lang('Twitter')">
</div>
@error('twitter')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Linkedin')</label>
<input type="text" name="linkedin" class="form-control rounded" value="{{ old('linkedin') }}" placeholder="@lang('Linkedin')">
</div>
@error('linkedin')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Youtube')</label>
<input type="text" name="youtube" class="form-control rounded" value="{{ old('youtube') }}" placeholder="@lang('Youtube')">
</div>
@error('youtube')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Instagram')</label>
<input type="text" name="instagram" class="form-control rounded" value="{{ old('instagram') }}" placeholder="@lang('Instagram')">
</div>
@error('instagram')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
</div>
</div>
</div>
<div class="card-footer border-0 text-center pt-3 pb-3 bg-white">
<button type="submit" class="btn theme-bg rounded text-light">
Save & Next
</button>
</div>
</form>
</div>
</div>
</div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
<script>
$(document).ready(function() {
$('.select2').select2();
})
// tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
<script>
'use strict';
// division to city
$(document).ready(function() {
$(document).on('change', '#division_id', function() {
let id = $(this).val();
if (id !== "") {
$.ajax({
url: "{{ route('front.division.to.city') }}/" + id,
type: "GET",
dataType: "json",
success: function(data) {
$('select[name="city_id"]').empty().append(
'<option value="" class="d-none">Select city</option>');
$('select[name="area_id"]').empty();
$.each(data, function(key, value) {
$('select[name="city_id"]').append(
'<option value="' + value.id + '">' + value
.name + '</option>'
);
});
}
});
} else {
$('select[name="city_id"]').empty();
$('select[name="city_id"]').append('<option value="" class="d-none">Select</option>');
$('select[name="area_id"]').empty();
}
});
});

// city to area
$(document).ready(function() {
$(document).on('change', '#city_id', function() {
let id = $(this).val();
if (id !== "") {
$.ajax({
url: "{{ route('front.city.to.area') }}/" + id,
type: "GET",
dataType: "json",
success: function(data) {
$('select[name="area_id"]').empty();
$('select[name="area_id"]').append(
'<option value="" class="d-none">Select area</option>');
$.each(data, function(key, value) {
$('select[name="area_id"]').append(
'<option value="' + value.id + '">' + value
.title + '</option>'
);
});
$('select[name="area_id"]').select2();
}
});
} else {
$('select[name="area_id"]').empty();
$('select[name="area_id"]').append('<option value="" class="d-none">Select</option>');
}
});
});

// type to category
$(document).ready(function() {
$(document).on('change', '#type_id', function() {
let id = $(this).val();
if (id !== "") {
$.ajax({
url: "{{ route('user.listingtype.to.category') }}/" + id,
type: "GET",
dataType: "json",
success: function(data) {
$('select[name="category_id"]').empty();
$('select[name="category_id"]').append(
'<option value="" class="d-none">Select Category</option>');
$.each(data, function(key, value) {
$('select[name="category_id"]').append(
'<option value="' + value.id + '">' + value
.title + '</option>'
);
});
$('select[name="category_id"]').select2();
}
});
} else {
$('select[name="category_id"]').empty();
}
});
});
</script>

{{-- form validation --}}
<script>
$('form[id="needs-validation"]').validate({
rules: {
name: 'required',
type: 'required',
category_id: 'required',
division_id: 'required',
city_id: 'required',
// area_id: 'required',
description: 'required',
// photo: 'required',
},
messages: {
name: 'This title field is required',
type: 'This type field is required',
category_id: 'This category field is required',
division_id: 'This division field is required',
city_id: 'This city field is required',
// area_id: 'This area field is required',
description: 'This description field is required',
// photo: 'This photo field is required',
},
errorPlacement: function(error, element) {
if (element.attr('name') === 'area_id') {
error.appendTo('#error-container');
$('#area_id').next('.select2-container').find('.select2-selection').addClass('invalid');
} else if (element.attr('name') === 'photo') {
error.appendTo('#error-photo');
} else {
element.addClass('invalid');
error.insertAfter(element);
}
},
highlight: function(element, errorClass, validClass) {
$(element).addClass('invalid').removeClass(validClass);
},
unhighlight: function(element, errorClass, validClass) {
$(element).removeClass('invalid').addClass(validClass);
},
submitHandler: function(form) {
form.submit();
}
});
$('#area_id').on('change', function() {
$('#error-container').empty();
});
</script>

<script>
// image custome input file
$('input[type="file"]').each(function(index) {
var $file = $(this),
$label = $file.next('label'),
$labelText = $label.find('span'),
labelDefault = $labelText.text(),
$removeBtn = $label.find('.remove-btn');
$file.on('change', function(event) {
var fileName = $file.val().split('\\').pop(),
tmppath = URL.createObjectURL(event.target.files[0]);
if (fileName) {
$label
.addClass('file-ok')
.css('background-image', 'url(' + tmppath + ')');
$labelText.text(fileName);
$removeBtn.show();
$removeBtn.on('click', function() {
$file.val('');
$label.css('background-image', 'none');
$label.removeClass('file-ok');
$labelText.text(labelDefault);
$removeBtn.hide();
$('input[type="file"]').slice(index + 1).val('').prop('disabled', true);
});
if (index < $('input[type="file"]').length - 1) {
$('input[type="file"]').eq(index + 1).prop('disabled', false);
}
} else {
$label.removeClass('file-ok');
$labelText.text(labelDefault);
$removeBtn.hide();
$('input[type="file"]').slice(index + 1).val('').prop('disabled', true);
}
});
});
$(document).ready(function() {
// description field text limit (summernote)
function updateDescriptionCount() {
var html = $('#descriptionField').summernote('code') || '';
var trimmedValue = $('<div>').html(html).text().replace(/\s/g, '');
var length = trimmedValue.length;
var maxLength = 5000;
if (length > maxLength) {
$('.desc_alert').text('The description information is too long');
} else {
$('.desc_alert').text('');
}
$('.desc_limit').text(length + '/5000');
}
$('#descriptionField').on('summernote.change', function() {
updateDescriptionCount();
});
updateDescriptionCount();


// name field text limit
$('input[name="name"]').on('keyup', function() {
var trimmedValue = $(this).val().replace(/\s/g, '');
var length = trimmedValue.length;
var maxLength = 70;
if (length > maxLength) {
var alertMsg = 'The listing title information is too long';
$('.limit_text').text(alertMsg);
} else {
$('.limit_text').text('');
}
$('.title_limit').text(length + '/70');
});

});
</script>
@endpush
