
@extends('layouts.admin')

@section('content')
<div class="card">
<div class="d-sm-flex align-items-center py-3 justify-content-between">
<ol class="breadcrumb m-0 py-0">
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="javascript:;">{{ __('Booking Conversation') }}</a></li>
</ol>
</div>
</div>


<!-- Row -->
<div class="row mt-3">
<!-- Datatables -->
<div class="col-lg-12">
<div class="order-table-wrap support-ticket-wrapper ">
<div class="panel panel-primary">
<div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
@include('includes.admin.form-both')
<div class="panel-body" id="messages">
@foreach($messages as $key => $message)
@if($message->user_id == 0)
<div class="single-reply-area user">
<div class="row">
<div class="col-lg-12">
<div class="reply-area">
<div class="left">
<p>{{ $message->message }}</p>
@if ($message->photo != NULL)
<a href="{{ asset('assets/images/'.$message->photo)}}" download="" class=""><i class="fas fa-paperclip"></i> @lang('attachment')-{{ $key +=1 }}</a>
@endif
</div>
<div class="right">
@if ($message->admin_id != NULL)
<img class="img-circle" src="{{ $message->admin->photo != null ? asset('assets/images/'.$message->admin->photo) : asset('assets/images/noimage.png')}}" alt="Admin">
@else
<img class="img-circle" src="{{ $message->owner->photo != null ? asset('assets/images/'.$message->owner->photo) : asset('assets/images/noimage.png')}}" alt="User">
@endif
<p class="ticket-date">{{ $message->created_at->diffForHumans() }}</p>
</div>
</div>
</div>
</div>
</div>
<br>

@else

<div class="single-reply-area admin">
<div class="row">
<div class="col-lg-12">
<div class="reply-area">
<div class="left">
<img class="img-circle" src="{{ $message->user->photo != null ? asset('assets/images/'.$message->user->photo ):asset('assets/images/noimage.png') }}" alt="">
<p class="ticket-date">{{ $message->created_at->diffForHumans() }}</p>
</div>
<div class="right">
<p>{{ $message->message }}</p>
@if ($message->photo != NULL)
<a href="{{ asset('assets/images/'.$message->photo)}}" download="" class=""><i class="fas fa-paperclip"></i> @lang('attachment')-{{ $key +=1 }}</a>
@endif
</div>
</div>
</div>
</div>
</div>

<br>

@endif

@endforeach
</div>
<div class="panel-footer">
<form action="{{route('admin.booking.message.submit',$conversation_id)}}"  method="POST" enctype="multipart/form-data">
{{csrf_field()}}
<div class="form-group">
<input type="hidden" name="user_id" value="0">
<input type="file" name="photo" id="" class="form-control my-3">
<textarea class="form-control" name="message" id="wrong-invoice" rows="5" required="" placeholder="{{ __('Message') }}"></textarea>
</div>
<div class="form-group">
<button type="submit" class="btn btn-primary btn-rounded">
{{ __('Add Reply') }}
</button>
</div>
</form>
</div>
</div>
</div>
</div>

</div>
<!--Row-->

@endsection


@section('scripts')


@endsection
