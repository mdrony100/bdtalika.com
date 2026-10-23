@extends('layouts.admin')

@section('content')
<div class="card">
<div class="d-sm-flex align-items-center justify-content-between">
<h5 class=" mb-0 text-gray-800 pl-3">{{ __('Manage Hotel Booking') }}</h5>
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="javascript:void(0)">{{ __('Manage Bookings') }}</a></li>
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

<button class="btn btn-primary btn-sm" data-toggle="modal" data-href="{{ route('admin.categories.bulk.delete') }}" data-target="" id="bulk_apply">@lang('Apply')</button>
</div>
</div>
</div>
<div class="table-responsive p-3">
<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
<thead class="thead-light">
<tr>
<th>
<input type="checkbox" class="" id="headercheck">
</th>
<th>{{ __('Booking Date') }}</th>
<th>{{ __('Directory') }}</th>
<th>{{ __('Customer') }}</th>
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
<p class="text-center">{{__("You are about to delete this Beauty. Every informtation under this beauty will be deleted.")}}</p>
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
ajax: '{{ route('admin.booking.datatables',['type'=>'beauty']) }}',
columns: [
{ data: 'checkbox', name: 'checkbox' },
{ data: 'checkin_date', name: 'checkin_date' },
{ data: 'listing_id', name: 'listing_id' },
{ data: 'user_id', name: 'user_id' },
{ data: 'status', name: 'status' },
{ data: 'action', searchable: false, orderable: false }

],
language: {
processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
}
});

</script>
@endsection
