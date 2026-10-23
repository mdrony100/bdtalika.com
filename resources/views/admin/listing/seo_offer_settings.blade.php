@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center py-3 justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Listing SEO Offer Settings') }}</h5>
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.listing.byviews') }}">{{ __('Listing By Views') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="card mb-4 mt-3">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Lifetime Plan SEO Offer Message') }}</h6>
        </div>

        <div class="card-body">
            <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
            <form class="geniusform" action="{{ route('admin.listing.seo.offer.settings.update') }}" method="POST">

                @include('includes.admin.form-both')

                {{ csrf_field() }}

                <div class="form-group">
                    <label for="message_template">{{ __('WhatsApp Message Template') }} *</label>
                    <textarea class="form-control" name="message_template" id="message_template" cols="30" rows="16" required>{{ $setting->message_template }}</textarea>
                    <small class="form-text text-muted">
                        {{ __('Placeholders: {name} (customer name), {listing} (listing name), {views} (total views), {monthly_price}, {yearly_price}, {save} (yearly saving), {contact} (support number)') }}
                    </small>
                </div>

                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="monthly_price">{{ __('Monthly Price (৳)') }} *</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="monthly_price" name="monthly_price" value="{{ $setting->monthly_price }}" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="yearly_price">{{ __('Yearly Price (৳)') }} *</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="yearly_price" name="yearly_price" value="{{ $setting->yearly_price }}" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="yearly_save">{{ __('Yearly Saving (৳)') }}</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="yearly_save" name="yearly_save" value="{{ $setting->yearly_save }}">
                        <small class="form-text text-muted">{{ __('Leave as-is or set 0 to calculate automatically (yearly - monthly x 12)') }}</small>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="contact_number">{{ __('Support Number') }} *</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ $setting->contact_number }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">{{ __('Status') }} *</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="1" {{ $setting->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="0" {{ $setting->status == 0 ? 'selected' : '' }}>{{ __('Inactive (hide copy button)') }}</option>
                    </select>
                </div>

                <button type="submit" id="submit-btn" class="btn btn-primary w-100">{{ __('Submit') }}</button>

            </form>
        </div>
    </div>

@endsection
