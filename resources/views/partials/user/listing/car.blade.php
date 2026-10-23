@if (isset($data) && $data->count() > 0)
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<h4 class="pb-3 ft-medium fs-md"><i class="fa fa-file me-2 theme-cl fs-sm"></i>@lang('Car')</h4>
<div class="dashboard-list-wraps-body">
<div class="row">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang('Car Price')</label>
<input type="number" name="car_price" class="form-control rounded" placeholder="@lang('Car Price')" value="{{ $data->car_price }}">
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang('Fuel type')</label>
<input type="text" name="car_fuel_type" class="form-control rounded" placeholder="@lang('Fuel type')" value="{{ $data->car_fuel_type }}">
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang('Year of Manufacture')</label>
<input type="text" name="car_manufacture_year" class="form-control rounded" placeholder="@lang('Example: 2010')" value="{{ $data->car_manufacture_year }}">
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang('Engine capacity')</label>
<input type="text" name="car_engine_capacity" class="form-control rounded" placeholder="@lang('Example: 500 cc')" value="{{ $data->car_engine_capacity }}">
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang('Mileage')</label>
<input type="text" name="car_mileage" class="form-control rounded" placeholder="@lang('Example: 22 KM/L')" value="{{ $data->car_mileage }}">
</div>
</div>
</div>
</div>
</div>
@else
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<h4 class="pb-3 ft-medium fs-md"><i class="fa fa-file me-2 theme-cl fs-sm"></i>@lang('Car')</h4>

<div class="dashboard-list-wraps-body">
<div class="row">
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang('Car Price')</label>
<input type="number" name="car_price" class="form-control rounded" placeholder="@lang('Car Price')">
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang('Fuel type')</label>
<input type="text" name="car_fuel_type" class="form-control rounded" placeholder="@lang('Fuel type')">
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang('Year of Manufacture')</label>
<input type="text" name="car_manufacture_year" class="form-control rounded" placeholder="@lang('Example: 2010')">
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang('Engine capacity')</label>
<input type="text" name="car_engine_capacity" class="form-control rounded" placeholder="@lang('Example: 500 cc')">
</div>
</div>

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang('Mileage')</label>
<input type="text" name="car_mileage" class="form-control rounded" placeholder="@lang('Example: 22 KM/L')">
</div>
</div>
</div>
</div>
</div>
@endif
