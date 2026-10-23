<!-- Room Booking -->
<div class="jb-apply-form bg-white rounded py-4 px-4 border mb-4">
<h4 class="ft-bold mb-1">@lang('Book a room')</h4>

<form action="{{ route('user.booking.store') }}" method="post">
@csrf

<input type="hidden" name="listing_id" value="{{ $data->id}}">
<input type="hidden" name="listing_owner_id" value="{{ $data->user_id != NULL ? $data->user_id : 0}}">
<input type="hidden" name="owner_type" value="{{ $data->user_id != NULL ? 'user' : 'admin'}}">
<input type="hidden" name="type" value="hotel">

<div class="Rego-boo-space mt-3">
<div class="row">

<div class="col-lg-6 col-md-6 col-sm-6 col-6">
<div class="form-group mb-3">
<label class="ft-medium small mb-1">@lang('Check In')</label>
<div class="cld-box">
<i class="ti-calendar"></i>
<input type="text" name="checkin" class="form-control" value="{{ now()->format('m/d/Y') }}" placeholder="@lang('Check In')" autocomplete="off">
</div>
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6 col-6">
<div class="form-group mb-3">
<label class="ft-medium small mb-1">@lang('Check Out')</label>
<div class="cld-box">
<i class="ti-calendar"></i>
<input type="text" name="checkout" class="form-control" value="{{ now()->format('m/d/Y') }}" placeholder="@lang('Check Out')" autocomplete="off">
</div>
</div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-6">
<div class="form-group mb-3">
<div class="guests">
<label class="ft-medium small mb-1">@lang('Adults')</label>
<div class="guests-box">
<button class="counter-btn restaurantDec" type="button" id="cnt-down"><i class="ti-minus"></i></button>
<input type="text" id="guestNo" name="adults" value="2">
<button class="counter-btn restaurantInc" type="button" id="cnt-up"><i class="ti-plus"></i></button>
</div>
</div>
</div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-6">
<div class="form-group mb-3">
<div class="guests">
<label class="ft-medium small mb-1">@lang('Kids')</label>
<div class="guests-box">
<button class="counter-btn" type="button" id="kcnt-down"><i class="ti-minus"></i></button>
<input type="text" id="kidsNo" name="kids" value="0">
<button class="counter-btn" type="button" id="kcnt-up"><i class="ti-plus"></i></button>
</div>
</div>
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="ft-medium small mb-1">@lang('Room Type')</label>
<select class="border form-control rounded ps-3" name="room_type">
<option value="executive">@lang('Executive room')</option>
<option value="standard">@lang('Standard room')</option>
</select>
</div>
</div>


<div class="col-lg-12 col-md-12 col-sm-12">
<button type="submit" class="btn text-light rounded full-width theme-bg ft-medium">@lang('Submit')</button>
</div>
</div>
</div>
</form>
</div>

