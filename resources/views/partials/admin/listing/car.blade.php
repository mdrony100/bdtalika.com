@if (isset($data) && $data->count() > 0)
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Car Price') }}</h4>
</div>
</div>

<div class="col-lg-9">
<input type="number" class="input-field" name="car_price" id="car_price" placeholder="{{ __('Car Price') }}" value="{{ $data->car_price }}">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Fuel type') }}</h4>
</div>
</div>

<div class="col-lg-9">
<input type="text" class="input-field" name="car_fuel_type" placeholder="{{ __('Fuel type') }}" value="{{ $data->car_fuel_type }}">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Year of Manufacture') }}</h4>
</div>
</div>

<div class="col-lg-9">
<input type="text" class="input-field" name="car_manufacture_year" placeholder="{{ __('Example: 2010') }}" value="{{ $data->car_manufacture_year }}">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Engine capacity') }}</h4>
</div>
</div>

<div class="col-lg-9">
<input type="text" class="input-field" name="car_engine_capacity" placeholder="{{ __('Example: 500 cc') }}" value="{{ $data->car_engine_capacity }}">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Mileage') }}</h4>
</div>
</div>

<div class="col-lg-9">
<input type="text" class="input-field" name="car_mileage" placeholder="{{ __('Example: 22 KM/L') }}" value="{{ $data->car_mileage }}">
</div>
</div>
@else
<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Car Price') }}</h4>
</div>
</div>

<div class="col-lg-9">
<input type="number" class="input-field" name="car_price" id="car_price" placeholder="{{ __('Car Price') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Fuel type') }}</h4>
</div>
</div>

<div class="col-lg-9">
<input type="text" class="input-field" name="car_fuel_type" placeholder="{{ __('Fuel type') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Year of Manufacture') }}</h4>
</div>
</div>

<div class="col-lg-9">
<input type="text" class="input-field" name="car_manufacture_year" placeholder="{{ __('Example: 2010') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Engine capacity') }}</h4>
</div>
</div>

<div class="col-lg-9">
<input type="text" class="input-field" name="car_engine_capacity" placeholder="{{ __('Example: 500 cc') }}" value="">
</div>
</div>

<div class="row">
<div class="col-lg-3">
<div class="left-area">
<h4 class="heading">{{ __('Mileage') }}</h4>
</div>
</div>

<div class="col-lg-9">
<input type="text" class="input-field" name="car_mileage" placeholder="{{ __('Example: 22 KM/L') }}" value="">
</div>
</div>

@endif

