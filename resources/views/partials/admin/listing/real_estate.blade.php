@if (isset($data) && $data->count() > 0)
<div class="row mt-4">
<div class="col-sm-6 col-xl-4">
<label class="form-label">{{ __('Property Price') }}</label>
<input type="number" min="0" class="input-field" name="r_price" id="r_price"
placeholder="{{ __('Price') }}" value="{{ $data->r_price }}">
</div>
<div class="col-sm-6 col-xl-4">
<label class="form-label">{{ __('No. Bed') }}</label>
<input type="number" min="0" class="input-field" name="r_bed" placeholder="{{ __('Example: 3') }}"
value="{{ $data->r_bed }}">
</div>
<div class="col-sm-6 col-xl-4">
<label class="form-label">{{ __('No. Bathrooms') }}</label>
<input type="number" min="0" class="input-field" name="r_baths" placeholder="{{ __('Example: 1') }}"
value="{{ $data->r_baths }}">
</div>
<div class="col-sm-6 col-xl-4">
<label class="form-label">{{ __('Square Feet') }}</label>
<input type="number" min="0" class="input-field" name="r_square"
placeholder="{{ __('Example: 1200 sqrt') }}" value="{{ $data->r_square }}">
</div>
<div class="col-sm-6 col-xl-4">
<label class="form-label">{{ __('Property Type') }}</label>
<select name="r_property_type" class="input-field">
<option value="buy" {{ $data->r_property_type == 'buy' ? 'selected' : '' }}>{{ __('Buy') }}
</option>
<option value="rent" {{ $data->r_property_type == 'rent' ? 'selected' : '' }}>{{ __('Rent') }}
</option>
</select>
</div>

</div>
@else
<div class="row">
<div class="col-sm-6 col-xl-4">
<label class="form-label">{{ __('Property Price') }}</label>
<input type="number" min="0" class="input-field" name="r_price" id="r_price"
placeholder="{{ __('Price') }}">
</div>
<div class="col-sm-6 col-xl-4">
<label class="form-label">{{ __('No. Bed') }}</label>
<input type="number" min="0" class="input-field" name="r_bed"
placeholder="{{ __('Example: 3') }}">
</div>
<div class="col-sm-6 col-xl-4">
<label class="form-label">{{ __('No. Bathrooms') }}</label>
<input type="number" min="0" class="input-field" name="r_baths"
placeholder="{{ __('Example: 1') }}">
</div>
<div class="col-sm-6 col-xl-4">
<label class="form-label">{{ __('Square Feet') }}</label>
<input type="number" min="0" class="input-field" name="r_square"
placeholder="{{ __('Example: 1200 sqrt') }}">
</div>
<div class="col-sm-6 col-xl-4">
<label class="form-label">{{ __('Property Type') }}</label>
<select name="r_property_type" class="input-field">
<option value="buy">{{ __('Buy') }}</option>
<option value="rent">{{ __('Rent') }}</option>
</select>
</div>
</div>
@endif
