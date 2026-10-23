<!-- Room Booking -->
<div class="jb-apply-form bg-white rounded py-4 px-4 border mb-4">
<h4 class="ft-bold mb-1">@lang('Make an appointment')</h4>

<form action="{{ route('user.booking.store') }}" method="post">
@csrf

<input type="hidden" name="listing_id" value="{{ $data->id}}">
<input type="hidden" name="listing_owner_id" value="{{ $data->user_id != NULL ? $data->user_id : 0}}">
<input type="hidden" name="owner_type" value="{{ $data->user_id != NULL ? 'user' : 'admin'}}">
<input type="hidden" name="type" value="beauty">

<div class="Rego-boo-space mt-3">
<div class="row">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="ft-medium small mb-1">@lang('Check In')</label>
<div class="cld-box">
<i class="ti-calendar"></i>
<input type="text" name="checkin" class="form-control" value="{{ now()->format('m/d/Y') }}" placeholder="Check In" autocomplete="off">
</div>
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="ft-medium small mb-1">@lang('Service')</label>
<select class="border form-control rounded ps-3" name="service_id">
<option value="">@lang('Select a service')</option>
@foreach ($data->services as $key=>$service)
<option value="{{ $service->id }}">{{ $service->service_name }} ({{ $service->service_from }} @lang('To') {{ $service->service_to }})</option>
@endforeach
</select>
</div>
</div>


<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="mb-1">@lang('Note')</label>
<textarea class="form-control border rounded ps-3" name="note" placeholder="@lang('Write note')"></textarea>
</div>
</div>

<div class="col-lg-12 col-md-12 col-sm-12">
<button type="submit" class="btn text-light rounded full-width theme-bg ft-medium">@lang('Submit')</button>
</div>
</div>
</div>
</form>
</div>

