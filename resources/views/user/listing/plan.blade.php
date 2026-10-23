@extends('layouts.front')
@section('stepTwo', 'active')
@push('css')
<style>
    .price-header .badge {padding: 3px 8px;font-size: 15px;}
    .bkash-btn{
    background: #e2136e !important;
    border-color: #e2136e !important;
    color: #fff !important;
    font-weight: 700;
    transition: 0.3s ease;
}
.bkash-btn:hover{
    background: #c70f60 !important;
    border-color: #c70f60 !important;
    color: #fff !important;
    transform: translateY(-1px);
}
.special-offer-box{
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    border-radius: 18px;
    padding: 28px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 35px rgba(255, 75, 43, 0.25);
}

.offer-badge{
    display:inline-block;
    background: rgba(255,255,255,0.2);
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 12px;
}

.offer-title{
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 8px;
    color:#fff;
}

.offer-subtitle{
    color: rgba(255,255,255,0.9);
    font-size: 15px;
}

.offer-countdown{
    text-align:center;
    min-width:220px;
}

.countdown-label{
    font-size:14px;
    margin-bottom:8px;
    font-weight:600;
}

.countdown-timer{
    background:#fff;
    color:#ff416c;
    padding:14px 20px;
    border-radius:12px;
    font-size:28px;
    font-weight:800;
    letter-spacing:2px;
}

.regular-price{
    text-decoration: line-through;
    color:#dc3545;
    font-size:18px;
    font-weight:500;
    margin-bottom:2px;
    font-style:italic;
}

.sale-price{
    font-size:48px;
    font-weight:900;
    color:#111;
    line-height:1;
}

.pricing{
    position: relative;
}

.popular-plan-badge{
    position: absolute;
    top: 14px;
    right: 14px;
    background: #ff9800;
    color: #fff;
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 800;
    line-height: 1;
    text-transform: uppercase;
    box-shadow: 0 5px 14px rgba(255, 152, 0, 0.25);
}

.plan-notification{
    position: fixed;
    bottom: 20px;
    left: 20px;
    width: 340px;
    background: #ecfff3;
    border-left: 5px solid #16c172;
    border-radius: 14px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    z-index: 99999;

    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);

    transition: all .4s ease;
}

