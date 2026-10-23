@php
$get_category_query = $get_category_query ?? null;
$area_query = $area_query ?? 'bangladesh';
@endphp
<!-- Location Modal -->
<div class="custom_modal location_modal">
<div class="modal fade" id="location_modal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="location_modalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="location_modalLabel">
<span id="go_back" style="display: none">
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M5 12l14 0" />
<path d="M5 12l6 6" />
<path d="M5 12l6 -6" />
</svg>
</span>
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin"
width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
fill="none" stroke-linecap="round" stroke-linejoin="round">
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
<path
d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
</svg>
Select City or Division
</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M18 6l-12 12" />
<path d="M6 6l12 12" />
</svg>
</button>
</div>
<div class="modal-body modal_list">
<div class="row">
<div class="col-lg-6">
{{-- city  --}}
<div class="custom_modal">
<div class="row">
<div class="listItem">
<div class="pt-2">
<span class="ps-2 text-dark">
<strong style="font-size: 17px; font-weight: 500;">Cities</strong>
</span>
</div>
<ul class="nav nav-pills mb-3" id="pills-tab2" role="tablist"
style="display: block !important;">
@if (isset($cities) && $cities->count() > 0)
@foreach ($cities->take(10) as $key => $city)
<li class="nav-item">
<a class="nav-link cityClick {{ $city->ads->count() == '0' ? 'disabled' : '' }}" id="v-pills-{{ $city->id }}-tab" data-bs-toggle="pill" href="#v-pills-{{ $city->id }}" role="tab" aria-controls="v-pills-{{ $city->id }}" aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
{{ Str::ucfirst($city->name) }} ({{ $city->ads->count() }})
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
<path stroke="none" d="M0 0h24v24H0z" fill="none" />
<path d="M9 6l6 6l-6 6" />
</svg>
</a>
</li>
@endforeach
<li>
<a class="nav-link border-0 d-block pt-3" href="{{ route('front.cities.directory') }}">
<strong>View all Cities</strong>
</a>
</li>
@endif
</ul>
</div>
</div>
</div>
{{-- Divisions  --}}
<div class="custom_modal">
<div class="row">
<div class="listItem">
<div class="pt-2 pb-2">
<span class="ps-2 text-dark">
<strong style="font-size: 17px; font-weight: 500;">Divisions</strong>
</span>
</div>
<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist" style="display: block !important;">
@if (isset($divisions) && $divisions->count() > 0)
@foreach ($divisions as $key => $division)
<li class="nav-item">
<a class="nav-link divisionClick {{ $division->listings->count() == '0' ? 'disabled' : '' }} {{ $area_query == $division->slug ? 'active' : '' }}" id="v-pills-{{ $division->id }}-tab" data-bs-toggle="pill" href="#v-pills-{{ $division->id }}" role="tab" aria-controls="v-pills-{{ $division->id }}" aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
{{ $division->name }} ({{ $division->listings->count() }})
<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
<path stroke="none" d="M0 0h24v24H0z"
fill="none" />
<path d="M9 6l6 6l-6 6" />
</svg>
</a>
</li>
@endforeach
@endif
</ul>
</div>
</div>
</div>
</div>
<div class="col-lg-6">
{{-- city wise area --}}
<div class="tab-content cityTab" id="pills-tabContent2">
@if (isset($cities) && $cities->count() > 0)
@foreach ($cities as $key => $city)
<div class="tab-pane fade @foreach ($city->areas as $area) {{ $area_query == $city->slug || $area_query == $area->slug ? 'active show' : '' }}@endforeach" id="v-pills-{{ $city->id }}" role="tabpanel" aria-labelledby="v-pills-{{ $city->id }}-tab">
<a href="{{ route('front.listing', ['area' => $city->slug, 'category' => $get_category_query]) }}">
<strong>All of {{ Str::ucfirst($city->name) }}</strong>
</a>
@if ($city->areas->count() > 0)
@foreach ($city->areas->take(25) as $area)
<a class="nav-link {{ $area->listings->count() == '0' ? 'disabled' : '' }}" href="{{ route('front.listing', ['area' => $area->slug, 'category' => $get_category_query]) }}">
{{ Str::ucfirst($area->title) }}
({{ $area->listings->count() }})
</a>
@endforeach
<a class="nav-link border-0 d-block pt-3" href="{{ route('front.cities.directory') }}">
<strong>View all Areas</strong>
</a>
@else
<div class="p-3 text-center">
<p class="m-0">No areas found for {{ Str::ucfirst($city->name) }}.</p>
</div>
@endif
</div>
@endforeach
@endif
</div>
{{-- division wise city --}}
<div class="tab-content divisionTab" id="pills-tabContent">
@if (isset($divisions) && $divisions->count() > 0)
@foreach ($divisions as $division)
<div class="tab-pane fade @foreach ($division->cities as $city) {{ $area_query == $division->slug || $area_query == $city->slug ? 'active show' : '' }}@endforeach" id="v-pills-{{ $division->id }}" role="tabpanel" aria-labelledby="v-pills-{{$division->id }}-tab">
<a href="{{ route('front.listing', ['area' => $division->slug, 'category' => $get_category_query]) }}">
<strong>All of {{ Str::ucfirst($division->name) }}</strong>
</a>
@if ($division->cities->count() > 0)
@foreach ($division->cities as $city)
<a class="nav-link {{ $city->ads->count() == '0' ? 'disabled' : '' }}" href="{{ route('front.listing', ['area' => $city->slug, 'category' => $get_category_query]) }}">
{{ $city->name }} ({{ $city->ads->count() }})
</a>
@endforeach
@else
<div class="p-3 text-center">
<p class="m-0">No cities found for
{{ Str::ucfirst($division->name) }}
</p>
</div>
@endif
</div>
@endforeach
@endif
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
