@if ($gs->theme == 'theme-3' || $gs->theme == 'theme-5')
<div class="header {{ request()->path() == '/' ? 'header-transparent change-logo' : 'header-light dark-text head-border' }}">
<div class="container">
<nav id="navigation" class="navigation navigation-landscape">
<div class="nav-header">
{{-- for dahshboard in  --}}
<a class="nav-brand static-logo" href="{{ route('front.index') }}">
<img src="{{ asset('assets/images/' . $gs->light_logo) }}" class="logo" alt="bdtalika">
</a>
<a class="nav-brand fixed-logo" href="{{ route('front.index') }}">
<img src="{{ asset('assets/images/' . $gs->logo) }}" class="logo" alt="bdtalika">
</a>
{{-- for dahshboard in  --}}
<div class="nav-toggle d-none"></div>
<div class="mobile_nav">
<ul>
@auth
<li><a href="{{ route('user.dashboard') }}" class="theme-cl fs-lg"><i class="lni lni-user"></i></a></li>
@else
<li><a href="{{ route('user.login') }}" class="theme-cl fs-lg"><i class="lni lni-user"></i></a></li>
@endauth
</ul>
</div>
</div>
<div class="nav-menus-wrapper" style="transition-property: none;">
<ul class="nav-menu">
@foreach (json_decode($gs->menu, true) as $key => $menue)
<li><a href="{{ url($menue['href']) }}" target="{{ $menue['target'] == 'blank' ? '_blank' : '_self' }}">{{ $menue['title'] }}</a> </li>
@endforeach
<li><a href="{{ route('front.subscribe.page') }}">Pricing</a></li>
</ul>

<ul class="nav-menu nav-menu-social align-to-right">
@auth
<li><a href="javascript:void(0);">@lang('My account')</a>
<ul class="nav-dropdown nav-submenu">
<li><a href="{{ route('user.dashboard') }}"><i class="lni lni-dashboard me-2"></i>@lang('Dashboard')</a></li>
<li><a href="{{ route('user.change.password.form') }}"><i class="lni lni-lock-alt me-2"></i>@lang('Change Password')</a></li>
<li><a href="{{ route('front.subscribe.page') }}"><i class="lni lni-money-protection me-2"></i>Pricing</a></li> 
<li><a href="{{ route('user.logout') }}"><i class="lni lni-dashboard me-2"></i>@lang('Logout')</a></li>
</ul>
</li>
<li class="add-listing">
<a href="{{ route('user.listing.type') }}">
<i class="fas fa-plus me-2"></i>@lang('Add Listing')
</a>
</li>
@else
<li>
<a href="{{ route('user.login') }}" class="ft-bold">
<i class="fas fa-sign-in-alt me-1 theme-cl"></i>@lang('Sign In')
</a>
</li>
@endauth
</ul>
</div>
</nav>
</div>
</div>
@else
<div class="header header-light dark-text sticky-top">
<div class="container">
<nav id="navigation" class="navigation navigation-landscape">
<div class="nav-header">
<a class="nav-brand p-0" href="{{ url('/') }}"> <img src="{{ asset('assets/images/' . $gs->logo) }}" class="logo" alt="bdtalika"> </a>
<div class="nav-toggle d-none"></div>
<div class="mobile_nav">
<ul class="nav-menu">
{{-- <li>
<a href="{{ route('front.advertise') }}">Business Advertising</a> 
</li>  --}}
<li><a href="{{ route('front.category.directory') }}">Browse Categories</a></li> 
@foreach (json_decode($gs->menu, true) as $key => $menue)
<li><a href="{{ url($menue['href']) }}" class="ft-bold" target="{{ $menue['target'] == 'blank' ? '_blank' : '_self' }}">{{ $menue['title'] }}</a></li>
@endforeach
@auth
<li> <a href="{{ route('user.dashboard') }}" class="ft-bold">@lang('Dashboard')</a></li>
@else
<li><a href="{{ route('user.login') }}" class="theme-cl fs-lg"><i class="lni lni-user"></i></a></li>
@endauth
</ul>
</div>
</div>
<div class="nav-menus-wrapper" style="transition-property: none;">
<ul class="nav-menu">
@foreach (json_decode($gs->menu, true) as $key => $menue)
<li>
<a href="{{ url($menue['href']) }}" target="{{ $menue['target'] == 'blank' ? '_blank' : '_self' }}">{{ $menue['title'] }} </a> </li>
@endforeach
{{--<li><a href="{{ route('front.subscribe.page') }}" class="ft-bold">Pricing</a></li>  --}}
</ul>
<ul class="nav-menu nav-menu-social align-to-right">
{{-- <li>
<a href="{{ route('front.advertise') }}">Business Advertising</a> 
</li>  --}}
<li><a href="{{ route('front.category.directory') }}">Categories</a> </li> 
<li>
    <a href="{{ route('front.blog') }}">Blogs</a> 
</li> 
@auth
<li>
<a href="{{ route('user.listing.create') }}" class="business_btn ft-bold">
<p class="bg-danger text-uppcase">Business</p>
<svg xmlns="http://www.w3.org/2000/svg" width="20.314" height="29.736"
viewBox="0 0 20.314 29.736">
<defs>
<style>
.cls-1 {
fill: #fff;
opacity: 0;
}

