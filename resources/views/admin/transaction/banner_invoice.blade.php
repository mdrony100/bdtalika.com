@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center py-3 justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Create Banner Advertisement Invoice') }}</h5>
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.transactions.index') }}">{{ __('Transactions') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="card mb-4 mt-3">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Banner Advertisement (Offline Payment)') }}</h6>
        </div>

        <div class="card-body">
            <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
            <form class="geniusform" action="{{ route('admin.transactions.banner.invoice.store') }}" method="POST">

                @include('includes.admin.form-both')

                {{ csrf_field() }}

                <div class="form-group">
                    <label for="listing_id">{{ __('Business / Listing') }} *</label>
                    <select class="form-control" id="listing_id" name="listing_id" required>
                        <option value="" class="d-none">{{ __('Select Business') }}</option>
                        @foreach ($listings as $listing)
                            <option value="{{ $listing->id }}"
                                data-name="{{ optional($listing->user)->name }}"
                                data-email="{{ optional($listing->user)->email }}"
                                data-phone="{{ optional($listing->user)->phone ?: $listing->phone_number }}">
                                {{ $listing->name }}{{ optional($listing->user)->name ? ' (' . optional($listing->user)->name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">{{ __('Selecting a business auto-fills the customer email and phone.') }}</small>
                </div>

                <div class="form-group">
                    <label for="service_name">{{ __('Advertisement / Service Name') }} *</label>
                    <input type="text" class="form-control" id="service_name" name="service_name"
                        value="{{ old('service_name', 'Homepage Hero Slider Advertisement') }}" required>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="duration">{{ __('Duration') }} *</label>
                        <input type="text" class="form-control" id="duration" name="duration"
                            value="{{ old('duration', '1 Month') }}" placeholder="e.g. 1 Month / 12 Months" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="amount">{{ __('Amount (৳)') }} *</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="amount" name="amount"
                            value="{{ old('amount') }}" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="phone">{{ __('Customer Phone') }}</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">{{ __('Customer Email (invoice will be emailed here)') }} *</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="email_invoice" name="email_invoice" value="1" checked>
                    <label class="form-check-label" for="email_invoice">{{ __('Email the invoice PDF to the customer') }}</label>
                </div>

                <button type="submit" id="submit-btn" class="btn btn-primary w-100">{{ __('Create Invoice & Download') }}</button>

                <small class="form-text text-muted mt-2">
                    {{ __('A transaction record with type "banner_ad" will be created and the invoice PDF will download automatically. The invoice is marked as Due (offline payment).') }}
                </small>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#listing_id').change(function() {
                var $opt = $(this).find('option:selected');
                if ($opt.val() === '') return;
                $('#email').val($opt.data('email') || '');
                $('#phone').val($opt.data('phone') || '');
            });
        });
    </script>
@endsection
