<div id="tabs">
<ul class="nav nav-pills nav-justified bg-light" role="tablist">
<li class="nav-item">
<a href="javascript:void(0)" class="nav-link bsc @yield('stepOne')">
{{ __('Step One') }}
<svg class="ph tn axo" viewBox="0 0 12 82" fill="none" preserveAspectRatio="none">
<path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentcolor" vector-effect="non-scaling-stroke">
</path>
</svg>
</a>
</li>
<li class="nav-item">
<a href="javascript:void(0)" class="nav-link @yield('stepTwo')">
{{ __('Step Two') }}
<svg class="ph tn axo" viewBox="0 0 12 82" fill="none" preserveAspectRatio="none">
<path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentcolor" vector-effect="non-scaling-stroke">
</path>
</svg>
</a>
</li>
<li class="nav-item">
<a href="javascript:void(0)" class="nav-link @yield('stepThree')">
{{ __('Step Three') }}
</a>
</li>
</ul>
</div>
