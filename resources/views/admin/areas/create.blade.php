@extends('layouts.admin')
@section('area', 'active')
@section('content')
<div class="card">
<div class="d-sm-flex align-items-center justify-content-between">
<h5 class=" mb-0 text-gray-800 pl-3">{{ __('Add New Areas') }} <a class="btn btn-primary btn-rounded btn-sm"
href="{{ route('admin.areas.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.areas.create') }}">{{ __('Add New Areas') }}</a></li>
</ol>
</div>
</div>

<div class="row justify-content-center mt-3">
<div class="col-md-10">
<!-- Form Basic -->
<div class="card mb-4">
<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
<h6 class="m-0 font-weight-bold text-primary">{{ __('Add New City Form') }}</h6>
</div>

<div class="card-body">
<div class="gocover"
style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
</div>
<form class="geniusform" action="{{ route('admin.areas.store') }}" method="POST"
enctype="multipart/form-data">

@include('includes.admin.form-both')

{{ csrf_field() }}

<div class="form-group">
<label for="inp-name">{{ __('Division') }}</label>
<select class="form-control mb-3" name="division_id" id="division_id" required>
<option value="" class="d-none">{{ __('Select Division') }}</option>
@foreach ($divisions as $key => $division)
<option value="{{ $division->id }}">{{ $division->name }}</option>
@endforeach
</select>
</div>

<div class="form-group">
<label for="inp-name">{{ __('City') }}</label>
<select class="form-control mb-3" name="city_id" required >
<option value="" selected>{{ __('Select City') }}</option>

</select>
</div>

<div class="form-group">
<label for="inp-name">{{ __('Name') }}</label>
<input type="text" class="form-control" id="inp-name" name="title"
placeholder="{{ __('Enter Name') }}" value="" required>
</div>

<div class="form-group">
<label for="order_number">{{ __('Order Number') }}</label>
<input type="text" class="form-control" id="order_number" name="order_number"
placeholder="{{ __('Enter Order Number') }}">
</div>

<div class="form-group">
<label>{{ __('Area thumbnail') }} </label>
<div class="wrapper-image-preview">
<div class="box">
<div class="back-preview-image"
style="background-image: url({{ asset('assets/images/default.png') }});"></div>
<div class="upload-options">
<label class="img-upload-label" for="img-upload"> <i class="fas fa-camera"></i>
{{ __('Upload Picture') }} </label>
<input id="img-upload" type="file" class="image-upload" name="photo"
accept="image/*">
</div>
</div>
</div>
</div>

<div class="form-group">
<label for="meta_title">{{ __('Meta Title') }}</label>
<input type="text" class="form-control" id="meta_title" name="meta_title"
placeholder="{{ __('Enter Meta Title') }}" value="" required>
</div>

<div class="form-group">
<label for="inp-slug">{{ __('Meta Description') }}</label>
<textarea name="meta_description" id="meta_description" class="form-control" cols="30" rows="5"  placeholder="Enter Meta Description" required></textarea>
</div>



<button type="submit" id="submit-btn" class="btn btn-primary w-100">{{ __('Submit') }}</button>

</form>
</div>
</div>
</div>

</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
$(document).on('change', '#division_id', function() {
let id = $(this).val();
if (id !== "") {
$.ajax({
url: "{{ route('admin.division.to.city') }}/" + id,
type: "GET",
dataType: "json",
success: function(data) {
$('select[name="city_id"]').empty();
$.each(data, function(key, value) {
$('select[name="city_id"]').append(
'<option value="" class="d-none">Select</option>' +
'<option value="' + value.id + '">' + value
.name + '</option>'
);

});
}
});
} else {
$('select[name="city_id"]').empty();
}
});
});
</script>
@endsection
