@extends('layouts.admin')

@section('content')
<div class="card">
<div class="d-sm-flex align-items-center justify-content-between">
<h5 class=" mb-0 text-gray-800 pl-3">{{ __('Manage Area') }}</h5>
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.areas.index') }}">{{ __('Area') }}</a></li>
</ol>
</div>
</div>


<div class="row mt-3">
<div class="col-lg-12">
@include('includes.admin.form-success')
<div class="card mb-4">
<div class="row px-3 py-3">
<div class="bulk-delete-section">
<div class="select-box-section">
<select id="bulk_option">
<option value="">@lang('Bulk Action')</option>
<option value="delete">@lang('Delete')</option>
</select>

<button class="btn btn-primary btn-sm" data-toggle="modal"
data-href="{{ route('admin.areas.bulk.delete') }}" data-target=""
id="bulk_apply">@lang('Apply')</button>
</div>
</div>
</div>
<div class="table-responsive p-3">
<table id="geniustable"  class="table table-hover dt-responsive" cellspacing="0" width="100%">
<thead class="thead-light">
<tr>
<th>
<input type="checkbox" class="" id="headercheck">
</th>
<th>{{ __('Division') }}</th>
<th>{{ __('City') }}</th>
<th>{{ __('Name') }}</th>
<th>{{ __('Order Number') }}</th>
<th>{{ __('Status') }}</th>
<th>{{ __('Options') }}</th>
</tr>
</thead>
</table>
</div>
</div>
</div>
</div>

@includeIf('partials.admin.status')
@endsection



@section('scripts')
<script>
"use strict";

var table = $('#geniustable').DataTable({
ordering: false,
processing: true,
serverSide: true,
searching: true,
ajax: "{{ route('admin.areas.datatables') }}",
columns: [{
data: 'checkbox',
name: 'checkbox'
},
{
data: 'division_id',
name: 'division_id'
},
{
data: 'city_id',
name: 'city_id'
},
{
data: 'title',
name: 'title'
},
{
data: 'order_number',
name: 'order_number'
},
{
data: 'status',
name: 'status'
},
{
data: 'action',
searchable: false,
orderable: false
}
]
});
$(function() {
$(".btn-area").append('<div class="col-sm-12 col-md-4 pr-3 text-right">' +
'<a class="btn btn-primary" href="{{ route('admin.areas.create') }}">' +
'<i class="fas fa-plus"></i> {{ __('Add New') }}' +
'</a>' +
'</div>');
});
</script>
@endsection
