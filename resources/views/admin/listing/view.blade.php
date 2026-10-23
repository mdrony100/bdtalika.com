@extends('layouts.admin')
@section('styles')
<link href="{{ asset('assets/admin/css/select2.min.css') }}" rel="stylesheet">
<style>
.select2-container--default .select2-selection--single {
width: 100%;
padding: 0px 20px 0px;
border-radius: 0px;
color: #5a6f84;
height: 35px !important;
font-size: 14px;
margin-bottom: 15px;
border-radius: 4px;
background: #fff !important;
border: 1px solid rgba(0, 0, 0, 0.15);
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
top: 4px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
line-height: 32px !important;
}

.select2-search--dropdown .select2-search__field {
outline: none !important;
box-shadow: none !important;
}

.select2-container--open .select2-dropdown {
top: -15px !important;
}
</style>
@endsection
@section('content')
<div class="card">
<div class="d-sm-flex align-items-center justify-content-between">
<h5 class="mb-0 text-gray-800 pl-3">{{ __('View Directory') }}
<a class="btn btn-primary btn-rounded btn-sm" href="{{ route('admin.listing.index') }}">
<i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
</h5>
<ol class="breadcrumb">
<li class="breadcrumb-item">
<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
</li>
<li class="breadcrumb-item">
<a href="#">{{ __('View Directory') }}</a>
</li>
</ol>
</div>
</div>

<div class="row mt-3 mb-4">
<div class="col-md-12">
<div class="card mb-4">
<div class="card-header">
<h5 class="card-title m-0">Basic Info</h5>
</div>
<div class="card-body pt-0">
<div class="table-responsive">
<table class="table table-bordered">
<tr>
<td>Listing Thumbnail</td>
<td>
<img src="{{ asset('assets/images/' . ($data->photo ?? 'default.png')) }}" width="120" alt="{{ $data->name }}">
</td>
</tr>
<tr>
<td style="width:20%;">Listing Title:</td>
<td>{{ $data->name }}</td>
</tr>
<tr>
<td>Division:</td>
<td>{{ $data->division->name }}</td>
</tr>
<tr>
<td>City:</td>
<td>{{ $data->city->name }}</td>
</tr>
@if ($data->area_id != null)
<tr>
<td>Area:</td>
<td>{{ $data->area->title ?? '' }}</td>
</tr>
@endif
<tr>
<td>Business Type:</td>
<td>{{ $data->listingType->name ?? '' }}</td>
</tr>
<tr>
<td>Category:</td>
<td>{{ $data->category->title ?? '' }}</td>
</tr>
<tr>
    <td>Plan:</td>
    <td>{{ $data->plan?->plan_type }}</td>
    </tr>
@if ($data->real_address)
<tr>
<td>Real address:</td>
<td>{{ $data->real_address }}</td>
</tr>
@endif
@if (isset($amenities) && is_array($amenities) && count($amenities) > 0)
<tr>
<td>Amenities:</td>
<td>
<ul class="m-0 p-0" style="list-style: none;">
<div class="row">
@foreach ($amenities as $amenity)
@php
$icon = DB::table('amenities')
->where('name', $amenity)
->value('icon');
@endphp
<div class="col-sm-6 col-lg-4 col-xl-3 mb-2">
<li class="mr-3">
<div class="Rego-afl-pace">
<i class="{{ $icon }}"></i>
<span>{{ $amenity }}</span>
</div>
</li>
</div>
@endforeach
</div>
</ul>
</td>
</tr>
@endif
@if ($data->distance)
<tr>
<td>Distance</td>
<td>{{ $data->distance }}</td>
</tr>
@endif
@if ($data->listing_video)
<tr>
<td>Listing Video Url:</td>
<td>
<a href="{{ $data->listing_video }}"
target="_blank">{{ $data->listing_video }}</a>
</td>
</tr>
@endif

<tr>
<td>Directory is Featured:</td>
<td>
@if ($data->is_feature == 1)
<span class="badge bg-success text-white">Yes</span>
@else
<span class="badge bg-danger text-white">No</span>
@endif
</td>
</tr>
<tr>
<td>Status:</td>
<td>
@if ($data->status == 0)
<span class="badge bg-warning text-white">Pending</span>
@elseif($data->status == 1)
<span class="badge bg-success text-white">Approved</span>
@else
<span class="badge bg-danger text-white">Reject</span>
@endif
</td>
</tr>
@if ($data->meta_keywords)
<tr>
<td>Meta Keywords:</td>
<td>{{ $data->meta_keywords }}</td>
</tr>
@endif
@if (isset($galleries) && $galleries->count() > 0)
<tr>
<td>Gallery:</td>
<td>
@foreach ($galleries as $gallery)
<img src="{{ asset('assets/images/' . $gallery->photo) }}" width="120" alt="{{ $data->name }}">
@endforeach
</td>
</tr>
@endif
</table>

