<div class="dashboard-nav border bg-white rounded-3 shadow-sm sticky-top">
<div class="user_profile">
<div class="user_photo mb-2">
<img src="{{ auth()->user()->photo ? asset('assets/images/' . auth()->user()->photo) : asset('assets/front/img/t-7.png') }}"
class="object-fit-cover border rounded" width="140" alt="{{ auth()->user()->name }}">
</div>
{{-- <div>
<div class="dashploio"><h4>{{ auth()->user()->name }}</h4></div>
@if (isset(Auth::user()->address))
<div class="dashploio"><span class="agd-location"><i class="lni lni-map-marker me-1"></i>{{ auth()->user()->address }}</span></div>
@endif
</div>
--}}
</div>
<div class="dashboard-inner">
<ul data-submenu-title="Main Navigation" class="mt-0">
<li class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}"><a href="{{ route('user.dashboard') }}"><i class="lni lni-dashboard me-2"></i>@lang('Dashboard')</a>
</li>
{{-- <li class="{{ request()->routeIs('user.transaction') ? 'active' : '' }}"><a href="{{ route('user.transaction') }}"><i class="lni lni-stats-up me-2"></i>@lang('Transactions')</a></li> --}}
<li class="{{ request()->routeIs('user.listing.create') ? 'active' : '' }}">
<a href="{{ route('user.listing.create') }}"><i class="lni lni-add-files me-2"></i>@lang('Add Listing')</a>
</li>
<li class="{{ request()->routeIs('user.listing.index') ? 'active' : '' }}">
<a href="{{ route('user.listing.index') }}"><i class="lni lni-files me-2"></i>@lang('My Listings')</a>
</li>
<li class="{{ request()->routeIs('user.customer.enquiry') ? 'active' : '' }}">
<a href="{{ route('user.customer.enquiry') }}"><i class="lni lni-envelope me-2"></i>@lang('Customer Enquiry')</a>
</li>
<li class="{{ request()->routeIs('user.saved.listing') ? 'active' : '' }}">
<a href="{{ route('user.saved.listing') }}"><i class="lni lni-bookmark me-2"></i>@lang('Saved Listing')</a>
</li>
{{-- <li class="{{ request()->routeIs('user.my.booking') ? 'active' : '' }}"><a
href="{{ route('user.my.booking') }}"><i class="lni lni-briefcase me-2"></i>@lang('My Bookings')</a>
</li> 
<li class="{{ request()->routeIs('user.pricing.plans') ? 'active' : '' }}"><a href="{{ route('user.pricing.plans') }}"><i class="lni lni-money-protection me-2"></i>@lang('Pricing Plan')</a></li> 
@if ($ticket = DB::table('admin_user_conversations')->orderBy('id', 'desc')->first())
<li class="{{ request()->routeIs('user.message.index') ? 'active' : '' }}"><a
href="{{ route('user.message.index', ['ticket' => $ticket->id]) }}"><i
class="lni lni-envelope me-2"></i>@lang('Messages')</a></li>
@else
<li class="{{ request()->routeIs('user.message.index') ? 'active' : '' }}"><a
href="{{ route('user.message.index') }}"><i
class="lni lni-envelope me-2"></i>@lang('Messages')</a></li>
@endif --}}
</ul>

{{-- <ul data-submenu-title="Booking Requestes">
<li class="{{ request()->routeIs('user.booking.hotel.request') ? 'active' : '' }}"><a
href="{{ route('user.booking.hotel.request') }}"><i
class="lni lni-apartment me-2"></i>@lang('Hotel') </a></li>
<li class="{{ request()->routeIs('user.booking.restaurant.request') ? 'active' : '' }}"><a
href="{{ route('user.booking.restaurant.request') }}"><i
class="lni lni-restaurant me-2"></i>@lang('Restaurant')</a></li>
<li class="{{ request()->routeIs('user.booking.beauty.request') ? 'active' : '' }}"><a
href="{{ route('user.booking.beauty.request') }}"><i
class="lni lni-pallet me-2"></i>@lang('Beauty')</a></li>
</ul>

<ul data-submenu-title="Directory Enquiry">
<li class="{{ request()->routeIs('user.customer.enquiry') ? 'active' : '' }}"><a
href="{{ route('user.customer.enquiry') }}"><i
class="lni lni-pencil-alt me-2"></i>@lang('Customer Enquiry') </a></li>
<li class="{{ request()->routeIs('user.my.enquiry') ? 'active' : '' }}"><a
href="{{ route('user.my.enquiry') }}"><i class="lni lni-library me-2"></i>@lang('My Enquiry')</a>
</li>
</ul>  --}}

<ul data-submenu-title="My Accounts">
<li class="{{ request()->routeIs('user.transaction') ? 'active' : '' }}"><a href="{{ route('user.transaction') }}"><i class="lni lni-dollar me-2"></i>@lang('Transaction') </a></li>
<li class="{{ request()->routeIs('user.listing.analytics') ? 'active' : '' }}"><a href="{{ route('user.listing.analytics') }}"><i class="lni lni-stats-down me-2"></i>@lang('Analytics') </a></li>
<li class="@yield('ticket')"><a href="{{ route('user.ticket.index') }}"><i class="lni lni-ticket me-2"></i>@lang('Support Tickets') </a></li>
<li class="{{ request()->routeIs('user.profile.index') ? 'active' : '' }}"><a href="{{ route('user.profile.index') }}"><i class="lni lni-user me-2"></i>@lang('My Profile') </a>
</li>
<li class="{{ request()->routeIs('user.change.password.form') ? 'active' : '' }}"><a href="{{ route('user.change.password.form') }}"><i class="lni lni-lock-alt me-2"></i>@lang('Change Password')</a></li>
<li>
<a href="{{ route('front.index') }}"><i class="lni lni-home me-2"></i>@lang('Back to Homepage')</a>
</li>
<li class="{{ request()->routeIs('user.logout') ? 'active' : '' }}"><a href="{{ route('user.logout') }}"><i class="lni lni-power-switch me-2"></i>@lang('Log Out')</a>
</li>
</ul>
</div>
</div>
