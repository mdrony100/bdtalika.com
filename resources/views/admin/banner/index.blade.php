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
<h5 class=" mb-0 text-gray-800 pl-3">{{ __('Manage Banner') }}</h5>
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">{{ __('Banner') }}</a></li>
</ol>
</div>
</div>


<div class="row mt-3">
<div class="col-lg-12">
@include('includes.admin.form-success')
<div class="card mb-4">
<div class="card-header">
<div class=""></div>
</div>
<div class="table-responsive p-3">
<table id="dataTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
<thead class="thead-light">
<tr>
<th>{{ __('Image') }}</th>
<th>{{ __('Link') }}</th>
<th>{{ __('Views') }}</th>
<th>{{ __('Status') }}</th>
<th style="width:15%;">{{ __('Actions') }}</th>
</tr>
</thead>
</table>
</div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script>
"use strict";
var baseUrl = "{{ asset('assets/images') }}/";
var table = $('#dataTable').DataTable({
ordering: false,
processing: true,
serverSide: true,
searching: true,
ajax: "{{ route('admin.banner.index') }}",
columns: [{
data: 'image',
name: 'image',
render: function(data, type, full, meta) {
return '<img src="' + baseUrl + data + '" height="100">';
}
},
{
data: 'link',
name: 'link'
},
{
data: 'views',
name: 'views'
},
{
data: 'status',
name: 'status',
render: function(data, type, row) {
if (data == 1) {
return '<span class="badge badge-success">Active</span>';
} else {
return '<span class="badge badge-danger">Inactive</span>';
}
}

},
{
data: 'action',
searchable: false,
orderable: false
}

]
});
$(function() {
$(".btn-area").append('<div class="col-sm-12 col-md-4 pr-3 text-right">'+
'<a class="btn btn-primary" href="{{route('admin.banner.create')}}">'+
'<i class="fas fa-plus"></i> {{__('Add New')}}'+
'</a>'+
'</div>');
});
</script>
@endsection
