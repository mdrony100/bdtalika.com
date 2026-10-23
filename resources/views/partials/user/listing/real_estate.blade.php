@if (isset($data) && $data->count() > 0)
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<h4 class="pb-3 ft-medium fs-md"><i class="fa fa-file me-2 theme-cl fs-sm"></i>@lang('Real estate Info')</h4>

<div class="dashboard-list-wraps-body">
<div class="row">
<div class="col-sm-6 col-lg-4">
<div class="form-group mb-2">
<label class="form-label">@lang('Property Price')</label>
<input type="number" min="0" name="r_price" class="form-control rounded" placeholder="@lang('Property Price')" value="{{ $data->r_price }}">
</div>
</div>

<div class="col-sm-6 col-lg-4">
<div class="form-group mb-2">
<label class="form-label">@lang('No. Bed')</label>
<input type="number" min="0" name="r_bed" class="form-control rounded" placeholder="@lang('No. Bed')" value="{{ $data->r_bed }}">
</div>
</div>

<div class="col-sm-6 col-lg-4">
<div class="form-group mb-2">
<label class="form-label">@lang('No. Bathrooms')</label>
<input type="number" min="0" name="r_baths" class="form-control rounded" placeholder="@lang('No. Bathrooms')" value="{{ $data->r_baths }}">
</div>
</div>

<div class="col-sm-6 col-lg-4">
<div class="form-group mb-2">
<label class="form-label">@lang('Square Feet')</label>
<input type="number" min="0" name="r_square" class="form-control rounded" placeholder="@lang('Square Feet')" value="{{ $data->r_square }}">
</div>
</div>

<div class="col-sm-6 col-lg-4">
<div class="form-group mb-2">
<label class="form-label">@lang('Property Type')</label>
<select name="r_property_type" class="input-field">
<option value="buy" {{ $data->r_property_type == 'buy' ? 'selected' : '' }}>
{{ __('Buy') }}</option>
<option value="rent" {{ $data->r_property_type == 'rent' ? 'selected' : '' }}>
{{ __('Rent') }}</option>
</select>
</div>
</div>

</div>
</div>
</div>
@else
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<h4 class="pb-3 ft-medium fs-md"><i class="fa fa-file me-2 theme-cl fs-sm"></i>@lang('Real estate Info')</h4>

<div class="dashboard-list-wraps-body">
<div class="row">
<div class="col-sm-6 col-lg-4">
<div class="form-group mb-2">
<label class="form-label">@lang('Property Price')</label>
<input type="number" min="0" name="r_price" class="form-control rounded" placeholder="@lang('Property Price')">
</div>
</div>

<div class="col-sm-6 col-lg-4">
<div class="form-group mb-2">
<label class="form-label">@lang('No. Bed')</label>
<input type="number" min="0" name="r_bed" class="form-control rounded" placeholder="@lang('No. Bed')">
</div>
</div>

<div class="col-sm-6 col-lg-4">
<div class="form-group mb-2">
<label class="form-label">@lang('No. Bathrooms')</label>
<input type="number" min="0" name="r_baths" class="form-control rounded" placeholder="@lang('No. Bathrooms')">
</div>
</div>

<div class="col-sm-6 col-lg-4">
<div class="form-group mb-2">
<label class="form-label">@lang('Square Feet')</label>
<input type="number" min="0" name="r_square" class="form-control rounded" placeholder="@lang('Square Feet')">
</div>
</div>

<div class="col-sm-6 col-lg-4">
<div class="form-group mb-2">
<label class="form-label">@lang('Property Type')</label>
<select name="r_property_type" class="input-field">
<option value="buy">{{ __('Buy') }}</option>
<option value="rent">{{ __('Rent') }}</option>
</select>
</div>
</div>
</div>
</div>
</div>
@endif
