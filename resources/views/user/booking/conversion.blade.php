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
{{-- <h1 class="ft-medium">@lang('Booking Conversation')</h1> --}}
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
</li>
<li class="breadcrumb-item text-muted"><a
href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<div class="dashboard-wrap px-4 pt-3 pb-5 bg-light">
<a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false"
aria-controls="MobNav">
<i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
</a>
<div class="collapse" id="MobNav">
@includeIf('partials.user.sidebar')
</div>

<div class="dashboard-content">
<div class="dashboard-widg-bar d-block">
<div class="row">
<div class="col-xl-8 col-lg-4">
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<div class="dashboard-list-wraps-head br-bottom py-3 px-3">
<div class="dashboard-list-wraps-flx">
<h4 class="mb-0 ft-medium fs-md"><i
class="fas fa-shopping-basket me-2 theme-cl fs-sm"></i>@lang('Conversation')</h4>
</div>
</div>

<div class="dashboard-list-wraps-body py-3 px-3">
<div class="dashboard-bookings-wraps">
<div class="messages-container-inner">

<!-- Message Content -->
<div class="dash-msg-content">
@if (count($messages) > 0)
@foreach ($messages as $key => $data)
@if ($data->user_id == 0)
<div class="message-plunch">
<div class="dash-msg-avatar">
@if ($data->admin_id != null)
<img src="{{ asset('assets/images/' . $data->admin->photo) }}"
alt="">
@else
<img src="{{ asset('assets/images/' . $data->owner->photo) }}"
alt="">
@endif
</div>
<div class="dash-msg-text">
<p>{{ $data->message }}
<br>
@if ($data->photo != null)
<a href="{{ asset('assets/images/' . $data->photo) }}"
download=""><i class="fas fa-paperclip"></i>
@lang('attachment')-{{ $key += 1 }}</a>
@endif
</p>
</div>
</div>
@else
<div class="message-plunch me">
<div class="dash-msg-avatar"><img
src="{{ asset('assets/images/' . $data->user->photo) }}"
alt=""></div>
<div class="dash-msg-text">
<p>{{ $data->message }}
<br>
@if ($data->photo != null)
<a href="{{ asset('assets/images/' . $data->photo) }}"
download=""><i class="fas fa-paperclip"></i>
@lang('attachment')-{{ $key += 1 }}</a>
@endif
</p>
</div>
</div>
@endif
@endforeach
@else
<h3>@lang('No Message Found')</h3>
@endif

<!-- Reply Area -->
<div class="clearfix"></div>
<div class="message-reply">
<form
action="{{ route('user.booking.conversation.store', $conversation_id) }}"
method="post" enctype="multipart/form-data">
@csrf
<input type="file" name="photo" id="" class="my-2">
<textarea cols="40" rows="3" name="message" class="form-control with-light" placeholder="@lang('Your Message')"></textarea>
<button type="submit"
class="btn theme-bg text-white">@lang('Send Message')</button>
</form>
</div>

</div>
<!-- Message Content -->

</div>

</div>
</div>
</div>
</div>

<div class="col-xl-4 col-md-4 col-lg-4">
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<div class="dashboard-list-wraps-head br-bottom py-3 px-3">
<div class="dashboard-list-wraps-flx">
<h4 class="mb-0 ft-medium fs-md"><i
class="fas fa-shopping-basket me-2 theme-cl fs-sm"></i>@lang('Directory')</h4>
</div>
</div>
</div>

<div class="dashboard-list-wraps-body py-3 px-3">
<div class="dashboard-bookings-wraps">
<div class="Rego-grid-wrap card placeholder-wave">
<div class="Rego-grid-upper">
<div class="Rego-grid-thumb">
<a href="{{ route('front.listing.details', $listing->slug) }}"
class="d-block text-center m-auto"><img
src="{{ asset('assets/images/' . $listing->photo) }}" class="img-fluid"
alt=""></a>
</div>
</div>
<div class="Rego-grid-fl-wrap">
<div class="Rego-caption px-3 py-2">
<div class="Rego-author">
@if ($listing->user_id == null && $listing->admin_id == null)
<a href="{{ route('front.author.details', 'admin') }}">
<img src="{{ asset('assets/images/' . getAdmin()->photo) }}"
class="img-fluid circle placeholder" alt="">
</a>
@else
<a href="{{ route('front.author.details', $listing->user->name) }}">
<img src="{{ asset('assets/images/' . $listing->user->photo) }}"
class="img-fluid circle placeholder" alt="">
</a>
@endif
</div>
<div class="Rego-cates placeholder"><a
href="{{ route('front.listing', ['category' => $listing->category->slug]) }}">{{ $listing->category->title }}</a>
</div>
<div class="mb-0 ft-medium medium"><a
href="{{ route('front.listing.details', $listing->slug) }}"
class="text-dark fs-md placeholder">{{ $listing->name }}<span
class="verified-badge"><i
class="fas fa-check-circle"></i></span></a></div>
<div class="Rego-middle-caption mt-3">
<div class="Rego-location placeholder"><i
class="fas fa-map-marker-alt"></i>{{ $listing->real_address }}
</div>
<div class="Rego-call placeholder"><i
class="fas fa-phone-alt"></i>{{ $listing->phone_number }}</div>
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

<div class="modal modal-blur fade" id="confirm-status" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-status bg-danger"></div>
<div class="modal-body text-center py-4">
<h3>{{ __('Are you sure') }}?</h3>
<div class="text-muted">{{ __('You are about to change the status') }}</div>
</div>
<div class="modal-footer">
<div class="w-100">
<div class="d-flex justify-content-center gap-10px">
<a href="#" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">
{{ __('Cancel') }}
</a>

<a href="javascript:;" class="btn btn-success w-100 btn-ok text-white">
{{ __('Apply') }}
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

$('#confirm-status').on('show.bs.modal', function(e) {
$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});
</script>
@endpush
