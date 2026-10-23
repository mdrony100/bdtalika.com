@if (isset($menus) && $menus->count() > 0)
@foreach ($menus as $key => $menu)
<input type="hidden" name="menu_id[]" value="{{ $menu->id }}">
<div class="card mb-2">
<div class="card-header">
<div class="d-flex justify-content-between">
<div>
<h6 class="m-0 font-weight-bold text-primary">{{ __('Menu Items') }}</h6>
</div>
<div>
<a href="{{ route('user.listing.menu.delete', $menu->id) }}" onclick="return confirm('Are you sure you want to delete this Menu?')" class="border-0">
<i class="fas fa-trash-alt text-danger"></i>
</a>
</div>
</div>
</div>

<div class="card-body">
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Menu Name') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="menu_title[]" placeholder="{{ __('Enter Name') }}" value="{{ $menu->menu_title }}">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Menu Price') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="number" class="input-field" id="menu_price" name="menu_price[]" placeholder="{{ __('Enter Price') }}" value="{{ $menu->menu_price }}">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Menu Photo') }}</h4>
<p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
</div>
</div>
<div class="col-lg-9">
<div class="wrapper-image-preview">
<div class="box">
<div class="back-preview-image" style="background-image: url({{ $menu->menu_photo ? asset('assets/images/' . $menu->menu_photo) : asset('assets/images/default.png') }});">
</div>
<div class="upload-options">
<label class="img-upload-label" for="menu-items-{{ $key }}"><i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
<input id="menu-items-{{ $key }}" type="file" class="image-upload" name="menu_photo[]" accept="image/*">
</div>
</div>
</div>
</div>
</div>
</div>
</div>
@endforeach
@else
<div class="card mb-2">
<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
<h6 class="m-0 font-weight-bold text-primary">{{ __('Menu Items') }}</h6>
</div>
<div class="card-body">
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Menu Name') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="text" class="input-field" name="menu_title[]" placeholder="{{ __('Enter Name') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Menu Price') }}</h4>
</div>
</div>
<div class="col-lg-9">
<input type="number" class="input-field" name="menu_price[]" id="menu_price" placeholder="{{ __('Enter Price') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Menu Photo') }}</h4>
<p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
</div>
</div>
<div class="col-lg-9">
<div class="wrapper-image-preview">
<div class="box">
<div class="back-preview-image" style="background-image: url({{ asset('assets/images/default.png') }});"></div>
<div class="upload-options">
<label class="img-upload-label" for="menu-items-1"><i class="fas fa-camera"></i>
{{ __('Upload Picture') }} </label>
<input id="menu-items-1" type="file" class="image-upload" name="menu_photo[]" accept="image/*">
</div>
</div>
</div>
</div>
</div>

</div>
</div>
@endif
