@extends('layouts.front')

@push('css')

@endpush

@section('content')

<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#" class="color_link">@lang('Home')</a></li>
<li class="breadcrumb-item active theme-cl" aria-current="page">@lang('Subscription')</li>
</ol>
</nav>
</div>
</div>
</div>
</div>


<!-- ================== ===== Booking page Design ======================== -->
<section class="gray space min">
<div class="container">
<div class="row">
<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
<div class="d-block"><h4 class="ft-medium">@lang('Payment Method')</h4></div>
<form id="" class="subscription-form" action="" method="POST" enctype="multipart/form-data">
@csrf
<div class="single-form-item bg-white rounded px-3 py-3 mb-4">
<div class="col-lg-12 col-md-12 col-sm-12">
<div class="form-group">
<label>@lang('Method')<i class="req">*</i></label>
<select name="method" id="subscriptionMethod" class="form-control" required>
<option value="" selected>{{__('Please select a method')}}</option>
@foreach ($gateways as $gateway)
@if ($gateway->type == 'manual')
<option value="Manual" data-details="{{$gateway->details}}">{{ $gateway->title }}</option>
@else
<option value="{{$gateway->keyword}}">{{ $gateway->name }}</option>
@endif
@endforeach
</select>
</div>
</div>

<div id="card-view" class="col-lg-12 my-3 pt-3 d-none">
<div class="row">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="lc" value="UK">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest">

<div class="col-lg-6 mb-3">
<input type="text" class="form-control card-elements" name="cardNumber" placeholder="{{ __('Card Number') }}" autocomplete="off" autofocus oninput="validateCard(this.value);"/>
<span id="errCard"></span>
</div>

<div class="col-lg-6 cardRow mb-3">
<input type="text" class="form-control card-elements" placeholder="{{ ('Card CVC') }}" name="cardCVC" oninput="validateCVC(this.value);">
<span id="errCVC"></span>
</div>

<div class="col-lg-6">
<input type="text" class="form-control card-elements" placeholder="{{ __('Month') }}" name="month" >
</div>

<div class="col-lg-6">
<input type="text" class="form-control card-elements" placeholder="{{ __('Year') }}" name="year">
</div>

</div>
</div>

<div class="col-sm-12 mt-4 manual-payment d-none">
<div class="card default--card">
<div class="card-body">
<div class="row">

<div class="col-sm-12 pb-2 manual-payment-details">
</div>

<div class="col-sm-12">
<label class="form-label required">@lang('Transaction ID')#</label>
<input class="form-control" name="txn_id4" type="text" placeholder="Transaction ID" id="manual_transaction_id">
</div>

</div>
</div>
</div>
</div>

<input type="hidden" name="email" value="{{ auth()->user()->email }}">
<input type="hidden" id="amount" name="price" value="{{ userBaseAmount($data->price) }}">
<input type="hidden" name="days" value="{{ $data->post_duration }}">
<input type="hidden" name="user_id" value="{{ auth()->id() }}">
<input type="hidden" name="plan_id" value="{{ $data->id }}">
<input type="hidden" name="currency_sign" value="{{ $currency->sign }}">
<input type="hidden" id="currencyCode" name="currency_code" value="{{ $currency->name }}">
<input type="hidden" name="currency_id" value="{{ $currency->id }}">
<input type="hidden" name="paystackInfo" id="paystackInfo" value="{{ $paystackKey }}">
<input type="hidden" name="ref_id" id="ref_id" value="">
</div>

<div class="single-form-item mb-4">
<div class="d-block"><button type="submit" class="btn theme-bg text-light ft-medium rounded">@lang('Confirm & Pay')</button></div>
</div>
</form>

</div>

<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
<div class="biiling-wrap mb-4">
<div class="billing-item-middle">
<div class="billing-its-title"><h4>@lang('Package summary')</h4></div>
<div class="billing-item-lists">
<ul>
<li><span class="prt-title">@lang('Membership'):</span><span class="prt-value">{{ strtoupper($data->title) }}</span></li>
@if (auth()->user()->plan_id != NULL && auth()->user()->plan_end_date>now())
<li><span class="prt-title">@lang('Start Date'):</span><span class="prt-value">{{ Carbon\Carbon::parse(auth()->user()->plan_end_date)->format('d-m-Y') }}</span></li>
<li><span class="prt-title">@lang('Expiry Date'):</span><span class="prt-value">{{  Carbon\Carbon::parse(auth()->user()->plan_end_date)->addDays($data->post_duration)->format('d-m-Y') }}</span></li>
@else
<li><span class="prt-title">@lang('Start Date'):</span><span class="prt-value">{{ now()->format('d-m-Y') }}</span></li>
<li><span class="prt-title">@lang('Expiry Date'):</span><span class="prt-value">{{ now()->addDays($data->post_duration)->format('d-m-Y') }}</span></li>
@endif
<li><span class="prt-title">@lang('Directory Limit'):</span><span class="prt-value">{{ $data->post_limit }}</span></li>
</ul>
</div>
</div>
<div class="billing-item-foot">
<div class="blg-fgty">
<h5 class="ft-medium">@lang('Total Cost'):</h5>
<h6 class="theme-cl ft-bold">{{ showNameAmount($data->price) }}</h6>
</div>
</div>
</div>
</div>

</div>
</div>
</section>
<!-- ======================= Booking page Design End ======================== -->

