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
<h5 class=" mb-0 text-gray-800 pl-3">{{ __('Add New Banner') }}</h5>
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('Add New Banner') }}</a></li>
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
<div class="card-title m-0">Add New Banner</div>
</div>
<div class="">
<a href="{{ route('admin.banner.index') }}" class="btn btn-sm btn-primary">Back</a>
</div>
</div>
</div>
<div class="card-body">
<form action="{{ route('admin.banner.store') }}" method="post" enctype="multipart/form-data">
@csrf
<div class="row d-flex justify-content-center">
<div class="col-xl-7 pb-4">
<div class="form-group">
<label for="" class="form-label">Image <span class="text-danger">*</span></label>
<input type="file" name="image" id="image" class="form-control" required>
</div>
<div class="form-group">
<label for="" class="form-label">Link <span class="text-danger">*</span></label>
<input type="text" name="link" id="link" class="form-control" required>
</div>
<div class="form-group">
<label for="" class="form-label">Status <span class="text-danger">*</span></label>
<select name="status" id="status" class="form-control">
<option value="1">Active</option>
<option value="0">Inactive</option>
</select>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
@endsection

