@if (isset($rooms) && $rooms->count() > 0)
@foreach ($rooms as $key => $room)
<input type="hidden" name="room_id[]" value="{{ $room->id }}">

<div class="card mb-4">
<div class="card-header py-3">
<div class="d-flex align-items-center justify-content-between w-100">
<div class=""><h6 class="m-0 font-weight-bold text-primary">{{ __('Hotel room') }}</h6></div>
<div class="">
<a href="{{ route('user.listing.room.delete', $room->id) }}" onclick="return confirm('Are you sure you want to permanently remove this?')"><i class="fas fa-trash-alt text-danger"></i></a>
</div>
</div>
</div>

<div class="card-body">
<div class="row">
<div class="col-lg-3">
<div class="left-area"><h4 class="heading">{{ __('Room Name') }}</h4></div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="room_name[]" placeholder="{{ __('Enter Name') }}" value="{{ $room->room_name }}">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area"><h4 class="heading">{{ __('Room Price') }}</h4></div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="room_price[]" placeholder="{{ __('Enter Price') }}" value="{{ $room->room_price }}">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room Photo') }}</h4>
<p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
</div>
</div>
<div class="col-lg-9">
<div class="wrapper-image-preview">
<div class="box">
<div class="back-preview-image" style="background-image: url({{ $room->room_photo ? asset('assets/images/'.$room->room_photo) : asset('assets/images/default.png') }});"></div>
<div class="upload-options">
<label class="img-upload-label" for="room-items-1"><i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
<input id="room-items-1" type="file" class="image-upload" name="room_photo[]" accept="image/*">
</div>
</div>
</div>
</div>
</div>
<div class="row mt-3">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room amenities') }}</h4>
<p class="sub-heading">{{ __('Seperated By Comma(,)') }}</p>
</div>
</div>
<div class="col-lg-9">
<input type="text" id="tags" class="mytags" name="room_amenities[]" placeholder="{{ __('Room amenities') }}"  value="{{ $room->room_amenities }}">
</div>
</div>
<div class="row mt-3">
<div class="col-lg-3">
<div class="left-area"><h4 class="heading">{{ __('Room description') }}</h4></div>
</div>
<div class="col-lg-9">
<textarea class="input-field" name="room_description[]" placeholder="{{ __('Room description') }}" cols="30" rows="10">{{ $room->room_description }}</textarea>
</div>
</div>
</div>
</div>
@endforeach
@else
<div class="card mb-4">
<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
<h6 class="m-0 font-weight-bold text-primary">{{ __('Hotel room') }}</h6>
</div>

<div class="card-body">
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room Name') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="room_name[]" placeholder="{{ __('Enter Name') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room Price') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="room_price[]" placeholder="{{ __('Enter Price') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room Photo') }}</h4>
<p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
</div>
</div>
<div class="col-lg-9">
<div class="wrapper-image-preview">
<div class="box">
<div class="back-preview-image" style="background-image: url({{ asset('assets/images/placeholder.jpg') }});"></div>
<div class="upload-options">
<label class="img-upload-label" for="room-items-1"><i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
<input id="room-items-1" type="file" class="image-upload" name="room_photo[]" accept="image/*">
</div>
</div>
</div>
</div>
</div>

<div class="row mt-3">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room amenities') }}</h4>
<p class="sub-heading">{{ __('Seperated By Comma(,)') }}</p>
</div>
</div>

<div class="col-lg-9">
<input type="text" class="mytags" name="room_amenities[]" placeholder="{{ __('Room amenities') }}"  value="">
</div>
</div>

<div class="row mt-3">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Room description') }}</h4>
</div>
</div>
<div class="col-lg-9">
<textarea class="input-field" name="room_description[]" placeholder="{{ __('Room description') }}" cols="30" rows="10"></textarea>
</div>
</div>

</div>
</div>
@endif
