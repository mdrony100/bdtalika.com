@extends('layouts.front')
@section('stepTwo', 'active')
@push('css')
    <style>
        .biiling-wrap .btn-primary {
            padding: 12px 20px !important;
            border-radius: 6px !important;
            font-size: 15px;
        }

        .border_end {
            border-right: 1px solid #ddd;
        }

        @media screen and (max-width:1199px) {
            .border_end {
                border-top: 1px solid #ddd;
                border-right: 0;
            }
        }
    </style>
@endpush
@section('content')
    <!-- ======================= dashboard Detail ======================== -->
    <div class="bg_light px-4 py-2">
        <div class="dashboard-tlbar d-block">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}"
                                    class="color_link">@lang('Home')</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0)" class="">@lang('Checkout')</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-wrap bg-light px-2 px-lg-4 pt-3 pb-5">
        <a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false"
            aria-controls="MobNav">
            <i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
        </a>
        <div class="collapse" id="MobNav">
            @includeIf('partials.user.sidebar')
        </div>
        <div class="dashboard-content">
            <div class="bg-white rounded-3 border border-light  p-4">
                <div class="row">
                    <div class="col-xl-5  mb-3 mb-xl-0 order-xl-2">
                        <div class="">
                            <div class="ps-xl-5">
                                <div class="biiling-wrap">
                                    <div class="d-flex align-items-center mb-3 justify-content-between">
                                        <h5 class="ft-medium m-0">Plan Price:</h5>
                                        <h6 class="theme-cl ft-bold m-0">TK {{ $plan->price }}</h6>
                                    </div>
                                    <div class="billing-item-middle m-0">
                                        <div class="billing-item-lists p-0 mb-4">
                                            <ul>
                                                <li class="p-0 pb-2">
                                                    <span class="prt-title">Membership:</span>
                                                    <span class="prt-value">{{ $plan->title }}</span>
                                                </li>
                                                <li class="p-0 pb-2">
                                                    <span class="prt-title">Plan Type:</span>
                                                    <span class="prt-value">{{ Str::ucfirst($plan->plan_type) }}</span>
                                                </li>
                                                <li class="p-0 pb-2">
                                                    <span class="prt-title">Plan Duration:</span>
                                                    <span class="prt-value">
                                                    {{ $plan->post_duration ? $plan->post_duration . ' days' : 'Lifetime' }}
                                                </span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="text-center mt-4">
                                            <h5 class="mb-3"><strong>Do you need help?</strong></h5>
                                            <h6>
                                                For any information Please call: </br>
                                                <strong>0190-9791-959 </strong>
                                                {{--<a href="https://wa.me/+8801909791959" target="_blank">
                                                    <img src="{{ asset('assets/images/whatsapp.png') }}" width="25"
                                                        alt="">
                                                </a> --}}
                                            </h6>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7 order-xl-1">
                        <div class="border_end pt-3 pt-xl-0">
                            <div class="">
                                <div class="biiling-wrap">
                                    <h4 class="mb-4">Pay to Bkash</h4>
                                    <div class="pe-xl-3">
                                        <div class="row d-flex align-items-center">
                                            <div class="mb-4">
                                                <h6>
                                                    Please scan the QR code below using your bKash app and send
                                                    <strong>TK {{ $plan->price }}</strong> to <strong>0190-9791-959</strong> complete your payment.
                                                </h6>
                                                <div>
                                                    <!-- <img src="{{ asset('assets/images/nagad.jpg') }}" class="img-fluid border rounded-2" width="200" alt=""> -->
                                                    <img src="{{ asset('assets/images/bkash.png') }}" class="img-fluid border rounded-2" width="200" alt="">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <form class="row" action="{{ route('user.listing.bkashpayment', $plan->id) }}"
                                                    method="post">
                                                    <input hidden name="plan_name" value="{{ $plan->title }}">
                                                    <input hidden name="plan_type" value="{{ $plan->plan_type }}">
                                                    <input hidden name="plan_duration" value="{{ $plan->post_duration }}">
                                                    <input hidden name="amount" value="{{ $plan->price }}">
                                                    @csrf
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Your Bkash number
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="phone" class="form-control py-2"
                                                            required placeholder="Enter your Bkash number">
                                                    </div>
                                                    <div class="col-md-6 form-group mb-3">
                                                        <label class="form-label">Bkash transaction id
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="text" name="txn_number" class="form-control py-2"
                                                            required placeholder="Enter Bkash transaction id">
                                                    </div>
                                                    <div class="col-12 form-group">
                                                        <button type="submit"
                                                            class="btn btn-primary rounded-4 py-2 px-5 shadow-none">
                                                            Request for Review
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
