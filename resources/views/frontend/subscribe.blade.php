@extends('layouts.front')

@push('css')
<style>
.subscribe-hero {
    background: linear-gradient(135deg, #0f7dd8 0%, #2654a1 100%);
    color: #fff;
    border-radius: 24px;
    padding: 34px 28px;
    box-shadow: 0 18px 45px rgba(15, 125, 216, 0.18);
}

.subscribe-hero h1 {
    color: #fff;
    font-weight: 800;
    margin-bottom: 10px;
}

.subscribe-hero p {
    color: rgba(255, 255, 255, 0.92);
    margin-bottom: 0;
    font-size: 18px;
    line-height: 30px;
}

.homepricing .home_hotkeybox ul {
    display: block;
}

.homepricing .pricing .plan-features ul li {
    text-align: left;
}

.homepricing .price-header .badge {
    padding: 3px 8px;
    font-size: 15px;
}
</style>
@endpush

@section('content')
<div class="bg-light py-2">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('front.index') }}">@lang('Home')</a></li>
                        <li class="breadcrumb-item active theme-cl" aria-current="page">Subscribe</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="py-4 py-lg-5">
    <div class="container">
        <div class="subscribe-hero mb-4">
            <h1>Subscribe</h1>
            <p>Pick a plan that fits your business and publish with the same pricing options you see on the home page.</p>
        </div>

        @include('partials.front.pricing-section')
    </div>
</section>
@endsection
