@php $area_query = $area_query ?? 'bangladesh'; @endphp
<style>
.footer_social_list{list-style:none;gap:8px}
.footer_social_list li{padding:0 !important}
.footer_social_list li a{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:#f1f3f6;color:#555;font-size:16px;text-decoration:none;transition:all .2s ease}
.footer_social_list li a:hover{background:#0076d7;color:#fff}
</style>
<footer class="light-footer skin-light-footer style-2 pt-4 pb-4">
<div class="footer-middle">
<div class="container">
<div class="row gy-4 gy-lg-0 mb-4 text-center text-sm-start">
<div class="col-sm-6 col-md-4 col-lg-3 footer_wrap">
<h6>Links</h6>
<ul class="p-0">





<li><a href="{{ route('front.listing') }}">Bangladesh Business Directory</a></li>
<li><a href="{{ route('front.listing') }}">Business Directory List Bangladesh</a></li>
<li><a href="{{ route('front.listing') }}">Online Listing Website Bangladesh</a></li>
<li><a href="{{ route('user.login') }}">Free Business Listing Bangladesh</a></li>
<li><a href="{{ route('front.listing') }}">Yellow Pages Dhaka</a></li>
<li><a href="{{ route('front.public-holiday') }}">Public Holidays in Bangladesh</a></li>
<li><a href="{{ route('front.blog') }}">@lang('Blogs')</a></li>
</ul>
</div>
<div class="col-sm-6 col-md-4 col-lg-3 footer_wrap">
<h6>Users</h6>
<ul class="p-0">
<li><a href="{{ route('user.listing.create') }}">Add Business</a></li>
{{--<li><a href="{{ route('front.subscribe.page') }}">Subscribe</a></li> --}}
<li><a href="{{ route('user.login') }}">Sign in</a></li>
<li><a href="{{ route('user.register') }}">Sign Up</a></li>
<li><a href="{{ route('front.contact') }}">Contact us</a></li>
</ul>
</div>
<div class="col-sm-6 col-md-4 col-lg-3 footer_wrap footer_categories">
<h6>Main Categories</h6>
<ul class="p-0">
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => 'universities']) }}">Universities</a></li>
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => 'hotels']) }}">Hotels</a></li>
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => 'schools']) }}">Schools</a></li>
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => 'lawyers']) }}">Lawyers</a></li>
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => 'courier-services']) }}">Courier Services</a></li>
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => 'hospitals-clinics']) }}">Hospitals &amp; Clinics</a></li>
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => 'insurance-companies']) }}">Insurance Companies</a></li>
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => 'banks-credit-unions']) }}">Banks</a></li>
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => 'restaurants']) }}">Restaurants</a></li>
<li><a href="{{ route('front.listing', ['area' => $area_query, 'category' => 'tours-travels']) }}">Tours &amp; Travels</a></li>
</ul>
</div>
<div class="col-sm-6 col-lg-3 footer_wrap">
<h6>Database</h6>
@php
$galleries = DB::table('galleries')->count();
$photo = DB::table('listings')->where('status', 1)->whereNotNull('photo')->count();
$total_photos = $galleries + $photo;
$total_reviews = DB::table('listing_reviews')->where('status', 1)->count();
$total_products = DB::table('listings')->where('status', 1)->count();
$total_locations = DB::table('areas')->where('status', 1)->count();
@endphp
<ul class="p-0" id="ss">
<li><span>{{ $total_products }}</span>Companies</li>
<li><span>{{ $total_photos }}</span>Photos</li>
<li><span>{{ $total_reviews }}</span>Reviews</li>
<li><span>{{ $total_locations }}</span>Locations</li>
</ul>
@php $footer_socials = collect($sociallinks ?? [])->where('status', 1); @endphp
@if ($footer_socials->count() > 0)
<div class="footer_social mt-3">
<h6>@lang('Follow us on')</h6>
<ul class="d-flex flex-wrap p-0 m-0 footer_social_list">
@foreach ($footer_socials as $sociallink)
<li><a href="{{ $sociallink->link }}" target="_blank" rel="noopener noreferrer" title="{{ $sociallink->name }}" aria-label="{{ $sociallink->name }}"><i class="{{ $sociallink->icon }}"></i></a></li>
@endforeach
</ul>
</div>
@endif
</div>
</div>
<div class="row"> 
	@if (request()->is('/'))
	    <div class="footertext mb-3">
			<p>BDTalika is a leading Bangladesh business directory and online listing website that helps users find businesses, services, and companies across the country. Whether you are looking for restaurants, hospitals, beauty salons, hotels, or corporate services, our Bangladesh business directory list makes it easy to discover trusted businesses.</p> 
			<p>Our platform also works as a Yellow Pages Bangladesh where companies can create free business listings and reach thousands of customers every day. If you own a business, you can submit your company to our free business directory Bangladesh and grow your online presence.</p>
	</div>
	@endif
<div class="d-md-flex justify-content-center align-items-center text-center">
<div class="footer_widget mb-3 mb-xl-0 me-0 me-md-5">
<ul class="footer-menu">
<li class="me-2"><strong>@lang('Main Navigation')</strong></li>
<li><a href="{{ route('front.listing') }}">@lang('Explore Listings')</a> |</li>
{{-- <li><a href="{{ route('front.plans') }}">@lang('Pricing Plan')</a></li> --}}
<li><a target="_blank" href="{{ route('front.blog') }}">@lang('Blogs')</a> |</li>
<li><a href="{{ route('front.contact') }}">@lang('Contact')</a> |</li>
<li><a href="{{ route('sitemap') }}">@lang('Sitemap')</a></li>
</ul>
</div>
<div class="footer_widget">
<ul class="footer-menu">
<li class="me-2"><strong>@lang('Helpful Topics')</strong></li>
@foreach ($pages as $key => $page)
<li><a href="{{ route('front.page', $page->slug) }}">{{ $page->title }}</a></li> |
@endforeach
{{-- <li><a href="{{ url('/sitemap') }}">@lang('Sitemap')</a></li> --}}
<li><a href="{{ route('front.public-holiday') }}">@lang('Public Holidays in Bangladesh')</a></li>
</ul>
</div>
</div>
</div>
</div>
</div>
<div class="footer-bottom" style="border:none !important;">
<div class="container">
<div class="row align-items-center">
<div class="col-lg-12 col-md-12 text-center">
@php echo $gs->copyright; @endphp
<p><strong>Trade License No: TRAD/DNCC/003678/2026</strong></p>
</div>
</div>
</div>
</div>
</footer>