<!-- ============================ Call To Action ================================== -->
{{-- @includeIf('partials.front.cta') --}}
<!-- ============================ Call To Action End ================================== -->



@endsection

@push('js')

<script>
'use strict';

$(document).on('change','#subscriptionMethod',function(){
var val = $(this).val();

if(val == 'stripe')
{
$('.subscription-form').prop('action',"{{ route('subscription.stripe.submit') }}");
$('.subscription-form').prop('id','');
$('#manual_transaction_id').prop('required',false);
$('.manual-payment').addClass('d-none');
}

if(val == 'paypal') {
$('.subscription-form').prop('action',"{{ route('subscription.paypal.submit') }}");
$('.subscription-form').prop('id','');
$('#card-view').addClass('d-none');
$('.card-elements').prop('required',false);
$('#manual_transaction_id').prop('required',false);
$('.manual-payment').addClass('d-none');
}

if(val == 'paystack') {
$('.subscription-form').prop('action',"{{ route('subscription.paystack.submit') }}");
$('.subscription-form').prop('id','step1-form');
$('#card-view').addClass('d-none');
$('.card-elements').prop('required',false);
$('#manual_transaction_id').prop('required',false);
$('.manual-payment').addClass('d-none');
}

if(val == 'paytm') {
$('.subscription-form').prop('action',"{{ route('subscription.paytm.submit') }}");
$('.subscription-form').prop('id','');
$('#card-view').addClass('d-none');
$('.card-elements').prop('required',false);
$('#manual_transaction_id').prop('required',false);
$('.manual-payment').addClass('d-none');
}

if(val == 'instamojo') {
$('.subscription-form').prop('action',"{{ route('subscription.instamojo.submit') }}");
$('.subscription-form').prop('id','');
$('#card-view').addClass('d-none');
$('.card-elements').prop('required',false);
$('#manual_transaction_id').prop('required',false);
$('.manual-payment').addClass('d-none');
}

if(val == 'razorpay') {
$('.subscription-form').prop('action',"{{ route('subscription.razorpay.submit') }}");
$('.subscription-form').prop('id','');
$('#card-view').addClass('d-none');
$('.card-elements').prop('required',false);
$('#manual_transaction_id').prop('required',false);
$('.manual-payment').addClass('d-none');
}

if(val == 'mollie') {
$('.subscription-form').prop('action',"{{ route('subscription.molly.submit') }}");
$('.subscription-form').prop('id','');
$('#card-view').addClass('d-none');
$('.card-elements').prop('required',false);
$('#manual_transaction_id').prop('required',false);
$('.manual-payment').addClass('d-none');
}

if(val == 'flutterwave') {
$('.subscription-form').prop('action',"{{ route('subscription.flutter.submit') }}");
$('.subscription-form').prop('id','');
$('#card-view').addClass('d-none');
$('.card-elements').prop('required',false);
$('#manual_transaction_id').prop('required',false);
$('.manual-payment').addClass('d-none');
}

if(val == 'authorize.net')
{
$('.subscription-form').prop('action',"{{ route('subscription.authorize.submit') }}");
$('.subscription-form').prop('id','');
$('#card-view').removeClass('d-none');
$('.card-elements').prop('required',true);
$('#manual_transaction_id').prop('required',false);
$('.manual-payment').addClass('d-none');
}

if(val == 'Manual'){
$('.subscription-form').prop('action','{{route('subscription.manual.submit')}}');
$('.subscription-form').prop('id','');
$('.manual-payment').removeClass('d-none');
$('#manual_transaction_id').prop('required',true);
$('#card-view').addClass('d-none');
$('.card-elements').prop('required',false);

const details = $(this).find(':selected').data('details');
$('.manual-payment-details').empty();
$('.manual-payment-details').append(`<font size="3">${details}</font>`)
}

});

$(document).on('submit','#step1-form',function(e){
e.preventDefault();

var total = parseFloat( $('#amount').val());
var paystackInfo = $("#paystackInfo").val();
var curr = $('#currencyCode').val();

total = Math.round(total);

var handler = PaystackPop.setup({
key: paystackInfo,
email: $('input[name=email]').val(),
amount: total * 100,
currency: curr,
ref: ''+Math.floor((Math.random() * 1000000000) + 1),
callback: function(response){
$('#ref_id').val(response.reference);
$('#step1-form').prop('id','');
$('.subscription-form').submit();
},
onClose: function(){
window.location.reload();
}
});
handler.openIframe();
return false;View all Areas
});
</script>

<script src="//voguepay.com/js/voguepay.js"></script>

<script src="{{ asset('assets/front/js/payvalid.js') }}"></script>
<script src="{{ asset('assets/front/js/paymin.js') }}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('assets/front/js/payform.js') }}"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
'use strict';

var cnstatus = false;
var dateStatus = false;
var cvcStatus = false;

function validateCard(cn) {
cnstatus = Stripe.card.validateCardNumber(cn);
if (!cnstatus) {
$("#errCard").html('Card number not valid<br>');
} else {
$("#errCard").html('');
}
btnStatusChange();


}

function validateCVC(cvc) {
cvcStatus = Stripe.card.validateCVC(cvc);
if (!cvcStatus) {
$("#errCVC").html('CVC number not valid');
} else {
$("#errCVC").html('');
}
btnStatusChange();
}

</script>
@endpush
