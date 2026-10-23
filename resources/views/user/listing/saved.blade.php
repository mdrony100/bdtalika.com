@extends('layouts.front')

@push('css')
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
{{-- <h1 class="ft-medium">@lang('Saved Listings')</h1> --}}
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('user.listing.index') }}" class="">@lang('Manage Listings')</a></li>
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
<div class="">


<div class="dashboard-widg-bar d-block">
<div class="row">
<div class="col-xl-12 col-lg-12">
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<div class="dashboard-list-wraps-head br-bottom py-3 px-3">
<div class="dashboard-list-wraps-flx">
<h4 class="mb-0 ft-medium fs-md"><i class="fa fa-file-alt me-2 theme-cl fs-sm"></i>@lang('Saved Listing')</h4>
</div>
</div>

<div class="dashboard-list-wraps-body py-3 px-3">
<div class="dashboard-listing-wraps">
@if (isset($wishlists) && $wishlists->count() > 0)
@foreach ($wishlists as $key => $data)
<!-- Single Listing Item -->
<div class="dsd-single-listing-wraps">
<div class="dsd-single-lst-thumb">
<img src="{{ asset('assets/images/' . ($data->listing->photo ?? 'default.png')) }}" class="img-fluid" alt="">
</div>
<div class="dsd-single-lst-caption">
<div class="dsd-single-lst-title">
<h5>{{ $data->listing->name }}</h5>
</div>
<span class="agd-location"><i class="lni lni-map-marker me-1"></i>{{ $data->listing->real_address }}</span>

@if (count($data->listing->reviews) > 0)
<div class="ico-content">
<div class="Rego-ft-first">
<div class="Rego-rating">
<div class="Rego-rates">
@php
$rate = ceil($data->listing->directoryRatting($data->listing->id));
@endphp

@for ($i = 1; $i <= $rate; $i++)
<i class="fas fa-star active"></i>
@endfor

@for ($i = 1; $i <= 5 - $rate; $i++)
<i class="fas fa-star"></i>
@endfor
</div>
</div>
<div class="Rego-price-range">
<span class="ft-medium">{{ $data->listing->directoryRatting($data->listing->id) }}
@lang('Reviews')</span>
</div>
</div>
</div>
@endif

<div class="dsd-single-lst-footer">
<a href="{{ route('front.listing.details', $data->listing->slug) }}" class="btn btn-view mr-1"><i class="fas fa-eye me-1"></i>@lang('View')</a>
<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="{{ route('user.saved.listing.delete', $data->id) }}" class="btn btn-delete">
<i class="fas fa-trash me-1"></i>
@lang('Delete')
</a>
</div>
</div>
</div>
@endforeach
@else
<div class="text-center mt-4">
<img src="{{ asset('assets/images/no-data.png') }}" class="img-fluid" alt="">
</div>
@endif
</div>

<div class="row justify-content-center">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="position-relative text-center">
@if ($wishlists->hasPages())
{{ $wishlists->links() }}
@endif
</div>
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
<a href="#" class="btn btn-outline-info w-100" data-bs-dismiss="modal">
{{ __('Cancel') }}
</a>

<a href="javascript:;" class="btn btn-danger w-100 btn-ok text-white">
{{ __('Delete') }}
</a>

</div>
</div>
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
@endpush
