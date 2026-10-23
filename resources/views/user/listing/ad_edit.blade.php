@extends('layouts.front')
@section('stepOne', 'active')
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
.replace_photo {z-index: ;background: #fffffff5;width: 100%;text-align: center;position: absolute;bottom: 0;padding: 4px 0px;height: 26%;display: flex;justify-content: center;align-items: center;color: #1565c0;transition: all 0.4s ease;-webkit-transition: all 0.4s ease;-moz-transition: all 0.4s ease;-ms-transition: all 0.4s ease;}


</style>
@endpush
@section('content')
<div class="bg_light px-4 py-2">
<div class="dashboard-tlbar d-block">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
</li>
<li class="breadcrumb-item"><a href="javascript:void(0)" class="">@lang('Create Listing')</a>
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
<form class="listingForm" id="needs-validation" action="{{ route('user.listing.update.step1', $data->id) }}" method="POST" enctype="multipart/form-data" novalidate>
{{ csrf_field() }}
{{-- tab menu --}}
<div class="tab-menu">
@include('user.listing.tabs_menu')
</div>
<div class="dashboard-list-wraps bg-white rounded border">
<div class="dashboard-list-wraps-body py-4 px-4">
<div class="row gy-3">
{{-- title and slug --}}
<div class="col-12">
<div class="form-group">
<div class="d-flex align-items-center justify-content-between">
<label class="form-label">
@lang('Listing title')
<span class="text-danger">*</span>
<span class="limit_text text-danger"></span>
</label>
<div class="title_limit text-muted">0/70</div>
</div>
<input type="text" name="name" id="validationCustom01" class="form-control rounded" value="{{ $data->name }}" placeholder="@lang('Listing title')">
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
{{ $type->id == $data->type ? 'selected' : '' }}>
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
{{ __('Please select a category') }}</option>
@foreach ($categories as $key => $category)
<option value="{{ $category->id }}"
{{ $data->category_id == $category->id ? 'selected' : '' }}>
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
{{ $data->division_id == $division->id ? 'selected' : '' }}>
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
@foreach ($cities as $city)
<option value="{{ $city->id }}"
{{ $data->city_id == $city->id ? 'selected' : '' }}>
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
<option value="" class="d-none">Select Area</option>
@foreach ($areas as $area)
<option value="{{ $area->id }}"
{{ $data->area_id == $area->id ? 'selected' : '' }}>
{{ $area->title }}</option>
@endforeach
</select>
<div id="error-container"></div>
</div>
</div>
{{-- Thubnail and images --}}
<label for="" class="form-label">Add up to 5 photos</label> <br>
<div class="custom_image m-0">
<div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
<div class="col">
<div class="wrap-custom-file">
<input type="file" name="photo" id="photo" accept=".jpg, .png, .jpeg">
@if ($data->photo)
<label for="photo" class="file-label file-ok" style="background-image: url('{{ asset('assets/images/' . $data->photo) }}');">
<span>Add a photo</span>
<button class="remove-btn" type="button" style="display: none;">
<i class="lni lni-trash-can"></i>
</button>
</label>
@else
<label for="photo" class="file-label" style="background-image: none;">
<span>Add a photo</span>
<button class="remove-btn" type="button" style="display: none;">
<i class="lni lni-trash-can"></i>
</button>
</label>
@endif
</div>

{{-- <div class="wrap-custom-file">
<input type="file" name="photo" id="photo"
accept=".jpg, .png, .jpeg">
<label for="photo" class="file-label file-ok" style="background-image: url('{{ asset('assets/images/' . $data->photo) }}');">
</label>
<span class="replace_photo" for="photo">Replace photo</span>
</div> --}}
</div>
@for ($i = 0; $i < 4; $i++)
@if (isset($galleries[$i]))
<div class="col">
<div class="wrap-custom-file">
<input type="file" disabled name="gallery[]" style="cursor: no-drop;" id="images{{ $i + 2 }}" accept=".jpg, .png, .jpeg">
<label for="images{{ $i + 2 }}" class="file-label {{ $galleries[$i]->photo ? 'file-ok' : '' }}" style="background-image: url('{{ asset('assets/images/' . $galleries[$i]->photo) }}');">
<span>Add a photo</span>
<a href="{{ route('user.listing.gallery.delete', $galleries[$i]->id) }}" class="remove_gallery" onclick="return confirm('Are you sure to Delete Photo?')" style="display: {{ $galleries[$i]->photo ? '' : 'none' }};">
<i class="lni lni-trash-can"></i>
</a>
</label>
</div>
</div>
@else
<div class="col">
<div class="wrap-custom-file">
<input type="file" name="gallery[]" id="images{{ $i + 2 }}" accept=".jpg, .png, .jpeg">
<label for="images{{ $i + 2 }}" class="file-label">
<span>Add a photo</span>
<button class="remove-btn" type="button" style="display: none;">
<i class="lni lni-trash-can"></i>
</button>
</label>
</div>
</div>
@endif
@endfor
</div>
<div id="error-photo"></div>
</div> 

{{-- <p class="m-0 pt-2">You must upload at least one photo</p> --}}

{{-- description --}}

<div class="col-12">
<div class="form-group">
<div class="d-flex align-items-center justify-content-between">
<label class="form-label">
@lang('Description')
<span class="text-danger">*</span>
</label>
<div class="desc_limit text-muted">0/5000</div>
</div>
<textarea class="form-control rounded summernote" rows="4" id="descriptionField" name="description" placeholder="@lang('Description')">{!! $data->description !!}</textarea>
<span class="text-danger desc_alert"></span>
</div>
</div>

{{-- Contact Info --}}
<h4 class="mb-0 ft-medium fs-md">
<i class="fa fa-file me-2 theme-cl fs-sm"></i>@lang('Contact Info')
</h4>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Real address')</label>
<input type="text" class="form-control rounded" value="{{ $data->real_address }}" name="real_address" placeholder="{{ __('Address') }}">
</div>
@error('real_address')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Email')</label>
<input type="text" name="email" class="form-control rounded" value="{{ $data->email }}" placeholder="@lang('Email')">
</div>
@error('email')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
 <div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Website')</label>
<input type="text" name="website" class="form-control rounded" value="{{ $data->website }}" placeholder="@lang('Website')">
</div>
@error('website')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Listing Video Url')</label>
<input type="text" class="form-control rounded" value="{{ $data->listing_video }}" name="listing_video" placeholder="https://www.youtube.com/watch?v=AXrHbrMrun0">
</div>
@error('listing_video')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Phone number')</label>
<input type="text" name="phone_number" class="form-control rounded" value="{{ $data->phone_number }}" placeholder="@lang('Phone number')">
</div>
@error('phone_number')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">Whatsapp Number</label>
<input type="text" name="whatsapp_number" class="form-control rounded" value="{{ $data->whatsapp_number }}" placeholder="Whatsapp Number">
</div>
@error('whatsapp_number')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-12">
<div class="form-group">
<label class="form-label">@lang('Map Location (iframe code)')</label>
<textarea class="form-control rounded" rows="5" name="map_location" placeholder="@lang('Location')">{{ $data->map_location }}</textarea>
</div>
</div> 
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Facebook')</label>
<input type="text" name="facebook" class="form-control rounded" value="{{ $data->facebook }}" placeholder="@lang('Facebook')">
</div>
@error('facebook')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Twitter')</label>
<input type="text" name="twitter" class="form-control rounded" value="{{ $data->twitter }}" placeholder="@lang('Twitter')">
</div>
@error('twitter')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Linkedin')</label>
<input type="text" name="linkedin" class="form-control rounded" value="{{ $data->linkedin }}" placeholder="@lang('Linkedin')">
</div>
@error('linkedin')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Youtube')</label>
<input type="text" name="youtube" class="form-control rounded" value="{{ $data->youtube }}" placeholder="@lang('Youtube')">
</div>
@error('youtube')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
<div class="col-md-6">
<div class="form-group">
<label class="form-label">@lang('Instagram')</label>
<input type="text" name="instagram" class="form-control rounded" value="{{ $data->instagram }}" placeholder="@lang('Instagram')">
</div>
@error('instagram')
<div class="text-danger">{{ $message }}</div>
@enderror
</div>
</div>
</div>
</div>
<div class="card-footer border-0 text-center pt-3 pb-3 bg-white">
<input type="hidden" id="cancel_edit_input" name="cancel_edit" value="0">
<div>
{{-- <div id="loadingBtn">
<button type="button" onclick="updateCancelEdit()" id="saveand"
class="btn rounded text-light btn-success loadingClass me-3"
style="padding:9px 18px !important;">
Save & Cancel Edit
</button>
</div> --}}
<button type="submit" class="btn theme-bg rounded  text-light">Save & Next</button>
</div>
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
</script>

{{-- form validation --}}
<script>
$('form[id="needs-validation"]').validate({
rules: {
name: 'required',
category_id: 'required',
division_id: 'required',
city_id: 'required',
// area_id: 'required',
description: 'required'
},
messages: {
name: 'This title field is required',
category_id: 'This category field is required',
division_id: 'This division field is required',
city_id: 'This city field is required',
// area_id: 'This area field is required',
description: 'This description field is required',
},
errorPlacement: function(error, element) {
if (element.attr('name') === 'area_id') {
error.appendTo('#error-container');
$('#area_id').next('.select2-container').find('.select2-selection').addClass('invalid');
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
console.log('ok');
$file.val('');
$label.css('background-image', 'none');
$label.removeClass('file-ok');
$labelText.text(labelDefault);
$removeBtn.hide();
});
// if (index < $('input[type="file"]').length - 1) {
//     $('input[type="file"]').eq(index + 1).prop('disabled', false);
// }
} else {
$label.removeClass('file-ok');
$labelText.text(labelDefault);
$removeBtn.hide();
// $('input[type="file"]').slice(index + 1).val('').prop('disabled', true);
}
});
});

$('.remove-btn').on('click', function() {
var $wrapCustomFile = $(this).closest('.wrap-custom-file');
var $fileInput = $wrapCustomFile.find('input[type="file"]');
var $label = $wrapCustomFile.find('.file-label');
var $labelText = $label.find('span');
var $removeBtn = $label.find('.remove-btn');

$fileInput.val('');
$label.css('background-image', 'none');
$label.removeClass('file-ok');
$labelText.text('Add a photo');
$removeBtn.hide();
});

function updateCancelEdit() {
$('#cancel_edit_input').val(1);
$('.listingForm').submit();
}
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
