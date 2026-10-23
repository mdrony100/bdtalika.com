@extends('layouts.admin')
@section('styles')
<style>
td {
vertical-align: middle !important;
}
</style>
@endsection
@section('content')
<div class="card">
<div class="d-sm-flex align-items-center justify-content-between">
<h5 class=" mb-0 text-gray-800 pl-3">{{ __('Edit Banner') }}</h5>
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('Edit Banner') }}</a></li>
</ol>
</div>
</div>


<div class="row mt-3">
<div class="col-lg-12">
@include('includes.admin.form-success')
<div class="card mb-4">
<div class="card-header border-bottom">
<div class="d-flex  justify-content-between">
<div class="">
<div class="card-title m-0">Edit Banner</div>
</div>
<div class="">
<a href="{{ route('admin.banner.index') }}" class="btn btn-sm btn-primary">Back</a>
</div>
</div>
</div>
<div class="card-body">
<form action="{{ route('admin.banner.update', $data->id) }}" method="post" enctype="multipart/form-data">
@csrf
<div class="row d-flex justify-content-center">
<div class="col-xl-7 pb-4">
<div class="form-group">
<img src="{{ asset('assets/images/'.$data->image) }}" width="120" alt="Banner Image">
</div>
<div class="form-group">
<label for="" class="form-label">Image</label>
<input type="file" name="image" id="image" class="form-control">
</div>
<div class="form-group">
<label for="" class="form-label">Link <span class="text-danger">*</span></label>
<input type="text" name="link" id="link" value="{{ $data->link }}" class="form-control" required>
</div>
<div class="form-group">
<label for="" class="form-label">Status <span class="text-danger">*</span></label>
<select name="status" id="status" class="form-control">
<option value="1" {{$data->status == 1 ? 'selected' : "" }}>Active</option>
<option value="0" {{$data->status == 0 ? 'selected' : "" }}>Inactive</option>
</select>
</div>
<button type="submit" class="btn btn-primary">Update</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
@endsection