<div class="mt-3">
@if ($data->description)
<p>
<strong>Description:</strong> <br>
{{ $data->description }}
</p>
@endif
@if ($data->map_location)
<p>
<strong>Map Location:</strong> <br>
{{ $data->map_location }}
</p>
@endif
@if ($data->meta_description)
<p>
<strong>Meta Description:</strong> <br>
{{ $data->meta_description }}
</p>
@endif
</div>
</div>
</div>
</div>
</div>

@if (isset($data->website) ||
isset($data->email) ||
isset($data->phone_number) ||
isset($data->facebook) ||
isset($data->twitter) ||
isset($data->linkedin))
<div class="col-12 mb-3">
<div class="card">
<div class="card-header">
<h4 class="card-title m-0">Contact Info</h4>
</div>
<div class="card-body p-1">
<div class="table-responsiv">
<table class="table">
@if (isset($data->website))
<tr>
<td>Website</td>
<td>{{ $data->website }}</td>
</tr>
@endif
@if (isset($data->email))
<tr>
<td>Email</td>
<td>{{ $data->email }}</td>
</tr>
@endif
@if (isset($data->phone_number))
<tr>
<td>Phone Number</td>
<td>{{ $data->phone_number }}</td>
</tr>
@endif
@if (isset($data->facebook))
<tr>
<td>Facebook</td>
<td>{{ $data->facebook }}</td>
</tr>
@endif
@if (isset($data->twitter))
<tr>
<td>Twitter</td>
<td>{{ $data->twitter }}</td>
</tr>
@endif
@if (isset($data->linkedin))
<tr>
<td>Linkedin</td>
<td>{{ $data->linkedin }}</td>
</tr>
@endif
</table>
</div>
</div>
</div>
</div>
@endif
<div class="col-12 mb-3">
<div class="card">
<div class="card-header">
<h4 class="card-title m-0">Schedule</h4>
</div>
<div class="card-body p-0">
<div class="table-responsive">
<table class="table table-borderless">
<tbody>
<tr>
<th>@lang('Mon')</th>
@if ($schedules['mon_open'] != 'closed' && $schedules['mon_close'])
<td>{{ $schedules['mon_open'] }} -
{{ $schedules['mon_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Tue')</td>
@if ($schedules['tue_open'] != 'closed' && $schedules['tue_close'])
<td>{{ $schedules['tue_open'] }} -
{{ $schedules['tue_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Wed')</td>
@if ($schedules['wed_open'] != 'closed' && $schedules['wed_close'])
<td>{{ $schedules['wed_open'] }} -
{{ $schedules['wed_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Thu')</td>
@if ($schedules['thu_open'] != 'closed' && $schedules['thu_close'])
<td>{{ $schedules['thu_open'] }} -
{{ $schedules['thu_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Fri')</td>
@if ($schedules['fri_open'] != 'closed' && $schedules['fri_close'])
<td>{{ $schedules['fri_open'] }} -
{{ $schedules['fri_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Sat')</td>
@if ($schedules['sat_open'] != 'closed' && $schedules['sat_close'])
<td>{{ $schedules['sat_open'] }} -
{{ $schedules['sat_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
<tr>
<td>@lang('Sun')</td>
@if ($schedules['sun_open'] != 'closed' && $schedules['sun_close'])
<td>{{ $schedules['sun_open'] }} -
{{ $schedules['sun_close'] }}</td>
@else
<td>@lang('closed')</td>
@endif
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>

@if ($category->slug == 'restaurants')
@if (isset($data->menus) && $data->menus->count() > 0)
<div class="col-12 mb-3">
<div class="card">
<div class="card-header">
<h4 class="card-title m-0">Food Menus</h4>
</div>
<div class="card-body p-0">
<div class="bg-white rounded mb-4">
<div class="jbd-01 px-4">
<div class="jbd-details mb-2">
<div class="d-block">
<div class="row g-3">
<!-- Single Menu -->
@foreach ($data->menus as $key => $menu)
@if (!empty($menu->menu_title))
<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
<div class="Rego-sng-menu card">
<div class="Rego-sng-menu-thumb">
<img src="{{ asset('assets/images/' . ($menu->menu_photo ?? 'default.png')) }}"
class="img-fluid" alt="{{ $menu->menu_title }}">
</div>
<div class="Rego-sng-menu-caption p-2 pt-0">
<h4 class="ft-medium fs-md mb-2">
{{ $menu->menu_title }}
</h4>
<div class="lkji-oiyt">
{{-- <span>@lang('Start From')</span> --}}
<h5 class="theme-cl ft-bold p-0 m-0">
{{ showSignAmount($menu->menu_price) }}
</h5>
</div>
</div>
</div>
</div>
@endif
@endforeach
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
@endif
@endif

@if ($category->slug == 'hotels')
@if (isset($data->rooms) && count($data->rooms) > 0)
<div class="col-12 mb-3">
<div class="card">
<div class="card-header">
<h4 class="card-title m-0">Hotel room</h4>
</div>
<div class="card-body p-0">

<div class="bg-white rounded mb-4">
<div class="jbd-01 px-4 py-4">
<div class="jbd-details mb-2">
<div class="d-block">
<div class="row g-3">
<!-- Single Menu -->
@foreach ($data->rooms as $key => $room)
<div class="col-sm-6 col-xl-4">
<div class="Rego-sng-menu card">
<div class="Rego-sng-menu-thumb">
<img src="{{ asset('assets/images/' . ($room->room_photo ?? 'default.png')) }}"
class="img-fluid" alt="{{ $room->room_name }}">
</div>
<div class="Rego-sng-menu-caption p-3">
<h4 class="ft-medium fs-md">{{ $room->room_name }}
</h4>
<p>{{ $room->room_description }}</p>
<div class="row">
@foreach (explode(',', $room->room_amenities) as $key => $amenity)
<div class="col-lg-4 col-md-4 col-xl-4">
{{ $amenity }}
</div>
@endforeach
</div>
<div class="lkji-oiyt mt-3">
{{-- <span>@lang('Start From')</span> --}}
<h2 class="theme-cl ft-bold">
{{ $room->currency_symbol == 'tk' ? '৳' : '$' }}
{{ $room->room_price }}
</h2>
</div>
</div>
</div>
</div>
@endforeach
</div>
</div>
</div>

</div>
</div>
</div>
</div>
</div>
@endif
@endif
@if ($category->slug == 'beauty-spa' || $category->slug == 'universities' || $category->slug == 'lawyers' || $category->slug == 'ac-repair-services')
@if (count($data->services) > 0)
<div class="col-12 mb-3">
<div class="card">
<div class="card-header">
<h4 class="card-title m-0">Products & Services</h4>
</div>
<div class="card-body p-0">

<div class="bg-white rounded mb-4">
<div class="jbd-01 px-4">
<div class="jbd-details mb-2">
<div class="d-block">
<div class="row g-3">
<!-- Single Menu -->
@foreach ($data->services as $key => $service)
<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-6">
<div class="Rego-sng-menu card">
<div class="Rego-sng-menu-thumb">
<img src="{{ asset('assets/images/' . ($service->service_photo ?? 'default.png')) }}"
class="img-fluid"
alt="{{ $service->service_name }}">
</div>
<div class="Rego-sng-menu-caption p-3">
<h4 class="ft-medium fs-md">
{{ $service->service_name }}
</h4>
<p>@lang('Availability '): {{ $service->service_from }} -
{{ $service->service_to }}</p>
<p>@lang('Duration '): {{ $service->service_duration }}
@lang('Minute')</p>
<div class="lkji-oiyt">
{{-- <span>@lang('Start From')</span> --}}
<h2 class="theme-cl ft-bold">
{{ showSignAmount($service->service_price) }}
</h2>
</div>
</div>
</div>
</div>
@endforeach
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
@endif
@endif

@if ($category->slug == 'real-estate')
<div class="col-12 mb-3">
<div class="card">
<div class="card-header">
<h4 class="card-title m-0">Real estate Info</h4>
</div>
<div class="card-body p-3">
<div class="table-responsive ">
<table class="table table-bordered">
<tbody>
<tr>
<td style="width:20%;">Property Price</td>
<td>{{ $data->r_price }}</td>
</tr>
<tr>
<td>No. Bed</td>
<td>{{ $data->r_bed }}</td>
</tr>
<tr>
<td>No. Bathrooms/td>
<td>{{ $data->r_baths }}</td>
</tr>
<tr>
<td>Square Feet</td>
<td>{{ $data->r_square }}</td>
</tr>
<tr>
<td>Property Type</td>
<td>{{ Str::ucfirst($data->r_property_type) }}</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
@endif

@if ($category->slug == 'car')
<div class="col-12 mb-3">
<div class="card">
<div class="card-header">
<h4 class="card-title m-0">Car</h4>
</div>
<div class="card-body p-3">
<div class="table-responsive ">
<table class="table table-bordered">
<tbody>
<tr>
<td style="width:20%;">Car Price</td>
<td>{{ $data->car_price }}</td>
</tr>
<tr>
<td>Car Fuel Type</td>
<td>{{ $data->car_fuel_type }}</td>
</tr>
<tr>
<td>Car Manufacture Year</td>
<td>{{ $data->car_manufacture_year }}</td>
</tr>
<tr>
<td>Car Engine Capacity</td>
<td>{{ $data->car_engine_capacity }}</td>
</tr>
<tr>
<td>Car Mileage</td>
<td>{{ $data->car_mileage }}</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
@endif

@if (isset($data->faqs) && count($data->faqs) > 0)
<div class="col-12 mb-3">
<div class="card">
<div class="card-header">
<h4 class="card-title m-0">Faq</h4>
</div>
<div class="card-body p-0">
@foreach ($data->faqs as $key => $faq)
<div class="card border m-3 shadow-none">
<div class="card-header">
<h3 class="mb-0">
{{ $faq->faq_name }}
</h3>
{{ $faq->faq_details }}
</div>
</div>
@endforeach
</div>
</div>
</div>
@endif


</div>
@endsection
