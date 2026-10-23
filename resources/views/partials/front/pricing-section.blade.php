<section class="pt-2 p-0 mt-3 mb-3 homepricing">
<div class="container">
<!-- row -->
<div class="row align-items-center">
<div class="home_hotkeybox p-0 placeholder-wave">
   <div class="sec_title position-relative mb-3">
	<h3 class="ft-bold">Simple Pricing. Real Business Growth.</h3>
	<p style="font-size:18px; line-height: 30px; color:#000000;">You can cancel your subscription at any time</p>
   </div>
   <div class="row gy-4 gy-xl-0 d-flex justify-content-center">
    @forelse ($plans as $plan)
        @php
            if (isset($plan->plan_type) && $plan->plan_type == 'free' || $plan->plan_type == 'lifetime') {
                $plan->post_duration = 'Lifetime';
            } else{
                $plan->post_duration =  $plan->post_duration.' days';
            }
            $freePlan = isset($plan->plan_type) && $plan->plan_type == 'free';
            $ad_id  = session('ad_id') ;
        @endphp
        <div class="col-md-4">
            <div class="pricing pe-lg-5">
                <div class="price-header">
                    <div class="price-name"> {{ $plan->title }}</div>
                    <div class="price mb-3"> @if($plan->regular_price >0)<i style="font-size:18px; color:#ff0000;"><del>{{ $plan->regular_price }} BDT</del></i> @endif{{ $plan->price }} BDT <br>
                 @if(isset($plan->plan_type) && $plan->plan_type == 'monthly')
                 <span class="badge bg-info">Monthly Fee</span></div>
                 @elseif(isset($plan->plan_type) && $plan->plan_type == 'yearly')
                 <span class="badge bg-info">Yearly Fee</span></div>
                 @elseif(isset($plan->plan_type) && $plan->plan_type == 'lifetime')
                 <span class="badge bg-info">One Time Fee</span></div>
                 @else
                  <span class="badge bg-info">Lifetime Free</span></div>
                  <span class="text-danger"> <i>phone, whatsapp, website, map, video won't see visitors.</i></span>
                 @endif
                </div>

                <div class="plan-features mb-3">
                    <ul class="m-0 p-0">
                        <li>
                            Published for {{ $plan->post_duration }}
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Your business will be published for {{ $plan->post_duration }} on our website.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li class="{{ isset($plan->google_review) && $plan->google_review == '1' ? '' : 'close' }}">
                            Import Google Reviews
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="If you have Google My Business reviews, we will import them and display them on your business details page.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li class="{{ isset($plan->google_map) && $plan->google_map == '1' ? '' : 'close' }}">
                            Google Map
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="This feature will allow you to embed google business map in your business details page.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li class="{{ isset($plan->video_url) && $plan->video_url == '1' ? '' : 'close' }}">
                            Video Url
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="This feature will allow you to add any video link of your business.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li class="{{ isset($plan->public_profile) && $plan->public_profile == '1' ? '' : 'close' }}">
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
                                title="Most visitors come to the listing page. It's a chance to get more customers.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                        <li class="{{ isset($plan->home) && $plan->home == '1' ? '' : 'close' }}">
                            Appear on the Home Page
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="80% of visitors land on the home page. It's a chance to get more customers.">
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
                        <li class="{{ isset($plan->analytics) && $plan->analytics == '1' ? '' : 'close' }}">
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
                        <li class="{{ isset($plan->notification) && $plan->notification == '1' ? '' : 'close' }}">
                            Monthly Visitors Notification
                            <button type="button" class="bg-transparent border-0" data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="You will get monthly analytics reports in your email.">
                                <img src="{{ asset('assets/images/icon/info.svg') }}" alt="info">
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="btn_primary">
                    <a href="{{route('user.login')}}" class="btn btn-sm btn-primary shadow-none w-100">
                        Subscribe & Enlist
                    </a>
                </div>
            </div>
        </div>
    @empty
    @endforelse
</div>

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
</section>