.cls-2 {
fill: #111;
stroke: rgba(255, 255, 255, 0);
}
</style>
</defs>
<g id="Cart" transform="translate(-945.188 -38)">
<g id="Group_7056" data-name="Group 7056" transform="translate(935 38)">
<g id="Group_6423" data-name="Group 6423">
<path id="Path_3272" data-name="Path 3272" class="cls-1"
d="M0,0H20V29.736H0Z" transform="translate(10.188)" />
</g>
</g>
<path id="Path_3479" data-name="Path 3479" class="cls-2"
d="M1805,5304.592v-11.023a.587.587,0,0,1,1.174,0V5304h10.166a.587.587,0,0,1,.035,1.174.136.136,0,0,1-.035,0h-10.753A.589.589,0,0,1,1805,5304.592Zm2.459-2.823a.575.575,0,0,1-.251-.371.556.556,0,0,1,.1-.43l2.29-3.219a.6.6,0,0,1,.433-.25.614.614,0,0,1,.473.157l1.992,1.849,2.972-4.1-1.461.1a.591.591,0,0,1-.642-.53.559.559,0,0,1,.142-.421.6.6,0,0,1,.41-.2l2.879-.21a.6.6,0,0,1,.437.138.562.562,0,0,1,.2.394l.217,2.769a.584.584,0,0,1-.543.623h-.007a.677.677,0,0,1-.451-.145.532.532,0,0,1-.19-.385l-.1-1.349-3.278,4.522a.616.616,0,0,1-.434.243.607.607,0,0,1-.473-.157l-1.986-1.845-1.892,2.654a.617.617,0,0,1-.489.251A.653.653,0,0,1,1807.46,5301.77Z"
transform="translate(-852.654 -5246.141)" />
</g>
</svg>
@lang('Free Listing')
</a>
</li>
<li><a href="javascript:void(0);">@lang('My account')</a>
<ul class="nav-dropdown nav-submenu">
<li><a href="{{ route('user.dashboard') }}"><i class="lni lni-dashboard me-2"></i>@lang('Dashboard')</a></li>
<li><a href="{{ route('user.change.password.form') }}"><i class="lni lni-lock-alt me-2"></i>@lang('Change Password')</a></li>
{{--<li><a href="{{ route('front.subscribe.page') }}"><i class="lni lni-money-protection me-2"></i>Subscribe</a></li> --}}
<li><a href="{{ route('user.logout') }}"><i class="lni lni-dashboard me-2"></i>@lang('Logout')</a></li>
</ul>
</li>
{{-- <li class="add-listing"><a href="{{ route('user.listing.type') }}"><i class="fas fa-plus me-2"></i>@lang('Add Listing')</a></li> --}}
@else
{{-- <li><a href="{{ route('user.login') }}" class="ft-bold"><i class="fas fa-sign-in-alt me-1 theme-cl"></i>@lang('Sign In')</a></li> --}}
<li>
<a href="{{ route('user.login') }}" class="business_btn ft-bold">
<p class="bg-danger text-uppcase">Business</p>
<svg xmlns="http://www.w3.org/2000/svg" width="20.314" height="29.736"
viewBox="0 0 20.314 29.736">
<defs>
<style>
.cls-1 {
fill: #fff;
opacity: 0;
}

.cls-2 {
fill: #111;
stroke: rgba(255, 255, 255, 0);
}
</style>
</defs>
<g id="Cart" transform="translate(-945.188 -38)">
<g id="Group_7056" data-name="Group 7056" transform="translate(935 38)">
<g id="Group_6423" data-name="Group 6423">
<path id="Path_3272" data-name="Path 3272" class="cls-1"
d="M0,0H20V29.736H0Z" transform="translate(10.188)" />
</g>
</g>
<path id="Path_3479" data-name="Path 3479" class="cls-2"
d="M1805,5304.592v-11.023a.587.587,0,0,1,1.174,0V5304h10.166a.587.587,0,0,1,.035,1.174.136.136,0,0,1-.035,0h-10.753A.589.589,0,0,1,1805,5304.592Zm2.459-2.823a.575.575,0,0,1-.251-.371.556.556,0,0,1,.1-.43l2.29-3.219a.6.6,0,0,1,.433-.25.614.614,0,0,1,.473.157l1.992,1.849,2.972-4.1-1.461.1a.591.591,0,0,1-.642-.53.559.559,0,0,1,.142-.421.6.6,0,0,1,.41-.2l2.879-.21a.6.6,0,0,1,.437.138.562.562,0,0,1,.2.394l.217,2.769a.584.584,0,0,1-.543.623h-.007a.677.677,0,0,1-.451-.145.532.532,0,0,1-.19-.385l-.1-1.349-3.278,4.522a.616.616,0,0,1-.434.243.607.607,0,0,1-.473-.157l-1.986-1.845-1.892,2.654a.617.617,0,0,1-.489.251A.653.653,0,0,1,1807.46,5301.77Z"
transform="translate(-852.654 -5246.141)" />
</g>
</svg>
@lang('Free Listing')
</a>
</li>
<li class="btn_primary">
<a href="{{ route('user.login') }}" class="text-white">
@lang('Login / Sign Up')
</a>
</li>
{{-- <li class="add-listing"><a href="{{ route('user.listing.type') }}" ><i class="fas fa-plus me-2"></i>@lang('Add Listing')</a></li> --}}
@endauth
</ul>
</div>
</nav>
</div>
</div>
@endif
