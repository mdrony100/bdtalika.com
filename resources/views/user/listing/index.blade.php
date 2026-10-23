@extends('layouts.front')

@push('css')
<style>
.btn.theme-bg.rounded.text-light {background: #1768E0 !important;padding: 7px 18px;font-size: 14px;}.agd-location {max-width: 700px !important;display: block;}
@media screen and (max-width:575px){.dsd-single-listing-wraps {display: block;}.dsd-single-lst-caption{padding-left:0;}.dsd-single-lst-thumb{margin-bottom:20px;}}
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
{{-- <h1 class="ft-medium">@lang('Manage Listings')</h1> --}}
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
</li>
<li class="breadcrumb-item"><a href="#" class="">@lang('Manage Listings')</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<div class="dashboard-wrap bg-light px-2 px-lg-4 pt-3 pb-5">
<a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false"
aria-controls="MobNav">
<i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
</a>
<div class="collapse" id="MobNav">
@includeIf('partials.user.sidebar')
</div>

<div class="dashboard-content">
<div class="">

<div class="dashboard-widg-bar d-block">
<div class="row">
<div class="col-xl-12 col-lg-12">
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<div class="dashboard-list-wraps-head br-bottom py-3 px-3">
<div class="dashboard-list-wraps-flx">
<div class="d-flex justify-content-between align-items-center">
<h4 class="mb-0 ft-medium fs-md">
<i class="fa fa-file-alt me-2 theme-cl fs-sm"></i>
@lang('My Listings')
</h4>
<a href="{{ route('user.listing.create') }}" class="btn theme-bg rounded text-light">Add New</a>
</div>
</div>
</div> 
<div class="dashboard-list-wraps-body py-3 px-3">
<div class="dashboard-listing-wraps">
@forelse ($listings as $key => $data)
@include('user.listing.partials.card', ['data' => $data])
@empty
<div class="text-center mt-4">
<img src="{{ asset('assets/images/no-data.png') }}" class="img-fluid" alt="">
</div>
@endforelse

{{-- paginate --}}
<div class="paginate">
{{ $listings->links() }}
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- footer -->
{{-- <div class="row">
<div class="col-md-12">
<div class="py-3">
@php
echo $gs->copyright;
@endphp
</div>
</div>
</div> --}}

</div>

</div>
<!-- ======================= dashboard Detail End ======================== -->

<div class="modal modal-blur fade" id="confirm-delete" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-status bg-danger"></div>
<div class="modal-body text-center py-4">
<h3>{{ __('Are you sure') }}?</h3>
<div class="text-muted">{{ __('You are about to delete this favourite.') }}</div>
</div>
<div class="modal-footer">
<div class="w-100">
<div class="d-flex justify-content-center gap-10px">
<a href="#" class="btn btn-outline-info w-100" data-bs-dismiss="modal">{{ __('Cancel') }}</a>
<a href="javascript:;" class="btn btn-danger w-100 btn-ok text-white">{{ __('Delete') }}</a>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">Listing Rejected Details</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-3">
<div id="datahtml"></div>
</div>
</div>
</div>
</div>
@endsection

@push('js')
<script>
'use strict';

$('#confirm-delete').on('show.bs.modal', function(e) {
$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>
<script>
$(document).ready(function() {
$("body").on('click', '.rejectView', function() {
let url = $(this).attr('href');
$.ajax({
url: url,
type: "GET",
success: function(data) {
$('#datahtml').text(data.reject_reason);
}
})
});
});
</script>
@endpush
