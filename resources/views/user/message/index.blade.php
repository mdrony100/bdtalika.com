@extends('layouts.front')
@section('content')
<div class="bg_light px-4 py-2">
<div class="dashboard-tlbar d-block">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
</li>
<li class="breadcrumb-item text-muted"><a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<div class="dashboard-wrap px-4 pt-3 pb-5 bg-light">
<a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false" aria-controls="MobNav">
<i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
</a>
<div class="collapse" id="MobNav">
@includeIf('partials.user.sidebar')
</div>
<div class="dashboard-content">
<div class="dashboard-widg-bar d-block">
<div class="row">
<div class="col-xl-12 col-lg-12 col-md-12">
<div class="_dashboard_content bg-white rounded mb-4 border">
<div class="_dashboard_content_body">
<div class="messages-container margin-top-0">
<div class="messages-headline">
<h4>Connor Griffin</h4>
<a href="javascript:void(0)" class="message-action" data-bs-toggle="modal" data-bs-target="#ticket-modal"><i class="ti-plus"></i> @lang('Open a ticket')</a>
</div>
<div class="messages-container-inner">
<div class="dash-msg-inbox">
<ul>
@foreach ($tickets as $key => $data)
<li class="{{ request()->query('ticket') == $data->id ? 'active-message' : '' }}">
<a href="{{ route('user.message.index', ['ticket' => $data->id]) }}">
<div class="message-by">
<div class="message-by-headline">
<h5>{{ $data->ticket_number }}</h5>
<span>{{ $data->created_at->diffForHumans() }}</span>
</div>
<p>{{ $data->subject }}</p>
</div>
</a>
</li>
@endforeach
</ul>
</div>
<div class="dash-msg-content">
@if (count($messages) > 0)
@foreach ($messages as $key => $data)
@if ($data->user_id == 0)
<div class="message-plunch">
<div class="dash-msg-avatar"><img src="{{ asset('assets/images/' . getAdmin()->photo) }}" alt=""></div>
<div class="dash-msg-text">
<p>{{ $data->message }}
<br>
@if ($data->photo != null)
<a href="{{ asset('assets/images/' . $data->photo) }}" download=""><i class="fas fa-paperclip"></i>
@lang('attachment')-{{ $key += 1 }}</a>
@endif
</p>
</div>
</div>
@else
<div class="message-plunch me">
<div class="dash-msg-avatar"><img src="{{ asset('assets/images/' . $user->photo) }}" alt=""></div>
<div class="dash-msg-text">
<p>{{ $data->message }}
<br>
@if ($data->photo != null)
<a href="{{ asset('assets/images/' . $data->photo) }}" download=""><i class="fas fa-paperclip"></i>
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
<div class="clearfix"></div>
<div class="message-reply">
<form action="{{ route('user.message.conversation', $ticket->id) }}" method="post" enctype="multipart/form-data">
@csrf
<input type="file" name="photo" id="">
<textarea cols="40" rows="3" name="message" class="form-control with-light" placeholder="@lang('Your Message')"></textarea>
<button type="submit" class="btn theme-bg text-white">@lang('Send Message')</button>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="modal fade" id="ticket-modal" aria-modal="true" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
<form action="{{ route('user.message.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="modal-body p-4">
<h4 class="modal-title text-center" id="withdrawModalTitle">@lang('Create a ticket')</h4>
<div class="pt-3 pb-4">
<div class="row">
<div class="col-sm-12">
<div class="form-group">
<label class="form-label required">{{ __('Subject') }}</label>
<input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" autocomplete="off" placeholder="{{ __('Enter Subject') }}" value="{{ old('subject') }}">
@error('subject')
<p class="text-danger mt-2">{{ $message }}</p>
@enderror
</div>
</div>

<div class="col-sm-12">
<div class="form-group">
<label class="form-label">{{ __('Message') }}</label>
<textarea name="message" class="form-control nic-edit @error('message') is-invalid @enderror" cols="30" rows="5" placeholder="{{ __('Enter Subject') }}"></textarea>
@error('message')
<p class="text-danger mt-2">{{ $message }}</p>
@enderror
</div>
</div>
<div class="col-sm-12">
<div class="form-group">
<input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror">
@error('attachment')
<p class="text-danger mt-2">{{ $message }}</p>
@enderror
</div>
</div>
</div>
</div>
<div class="d-flex">
<button type="button" class="btn shadow-none btn-danger me-2 w-50" data-bs-dismiss="modal">@lang('Close')</button>
<button type="submit" class="btn shadow-none btn-success w-50">@lang('Send')</button>
</div>
</div>
</form>
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