.plan-notification.show{
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.plan-notification img,
.notify-avatar{
    width: 55px;
    height: 55px;
    border-radius: 50%;
    object-fit: cover;
}

.notify-avatar{
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 18px;
    color: #fff;
    text-transform: uppercase;
}

.notify-content{
    flex: 1;
}

.notify-content a{
    color: #16c172;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
}

#notifyText{
    font-size: 14px;
    color: #222;
    margin-bottom: 5px;
}
@media(max-width:768px){

.plan-notification{
    width: calc(100% - 30px);
    left: 15px;
    bottom: 15px;
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
                                <a href="javascript:void(0)" class="">@lang('Listing Plan')</a>
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
            <div class="dashboard-widg-bar d-block bg-white rounded-2 p-4">
                <div class="text-center mb-4">
                    <h2 class="pricing_title">Simple Pricing. Real Business Growth.</h2>
                </div>
                 <div class="col-12 mb-3">
                    @if($lifeTimeplan)
                    <div class="special-offer-box mb-4">
                    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between">
                        <div class="offer-left">
                            <div class="offer-badge">
                                🔥 Limited Time Offer
                            </div>
                            <h3 class="offer-title">
                                আজই Premium Plan নিন এবং বিশেষ ডিসকাউন্ট উপভোগ করুন
                            </h3>
                            <p class="offer-subtitle mb-0">
                                Premium Listing নিলে আপনার Business Contact Info সবাই দেখতে পারবে।
                            </p>
                        </div>
                        <div class="offer-countdown mt-3 mt-lg-0">
                            <div class="countdown-label">
                                Offer Ends In
                            </div>
                            <div id="countdownTimer" class="countdown-timer">
                                00d : 00h : 00m : 00s
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                {{--<div class="p-3 border rounded bg-light mt-3">
                        <h5 class="mb-1 text-danger">
                            <i class="fa fa-lock"></i> আপনি যদি ফ্রি প্ল্যান ব্যবহার করেন
                        </h5>
                        <small class="text-muted" style="font-size:17px;">
                            তাহলে ফোন নম্বর, হোয়াটসঅ্যাপ নম্বর, ওয়েবসাইট লিংক, ভিডিও এবং ম্যাপ লোকেশনসহ এই তথ্যগুলো পাবলিক ভিউ পেজে কাস্টমারদের কাছে প্রদর্শিত হবে না।
এসব তথ্য লক করা থাকবে। পেইড প্ল্যান গ্রহণ করলে তবেই কাস্টমাররা এই তথ্যগুলো দেখতে পারবেন।
                        </small>
                    </div> --}}
                </div> 
                <div class="row gy-4 gy-xl-0 d-flex justify-content-center">
                    @forelse ($plans as $plan)
                        @php
                            $bkashPricing = app(\App\Services\BkashCheckoutService::class);
                            $durationLabel = (isset($plan->plan_type) && ($plan->plan_type == 'free' || $plan->plan_type == 'lifetime'))
                                ? 'Lifetime'
                                : $bkashPricing->planDurationDays($plan) . ' days';
                            $freePlan = isset($plan->plan_type) && $plan->plan_type == 'free';
                            $ad_id  = session('ad_id') ;
                            $displayAmount = $bkashPricing->displayAmount($plan);
                            $billingLabel = $bkashPricing->billingLabel($plan);
                        @endphp 
                        <div class="col-md-4 mb-2">
                            <div class="pricing pe-lg-5">
                                @if(isset($plan->plan_type) && $plan->plan_type == 'lifetime')
                                    <span class="popular-plan-badge">{{ __('Most Popular') }}</span>
                                @endif
                                <div class="price-header">
                                    <div class="price-name">{{ $plan->title }}</div>
                                  <div class="price mb-3">
                                     @if($plan->regular_price >0)<span class="regular-price">{{ $plan->regular_price }} BDT</span>@endif
                                     {{ number_format($displayAmount, $displayAmount == floor($displayAmount) ? 0 : 2) }} BDT
                                     @if(isset($plan->plan_type) && $plan->plan_type == 'monthly')
                                        <span style="font-size:18px;font-weight:600;">/month</span>
                                     @endif
                                     <br>
                                     @if($billingLabel)
                                        <small class="d-block text-muted mt-1">{{ $billingLabel }}</small>
                                     @endif


                                    @if(isset($plan->plan_type) && $plan->plan_type == 'monthly')
                                    <span class="badge bg-info">Yearly billing</span>
                                 </div>
                                 @elseif(isset($plan->plan_type) && $plan->plan_type == 'yearly')
                                 <span class="badge bg-info">Yearly Fee</span></div>
                                 @elseif(isset($plan->plan_type) && $plan->plan_type == 'lifetime')
                                 <span class="badge bg-info">One Time Fee</span></div>
                                 @else
                                  <span class="badge bg-info">Lifetime Free</span>
                              </div>
                              <span class="text-danger"> <i>phone, whatsapp, website, map, video won't see visitors.</i></span>
                                 @endif
                                </div>

                                <div class="plan-features mb-3">
                                    <ul class="m-0 p-0">
                                        <li>
                                            Published for {{ $durationLabel }}
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Your business will be published for {{ $durationLabel }} on our website.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li
                                            class="{{ isset($plan->ai_assistant) && $plan->ai_assistant == '1' ? '' : 'close' }}">
                                            Mona Ai Assistant
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Your business can appear in Mona AI search results when customers look for services in your area.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li
                                            class="{{ isset($plan->google_review) && $plan->google_review == '1' ? '' : 'close' }}">
                                            Import Google Reviews
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="If you have Google My Business reviews, we will import them and display them on your business details page.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li
                                            class="{{ isset($plan->google_map) && $plan->google_map == '1' ? '' : 'close' }}">
                                            Google Map
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="This feature will allow you to embed google business map in your business details page.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li
                                            class="{{ isset($plan->video_url) && $plan->video_url == '1' ? '' : 'close' }}">
                                            Video Url
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="This feature will allow you to add any video link of your business.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li
                                            class="{{ isset($plan->public_profile) && $plan->public_profile == '1' ? '' : 'close' }}">
                                            Public Branding Page
                                            <button type="button" class="bg-transparent border-0"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="A personalized public page to show all your business listings.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li class="{{ isset($plan->features) && $plan->features == '1' ? '' : 'close' }}">
                                            Featured Badge
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="You will get a special green badge that will increase your customer trust.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li class="{{ isset($plan->listing) && $plan->listing == '1' ? '' : 'close' }}">
                                            Appear on the Top Position of the Listing Page
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Most visitors come to the listing page. It’s a chance to get more customers.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li class="{{ isset($plan->home) && $plan->home == '1' ? '' : 'close' }}">
                                            Appear on the Home Page
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="80% of visitors land on the home page. It’s a chance to get more customers.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li class="{{ isset($plan->follow) && $plan->follow == '1' ? '' : 'close' }}">
                                            Do-Follow Attribute
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Get credit from Google for SEO.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li
                                            class="{{ isset($plan->analytics) && $plan->analytics == '1' ? '' : 'close' }}">
                                            Analytics
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="In the user dashboard, you will get an analytics menu to track customers.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li class="{{ isset($plan->indexing) && $plan->indexing == '1' ? '' : 'close' }}">
                                            Urgent Indexing
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="We will request in Google Search Console for indexing.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                        <li
                                            class="{{ isset($plan->notification) && $plan->notification == '1' ? '' : 'close' }}">
                                            Monthly Visitors Notification
                                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="You will get monthly analytics reports in your email.">
                                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                               <div class="buy">
                                <a href="{{ (isset($listing->plan_id) && $listing->plan_id == $plan->id) ? '#' : route('user.listing.plan.checkout', $plan->id) }}"
                                    class="btn btn-sm shadow-none w-100 {{ isset($listing->plan_id) && $listing->plan_id == $plan->id ? 'disabled' : '' }} 
                                    {{ !$freePlan ? 'bkash-btn' : 'btn-primary' }}"
                                    @if (!$freePlan)
                                        data-bkash-pay
                                    @endif
                                    @if ($freePlan)
                                        onclick="return confirm('Currently are are not approving free listing. You have to take paid plan')"
                                    @endif>
                                    @if (isset($listing->status) && $listing->status == 3 && $listing->plan_id == $plan->id)
                                        <i class="fas fa-clock me-2"></i>
                                        Requires Review
                                    @elseif (isset($listing->plan_id) && $listing->plan_id == $plan->id)
                                        <i class="fas fa-check-circle me-2"></i>
                                        Published
                                    @elseif ($freePlan)
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Publish Now 
                                    @else
                                        <img src="{{ asset('assets/images/bkashicon.png') }}"alt="bKash" style="height:32px; width:auto;">
                                        Pay with bKash
                                    @endif

                                </a> 
                            </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                    <div class="bkash-trust mt-4 rounded-4 border bg-white px-3 py-3 py-md-4 mx-auto" style="max-width: 720px;">
                    <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap text-center text-md-start">
                        <img src="{{ asset('assets/images/bkashicon.png') }}" alt="bKash" style="height:38px; width:auto;">
                        <div>
                            <div class="fw-bold">Secure payment with bKash</div>
                            <div style="font-size:12px; color:#6c757d;">Trusted and secure subscription payment for your business.</div>
                        </div>
                        <p><strong>Trade License No: TRAD/DNCC/003678/2026</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
@push('js')
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        document.querySelectorAll('[data-bkash-pay]').forEach(function(button) {
            button.addEventListener('click', function() {
                if (button.classList.contains('disabled')) {
                    return false;
                }

                button.classList.add('disabled');
                button.setAttribute('aria-disabled', 'true');
                button.innerHTML = 'Redirecting to bKash...';
            });
        });
    </script>
<script>

const countdownDays = 10;

/*
|--------------------------------------------------------------------------
| FIXED START DATE
|--------------------------------------------------------------------------
|
| Change this whenever you want to restart campaign
|
*/

const startDate = new Date("2026-05-15T00:00:00").getTime();

const countdownDuration = countdownDays * 24 * 60 * 60 * 1000;

function updateCountdown()
{
    const now = new Date().getTime();

    /*
    |--------------------------------------------------------------------------
    | ELAPSED TIME
    |--------------------------------------------------------------------------
    */

    const elapsed = now - startDate;

    /*
    |--------------------------------------------------------------------------
    | CURRENT CYCLE
    |--------------------------------------------------------------------------
    */

    const distance = countdownDuration - (elapsed % countdownDuration);

    /*
    |--------------------------------------------------------------------------
    | TIME CALCULATION
    |--------------------------------------------------------------------------
    */

    const days = Math.floor(
        distance / (1000 * 60 * 60 * 24)
    );

    const hours = Math.floor(
        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );

    const minutes = Math.floor(
        (distance % (1000 * 60 * 60)) / (1000 * 60)
    );

    const seconds = Math.floor(
        (distance % (1000 * 60)) / 1000
    );

    /*
    |--------------------------------------------------------------------------
    | OUTPUT
    |--------------------------------------------------------------------------
    */

    document.getElementById("countdownTimer").innerHTML =
        days + " Days : " +
        String(hours).padStart(2, '0') + "h : " +
        String(minutes).padStart(2, '0') + "m : " +
        String(seconds).padStart(2, '0') + "s";
}

setInterval(updateCountdown, 1000);

updateCountdown();

</script>
<script>

let notifications = @json($notifications);

let currentIndex = 0;

const avatarColors = [
    '#16a34a',
    '#2563eb',
    '#9333ea',
    '#ea580c',
    '#dc2626',
    '#0891b2',
    '#ca8a04'
];

function getInitials(name) {

    let words = name.trim().split(' ');

    if(words.length === 1){
        return words[0].substring(0,2);
    }

    return (
        words[0].charAt(0) +
        words[1].charAt(0)
    );
}

function getRandomColor() {
    return avatarColors[
        Math.floor(Math.random() * avatarColors.length)
    ];
}

function showNotification() {

    if(notifications.length === 0){
        return;
    }

    let item = notifications[currentIndex];

    let message = `
        <strong>${item.name}</strong>
        recently upgraded to
        <strong>${item.plan_name}</strong>.
    `;

    document.getElementById('notifyText')
        .innerHTML = message;

    document.getElementById('notifyLink')
        .href = `/details/${item.slug}`;

    let photoElement =
        document.getElementById('notifyPhoto');

    let avatarElement =
        document.getElementById('notifyAvatar');

    // IF PHOTO EXISTS
    if(item.photo && item.photo !== 'NULL'){

        photoElement.src =
            `/assets/images/${item.photo}`;

        photoElement.style.display = 'block';

        avatarElement.style.display = 'none';

    }else{

        photoElement.style.display = 'none';

        avatarElement.style.display = 'flex';

        avatarElement.innerHTML =
            getInitials(item.name);

        avatarElement.style.background =
            getRandomColor();
    }

    let popup =
        document.getElementById('planNotification');

    popup.classList.add('show');

    // sound
    document.getElementById('notifySound').play();

    setTimeout(() => {
        popup.classList.remove('show');
    }, 5000);

    currentIndex++;

    if(currentIndex >= notifications.length){
        currentIndex = 0;
    }
}

// Start after 5 sec
setTimeout(() => {

    showNotification();

    setInterval(() => {
        showNotification();
    }, 12000);

}, 5000);

</script>
@endpush
