@extends('layouts.admin')
@section('show', 'show')
@if (($mailType ?? 'draft') === 'renewal')
    @section('paid_listing', 'active')
@else
    @section('draft_listing', 'active')
@endif

@section('styles')
    <style>
        .note-editable.card-block {
            height: 380px;
        }

        .note-toolbar.card-header {
            display: none;
        }
    </style>
@endsection

@php
    $mailType = $mailType ?? 'draft';
    $isRenewal = $mailType === 'renewal';
    $recipientName = optional($listing->user)->name ?: 'Business Owner';
    $recipientEmail = optional($listing->user)->email ?: $listing->email;
    $paidDate = optional($listing->latestCompletedBkashPayment)->paid_at
        ?: optional($listing->latestTransaction)->created_at
        ?: $listing->plan_start_date;
    $endingDate = $listing->plan_end_date;
    $isLifetime = optional($listing->plan)->plan_type === 'lifetime';
    $backRoute = $isRenewal ? route('admin.listing.paid') : route('admin.listing.pending');

    // Plan prices are pulled live from the database.
    $publicPaidPlans = ($plans ?? collect())->where('plan_type', '!=', 'free');
    $formatPrice = function ($price) {
        $formatted = number_format((float) $price, 2, '.', '');
        $formatted = rtrim(rtrim($formatted, '0'), '.');
        return $formatted === '' ? '0' : $formatted;
    };
    $monthlyPrices = $publicPaidPlans->where('plan_type', 'monthly')->pluck('price')->sort()->values();
    $cheapestMonthly = $monthlyPrices->isNotEmpty() ? $formatPrice($monthlyPrices[0]) : null;
    $draftSubject = $cheapestMonthly !== null
        ? 'Pay Only ' . $cheapestMonthly . ' Taka Monthly to Activate Your Business - Pay by Bkash.'
        : 'Activate Your Business - Pay by Bkash.';
    $hasMonthlyPlans = $monthlyPrices->isNotEmpty();
@endphp

@section('content')
    <div class="card mb-4">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-gray-800 pl-3">{{ $isRenewal ? __('Renewal Mail') : __('Directory') }}
                <a class="btn btn-primary btn-rounded btn-sm" href="{{ $backRoute }}">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
            </h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item">
                    <a href="javascript:;">{{ $isRenewal ? __('Paid Listings') : __('All Directories') }}</a>
                </li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-6">
                    <form action="{{ route('admin.listing.sendmail') }}" method="post">
                        @csrf
                        <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                        <input type="hidden" name="to" value="{{ $recipientEmail }}">
                        <input type="hidden" name="redirect_to" value="{{ $isRenewal ? 'paid' : 'pending' }}">
                        <div class="form-group">
                            <input type="text" class="form-control" name="subject" placeholder="{{ __('Subject') }}"
                                value="{{ $isRenewal ? 'Renew Your BDTalika Listing Before Expiry' : $draftSubject }}"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control summernote" name="message" cols="20" rows="10" placeholder="Your Message" required>
@if ($isRenewal)
<strong>Dear <span>{{ $recipientName }}</span></strong>, <br><br>
Your business <strong>{{ $listing->name }}</strong> on <strong>bdtalika.com</strong> is currently using the <strong>{{ optional($listing->plan)->title ?: 'paid' }}</strong> package.<br><br>

<strong>Your current paid period:</strong><br>
Paid Date: <strong>{{ $paidDate ? \Carbon\Carbon::parse($paidDate)->format('d M Y') : 'Not found' }}</strong><br>
Paid Ending Date: <strong>{{ $isLifetime ? 'Lifetime' : ($endingDate ? \Carbon\Carbon::parse($endingDate)->format('d M Y') : 'Not set') }}</strong><br><br>

@if ($isLifetime)
Your listing is marked as a lifetime package, so no yearly renewal payment is required.<br><br>
@else
To keep your listing active and visible to customers, please renew your package before the paid ending date. If payment is not completed after expiry, your listing may be deactivated.<br><br>

<strong>Renewal payment options:</strong><br>
@foreach ($publicPaidPlans as $plan)
{{ $plan->title }}: {{ $formatPrice($plan->price) }} Tk<br>
@endforeach
@if ($hasMonthlyPlans)
Monthly packages are billed annually.<br>
@endif
View packages: https://bdtalika.com/user/listing<br><br>
@endif

<strong>Need Help? Contact Us:</strong><br>
Email: bdtalika@gmail.com<br>
bKash: +88 01738411242<br>
WhatsApp: https://wa.me/8801738411242<br><br>

Thank you for staying with BDTalika.<br><br>
Best regards,<br>
Bdtalika Team<br>
https://bdtalika.com
@else
<strong>Dear <span>{{ $recipientName }}</span></strong>, <br><br>
Your business <strong>{{ $listing->name }}</strong> is currently in Draft. Activate a package so customers can see it on BDTalika.<br><br>

<strong>Packages:</strong><br>
@foreach ($publicPaidPlans as $plan)
{{ $plan->title }}: {{ $formatPrice($plan->price) }} Tk<br>
@endforeach
@if ($hasMonthlyPlans)
Monthly packages are billed annually.<br>
@endif
<br>

<strong>bKash:</strong> +88 01738411242<br>
View packages: https://bdtalika.com/user/listing<br>
WhatsApp: https://wa.me/8801738411242<br><br>

Thank you,<br>
BDTalika Team<br>
https://bdtalika.com
@endif
                            </textarea>
                        </div>
                        <div id="submitBtn">
                            <button class="submit-btn btn btn-primary text-center" type="submit">
                                {{ __('Send Mail') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('submit', 'form', function() {
            var submitBtn = $(this).find('#submitBtn');
            submitBtn.prop('disabled', true);
            submitBtn.html(`
                <button type="submit" class="submit-btn btn btn-primary text-center disabled">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('Loading...') }}
                </button>
              `);
        });
    </script>
@endsection
