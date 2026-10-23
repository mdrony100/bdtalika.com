@extends('layouts.admin')

@section('content')
<style>
    #geniustable_wrapper img{
        width: 120px;
    }
</style>

<div class="card">
<div class="d-sm-flex align-items-center justify-content-between">
<h5 class=" mb-0 text-gray-800 pl-3">{{ __('Posts') }}</h5>
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="javascript:;">{{ __('Blog') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">{{ __('Posts') }}</a></li>
</ol>
</div>
</div>

<div class="row mt-3">
<div class="col-lg-12">
{{-- @include('includes.admin.form-success') --}}

<div class="card mb-4">
<div class="table-responsive p-3">
<div class="form-group d-flex align-items-center mb-3" style="max-width: 280px;">
<label for="status-filter" class="mb-0 mr-2 font-weight-bold">{{ __('Filter') }}:</label>
<select id="status-filter" class="form-control">
<option value="">{{ __('All Posts') }}</option>
<option value="1">{{ __('Published') }}</option>
<option value="0">{{ __('Draft') }}</option>
</select>
</div>
<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
<thead class="thead-light">
<tr>
<th>{{ __('Featured Image') }}</th>
<th>{{ __('Post Title') }}</th>
<th>{{ __('Status') }}</th>
<th>{{ __('Views') }}</th>
<th>{{ __('Options') }}</th>
</tr>
</thead>
</table>
</div>
</div>
</div>
</div>


@includeIf('partials.admin.status')
<div class="modal fade confirm-modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">{{ __("Confirm Delete") }}</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<p class="text-center">{{__("You are about to delete this Blog. Every informtation under this blog will be deleted.")}}</p>
<p class="text-center">{{ __("Do you want to proceed?") }}</p>
</div>
<div class="modal-footer">
<a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __("Cancel") }}</a>
<a href="javascript:;" class="btn btn-danger btn-ok">{{ __("Delete") }}</a>
</div>
</div>
</div>
</div>
@endsection



@section('scripts')

<script>
"use strict";

var table = $('#geniustable').DataTable({
ordering: false,
processing: true,
serverSide: true,
searching: true,
ajax: {
url: '{{ route('admin.blog.datatables') }}',
data: function (d) {
d.status = $('#status-filter').val();
}
},
columns: [

{ data: 'photo', name: 'photo' , searchable: false, orderable: false},
{ data: 'title', name: 'title' },
{ data: 'status', name: 'status', searchable: false, orderable: false },
{ data: 'views', name: 'views' },
{ data: 'action', searchable: false, orderable: false }

],
language : {
processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
}
});

$('#status-filter').on('change', function () {
table.draw();
});

$(function() {
$(".btn-area").append('<div class="col-sm-12 col-md-4 pr-3 text-right">'+
'<a class="btn btn-primary" href="{{route('admin.blog.create')}}">'+
'<i class="fas fa-plus"></i> {{__('Add New Post')}}'+
'</a>'+
'</div>');
});

</script>

@endsection
