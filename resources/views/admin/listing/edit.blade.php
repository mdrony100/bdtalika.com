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
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('Directory') }}
                <a class="btn btn-primary btn-rounded btn-sm" href="{{ route('admin.listing.index') }}"><i
                        class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
            </h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.listing.edit', $data->id) }}">{{ __('Edit Directory') }}</a>
                </li>
            </ol>
        </div>
    </div>
   <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="gocover"
                        style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                    </div>
                    <form class="geniusform" action="{{ route('admin.listing.update', $data->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @include('includes.admin.form-both')
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="category_id" value="{{ $category_id }}">
                        <div class="">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="mb-3">Basic</h4>
                                    <hr>
                                </div>
                                <div class="col-sm-6 col-xl-4">
                                    <label class="form-label">Pricing Plan</label>
                                    <select name="plan_id" id="plan_id" class="input-field">
                                        <option>
                                            Select Pricing Plan</option>
                                        @if (isset($plans) && $plans->count() > 0)
                                            @foreach ($plans as $key => $plan)
                                                <option value="{{ $plan->id }}"
                                                    {{ $plan->id == $data->plan_id ? 'selected' : '' }}>
                                                    {{ ucfirst($plan->title) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @if($category == 'hospitals')
                                    <div class="col-sm-6 col-xl-4">
                                        <label class="form-label">{{ __('Listing Title') }} *</label>
                                        <input type="text" class="input-field" name="name" id="listingTitle"
                                            placeholder="{{ __('Listing Title') }}" value="{{ $data->name }}">
                                    </div>
                                @else
                                    <div class="col-sm-6 col-xl-4">
                                        <label class="form-label">{{ __('Listing Title') }} *</label>
                                        <input type="text" class="input-field" name="name" id="listingTitle"
                                            placeholder="{{ __('Listing Title') }}" value="{{ $data->name }}">
                                    </div>
                                @endif
                                <div class="col-sm-6 col-xl-4">
                                    <label class="form-label">{{ __('Division') }} *</label>
                                    <select name="division_id" id="division_id" class="input-field">
                                        <option value="" class="d-none">
                                            {{ __('Please select a division') }}</option>
                                        @foreach ($divisions as $key => $division)
                                            <option value="{{ $division->id }}"
                                                {{ $division->id == $data->division_id ? 'selected' : '' }}>
                                                {{ ucfirst($division->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-xl-4">
                                    <label class="form-label">{{ __('City') }} *</label>
                                    <select name="city_id" id="city_id" class="input-field">
                                        <option value="" class="d-none">
                                            {{ __('Please select a city') }}</option>
                                        @if (isset($cities) && $cities->count() > 0)
                                            @foreach ($cities as $key => $city)
                                                <option value="{{ $city->id }}"
                                                    {{ $city->id == $data->city_id ? 'selected' : '' }}>
                                                    {{ ucfirst($city->name) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>                                
                                <div class="col-sm-6 col-xl-4">
                                    <label class="form-label">{{ __('Area') }}</label>
                                    <select name="area_id" id="area_id" class="input-field select2">
                                        @php
                                            $areas = DB::table('areas')
                                                ->where('city_id', $data->city_id)
                                                ->get();
                                        @endphp
                                        @foreach ($areas as $key => $area)
                                            <option value="{{ $area->id }}"
                                                {{ $area->id == $data->area_id ? 'selected' : '' }}>
                                                {{ ucfirst($area->title) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-xl-4">
                                    <label class="form-label">{{ __('Real address') }}</label>
                                    <input type="text" class="input-field" name="real_address"
                                        placeholder="{{ __('Address') }}" value="{{ $data->real_address }}">
                                </div>

                            
                                @if ($category == 'hotels')
                                    <div class="col-sm-6 col-xl-6">
                                        <label class="form-label">{{ __('Distance') }}</label>
                                        <input type="text" class="input-field" name="distance"
                                            placeholder="{{ __('Distance') }}" value="{{ $data->distance }}">
                                    </div>
                                @endif


                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">
                                            @lang('Description')
                                            <span class="text-danger">*</span>
                                        </label>

                                        <div>
                                            <button type="button" id="generateDescription" class="btn btn-sm btn-primary">
                                                🤖 Generate with AI
                                            </button>
                                        </div>

                                        <div class="word_limit text-muted ">0/4500</div>
                                    </div>

                                    <textarea id="descriptionField"
                                        name="description"
                                        class="input-field summernote"
                                        placeholder="{{ __('Description') }}"
                                        cols="30"
                                        rows="10">{!! $data->description !!}</textarea>
                                </div>


                                <!-- <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">
                                            @lang('Description')
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="word_limit text-muted ">0/5000</div>
                                    </div>
                                    <textarea name="description" class="input-field" placeholder="{{ __('Description') }}" cols="30" rows="10">{{ $data->description }}</textarea>
                                </div> -->


                                <div class="col-12">
                                    <label class="form-label">{{ __('Map Location') }}</label>
                                    <textarea class="input-field rounded" cols="5" rows="5" name="map_location"
                                        placeholder="@lang('Location')">{{ $data->map_location }}</textarea>
                                </div>
                                <div class="col-sm-6 col-xl-4">
                                    <label class="form-label">{{ __('Latitude') }}</label>
                                    <input type="text" class="input-field" name="latitude"
                                        placeholder="{{ __('latitude') }}" value="{{ $data->latitude }}">
                                </div>
                                <div class="col-sm-6 col-xl-4">
                                    <label class="form-label">{{ __('Longitude') }}</label>
                                    <input type="text" class="input-field" name="longitude"
                                        placeholder="{{ __('longitude') }}" value="{{ $data->longitude }}">
                                </div>
                            </div>

                            {{-- Amenities --}}
                            <div class="amenities mb-3">
                                <h4 class="mb-3">Amenities</h4>
                                <hr>
                                <div class="row">
                                    @if ($amenities)
                                        @foreach ($amenities as $key => $amenity)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="amenities[{{ $amenity->icon }}][]"
                                                        value="{{ $amenity->name }}"
                                                        id="{{ $amenity->name }}-option-{{ $key }}"
                                                        {{ in_array($amenity->name, $listingAmenities) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="{{ $amenity->name }}-option-{{ $key }}">{{ $amenity->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- Media --}}
                            <div class="media-file mb-3">
                                <div class="w-100">
                                    <h4 class="mb-3">Media</h4>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-label">{{ __('Listing Video Provider') }}</label>
                                        <select name="listing_video_provider" class="input-field">
                                            <option value="youtube"
                                                {{ $data->listing_video_provider == 'youtube' ? 'selected' : '' }}>
                                                {{ __('Youtube') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">{{ __('Listing Video Url') }}</label>
                                        <input type="text" class="input-field" name="listing_video"
                                            placeholder="https://www.youtube.com/watch?v=AXrHbrMrun0"
                                            value="{{ $data->listing_video }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label m-0">{{ __('Listing Thumbnail') }}</label> <br>
                                        <p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
                                        <div class="wrapper-image-preview">
                                            <div class="box">
                                                <div class="back-preview-image"
                                                    style="background-image: url({{ $data->photo ? asset('assets/images/' . $data->photo) : asset('assets/images/default.png') }});">
                                                </div>
                                                <div class="upload-options">
                                                    <label class="img-upload-label" for="img-upload"> <i
                                                            class="fas fa-camera"></i> {{ __('Upload Picture') }}
                                                    </label>
                                                    <input id="img-upload" type="file" class="image-upload"
                                                        name="photo" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <label class="form-label">{{ __('Listing Gallary') }}</label> <br>
                                        <button type="button" class="btn btn-primary set-gallery" data-toggle="modal"
                                            data-target="#setgallery" id="#myBtn">
                                            <i class="icofont-plus"></i> {{ __('Set Gallery') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Schedule --}}
                            <div class="amenities mb-3">
                                <h4 class="mb-3">Schedule</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Saturday') }}</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group m-0">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="openTime">
                                                <label class="form-check-label" for="openTime">
                                                    Check All Opening Time Same
                                                </label>
                                            </div>
                                            <select class="input-field" name="saturday_opening" id="saturday_opening">
                                                <option value="" class="d-none">{{ __('Opening Time') }}
                                                </option>
                                                <option value="24 hours"
                                                    {{ $listingSchedules['sat_open'] == '24 hours' ? 'selected' : '' }}>
                                                    24 hours
                                                </option>
                                                <option value="12 am"
                                                    {{ $listingSchedules['sat_open'] == '12 am' ? 'selected' : '' }}>12
                                                    am</option>
                                                <option value="12.30 am"
                                                    {{ $listingSchedules['sat_open'] == '12.30 am' ? 'selected' : '' }}>
                                                    12:30 am</option>
                                                <option value="01 am"
                                                    {{ $listingSchedules['sat_open'] == '01 am' ? 'selected' : '' }}>01
                                                    am</option>
                                                <option value="01.30 am"
                                                    {{ $listingSchedules['sat_open'] == '01.30 am' ? 'selected' : '' }}>
                                                    01:30 am</option>
                                                <option value="02 am"
                                                    {{ $listingSchedules['sat_open'] == '02 am' ? 'selected' : '' }}>02
                                                    am</option>
                                                <option value="02.30 am"
                                                    {{ $listingSchedules['sat_open'] == '02.30 am' ? 'selected' : '' }}>
                                                    02:30 am</option>
                                                <option value="03 am"
                                                    {{ $listingSchedules['sat_open'] == '03 am' ? 'selected' : '' }}>03
                                                    am</option>
                                                <option value="03.30 am"
                                                    {{ $listingSchedules['sat_open'] == '03.30 am' ? 'selected' : '' }}>
                                                    03:30 am</option>
                                                <option value="04 am"
                                                    {{ $listingSchedules['sat_open'] == '04 am' ? 'selected' : '' }}>04
                                                    am</option>
                                                <option value="04.30 am"
                                                    {{ $listingSchedules['sat_open'] == '04.30 am' ? 'selected' : '' }}>
                                                    04:30 am</option>
                                                <option value="05 am"
                                                    {{ $listingSchedules['sat_open'] == '05 am' ? 'selected' : '' }}>05
                                                    am</option>
                                                <option value="05.30 am"
                                                    {{ $listingSchedules['sat_open'] == '05.30 am' ? 'selected' : '' }}>
                                                    05:30 am</option>
                                                <option value="06 am"
                                                    {{ $listingSchedules['sat_open'] == '06 am' ? 'selected' : '' }}>06
                                                    am</option>
                                                <option value="06.30 am"
                                                    {{ $listingSchedules['sat_open'] == '06.30 am' ? 'selected' : '' }}>
                                                    06:30 am</option>
                                                <option value="07 am"
                                                    {{ $listingSchedules['sat_open'] == '07 am' ? 'selected' : '' }}>07
                                                    am</option>
                                                <option value="07.30 am"
                                                    {{ $listingSchedules['sat_open'] == '07.30 am' ? 'selected' : '' }}>
                                                    07:30 am</option>
                                                <option value="08 am"
                                                    {{ $listingSchedules['sat_open'] == '08 am' ? 'selected' : '' }}>08
                                                    am</option>
                                                <option value="08.30 am"
                                                    {{ $listingSchedules['sat_open'] == '08.30 am' ? 'selected' : '' }}>
                                                    08:30 am</option>
                                                <option value="09 am"
                                                    {{ $listingSchedules['sat_open'] == '09 am' ? 'selected' : '' }}>09
                                                    am</option>
                                                <option value="09.30 am"
                                                    {{ $listingSchedules['sat_open'] == '09.30 am' ? 'selected' : '' }}>
                                                    09:30 am</option>
                                                <option value="10 am"
                                                    {{ $listingSchedules['sat_open'] == '10 am' ? 'selected' : '' }}>
                                                    10 am</option>
                                                <option value="10.30 am"
                                                    {{ $listingSchedules['sat_open'] == '10.30 am' ? 'selected' : '' }}>
                                                    10:30 am</option>
                                                <option value="11 am"
                                                    {{ $listingSchedules['sat_open'] == '11 am' ? 'selected' : '' }}>
                                                    11 am</option>
                                                <option value="11.30 am"
                                                    {{ $listingSchedules['sat_open'] == '11.30 am' ? 'selected' : '' }}>
                                                    11:30 am</option>
                                                <option value="12 pm"
                                                    {{ $listingSchedules['sat_open'] == '12 pm' ? 'selected' : '' }}>
                                                    12 pm</option>
                                                <option value="12.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '12.30 pm' ? 'selected' : '' }}>
                                                    12:30 pm</option>
                                                <option value="01 pm"
                                                    {{ $listingSchedules['sat_open'] == '01 pm' ? 'selected' : '' }}>
                                                    01 pm</option>
                                                <option value="01.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '01.30 pm' ? 'selected' : '' }}>
                                                    01:30 pm</option>
                                                <option value="02 pm"
                                                    {{ $listingSchedules['sat_open'] == '02 pm' ? 'selected' : '' }}>
                                                    02 pm</option>
                                                <option value="02.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '02.30 pm' ? 'selected' : '' }}>
                                                    02:30 pm</option>
                                                <option value="03 pm"
                                                    {{ $listingSchedules['sat_open'] == '03 pm' ? 'selected' : '' }}>
                                                    03 pm</option>
                                                <option value="03.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '03.30 pm' ? 'selected' : '' }}>
                                                    03:30 pm</option>
                                                <option value="04 pm"
                                                    {{ $listingSchedules['sat_open'] == '04 pm' ? 'selected' : '' }}>
                                                    04 pm</option>
                                                <option value="04.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '04.30 pm' ? 'selected' : '' }}>
                                                    04:30 pm</option>
                                                <option value="05 pm"
                                                    {{ $listingSchedules['sat_open'] == '05 pm' ? 'selected' : '' }}>
                                                    05 pm</option>
                                                <option value="05.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '05.30 pm' ? 'selected' : '' }}>
                                                    05:30 pm</option>
                                                <option value="06 pm"
                                                    {{ $listingSchedules['sat_open'] == '06 pm' ? 'selected' : '' }}>
                                                    06 pm</option>
                                                <option value="06.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '06.30 pm' ? 'selected' : '' }}>
                                                    06:30 pm</option>
                                                <option value="07 pm"
                                                    {{ $listingSchedules['sat_open'] == '07 pm' ? 'selected' : '' }}>
                                                    07 pm</option>
                                                <option value="07.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '07.30 pm' ? 'selected' : '' }}>
                                                    07:30 pm</option>
                                                <option value="08 pm"
                                                    {{ $listingSchedules['sat_open'] == '08 pm' ? 'selected' : '' }}>
                                                    08 pm</option>
                                                <option value="08.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '08.30 pm' ? 'selected' : '' }}>
                                                    08:30 pm</option>
                                                <option value="09 pm"
                                                    {{ $listingSchedules['sat_open'] == '09 pm' ? 'selected' : '' }}>
                                                    09 pm</option>
                                                <option value="09.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '09.30 pm' ? 'selected' : '' }}>
                                                    09:30 pm</option>
                                                <option value="10 pm"
                                                    {{ $listingSchedules['sat_open'] == '10 pm' ? 'selected' : '' }}>
                                                    10 pm</option>
                                                <option value="10.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '10.30 pm' ? 'selected' : '' }}>
                                                    10:30 pm</option>
                                                <option value="11 pm"
                                                    {{ $listingSchedules['sat_open'] == '11 pm' ? 'selected' : '' }}>
                                                    11 pm</option>
                                                <option value="11.30 pm"
                                                    {{ $listingSchedules['sat_open'] == '11.30 pm' ? 'selected' : '' }}>
                                                    11:30 pm</option>
                                                <option value="closed"
                                                    {{ $listingSchedules['sat_open'] == 'closed' ? 'selected' : '' }}>
                                                    Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="closeTime">
                                            <label class="form-check-label" for="closeTime">
                                                Check All Closing Time Same
                                            </label>
                                        </div>
                                        <select class="input-field" name="saturday_closing" id="saturday_closing">
                                            <option value="" class="d-none">{{ __('Closing Time') }}
                                            </option>
                                            <option value="24 hours"
                                                {{ $listingSchedules['sat_close'] == '24 hours' ? 'selected' : '' }}>
                                                24 hours</option>
                                            <option value="12 am"
                                                {{ $listingSchedules['sat_close'] == '12 am' ? 'selected' : '' }}>
                                                12 am</option>
                                            <option value="12.30 am"
                                                {{ $listingSchedules['sat_close'] == '12.30 am' ? 'selected' : '' }}>
                                                12:30 am</option>
                                            <option value="01 am"
                                                {{ $listingSchedules['sat_close'] == '01 am' ? 'selected' : '' }}>
                                                01 am</option>
                                            <option value="01.30 am"
                                                {{ $listingSchedules['sat_close'] == '01.30 am' ? 'selected' : '' }}>
                                                01:30 am</option>
                                            <option value="02 am"
                                                {{ $listingSchedules['sat_close'] == '02 am' ? 'selected' : '' }}>
                                                02 am</option>
                                            <option value="02.30 am"
                                                {{ $listingSchedules['sat_close'] == '02.30 am' ? 'selected' : '' }}>
                                                02:30 am</option>
                                            <option value="03 am"
                                                {{ $listingSchedules['sat_close'] == '03 am' ? 'selected' : '' }}>
                                                03 am</option>
                                            <option value="03.30 am"
                                                {{ $listingSchedules['sat_close'] == '03.30 am' ? 'selected' : '' }}>
                                                03:30 am</option>
                                            <option value="04 am"
                                                {{ $listingSchedules['sat_close'] == '04 am' ? 'selected' : '' }}>
                                                04 am</option>
                                            <option value="04.30 am"
                                                {{ $listingSchedules['sat_close'] == '04.30 am' ? 'selected' : '' }}>
                                                04:30 am</option>
                                            <option value="05 am"
                                                {{ $listingSchedules['sat_close'] == '05 am' ? 'selected' : '' }}>
                                                05 am</option>
                                            <option value="05.30 am"
                                                {{ $listingSchedules['sat_close'] == '05.30 am' ? 'selected' : '' }}>
                                                05:30 am</option>
                                            <option value="06 am"
                                                {{ $listingSchedules['sat_close'] == '06 am' ? 'selected' : '' }}>
                                                06 am</option>
                                            <option value="06.30 am"
                                                {{ $listingSchedules['sat_close'] == '06.30 am' ? 'selected' : '' }}>
                                                06:30 am</option>
                                            <option value="07 am"
                                                {{ $listingSchedules['sat_close'] == '07 am' ? 'selected' : '' }}>
                                                07 am</option>
                                            <option value="07.30 am"
                                                {{ $listingSchedules['sat_close'] == '07.30 am' ? 'selected' : '' }}>
                                                07:30 am</option>
                                            <option value="08 am"
                                                {{ $listingSchedules['sat_close'] == '08 am' ? 'selected' : '' }}>
                                                08 am</option>
                                            <option value="08.30 am"
                                                {{ $listingSchedules['sat_close'] == '08.30 am' ? 'selected' : '' }}>
                                                08:30 am</option>
                                            <option value="09 am"
                                                {{ $listingSchedules['sat_close'] == '09 am' ? 'selected' : '' }}>
                                                09 am</option>
                                            <option value="09.30 am"
                                                {{ $listingSchedules['sat_close'] == '09.30 am' ? 'selected' : '' }}>
                                                09:30 am</option>
                                            <option value="10 am"
                                                {{ $listingSchedules['sat_close'] == '10 am' ? 'selected' : '' }}>
                                                10 am</option>
                                            <option value="10.30 am"
                                                {{ $listingSchedules['sat_close'] == '10.30 am' ? 'selected' : '' }}>
                                                10:30 am</option>
                                            <option value="11 am"
                                                {{ $listingSchedules['sat_close'] == '11 am' ? 'selected' : '' }}>
                                                11 am</option>
                                            <option value="11.30 am"
                                                {{ $listingSchedules['sat_close'] == '11.30 am' ? 'selected' : '' }}>
                                                11:30 am</option>
                                            <option value="12 pm"
                                                {{ $listingSchedules['sat_close'] == '12 pm' ? 'selected' : '' }}>
                                                12 pm</option>
                                            <option value="12.30 pm"
                                                {{ $listingSchedules['sat_close'] == '12.30 pm' ? 'selected' : '' }}>
                                                12:30 pm</option>
                                            <option value="01 pm"
                                                {{ $listingSchedules['sat_close'] == '01 pm' ? 'selected' : '' }}>
                                                01 pm</option>
                                            <option value="01.30 pm"
                                                {{ $listingSchedules['sat_close'] == '01.30 pm' ? 'selected' : '' }}>
                                                01:30 pm</option>
                                            <option value="02 pm"
                                                {{ $listingSchedules['sat_close'] == '02 pm' ? 'selected' : '' }}>
                                                02 pm</option>
                                            <option value="02.30 pm"
                                                {{ $listingSchedules['sat_close'] == '02.30 pm' ? 'selected' : '' }}>
                                                02:30 pm</option>
                                            <option value="03 pm"
                                                {{ $listingSchedules['sat_close'] == '03 pm' ? 'selected' : '' }}>
                                                03 pm</option>
                                            <option value="03.30 pm"
                                                {{ $listingSchedules['sat_close'] == '03.30 pm' ? 'selected' : '' }}>
                                                03:30 pm</option>
                                            <option value="04 pm"
                                                {{ $listingSchedules['sat_close'] == '04 pm' ? 'selected' : '' }}>
                                                04 pm</option>
                                            <option value="04.30 pm"
                                                {{ $listingSchedules['sat_close'] == '04.30 pm' ? 'selected' : '' }}>
                                                04:30 pm</option>
                                            <option value="05 pm"
                                                {{ $listingSchedules['sat_close'] == '05 pm' ? 'selected' : '' }}>
                                                05 pm</option>
                                            <option value="05.30 pm"
                                                {{ $listingSchedules['sat_close'] == '05.30 pm' ? 'selected' : '' }}>
                                                05:30 pm</option>
                                            <option value="06 pm"
                                                {{ $listingSchedules['sat_close'] == '06 pm' ? 'selected' : '' }}>
                                                06 pm</option>
                                            <option value="06.30 pm"
                                                {{ $listingSchedules['sat_close'] == '06.30 pm' ? 'selected' : '' }}>
                                                06:30 pm</option>
                                            <option value="07 pm"
                                                {{ $listingSchedules['sat_close'] == '07 pm' ? 'selected' : '' }}>
                                                07 pm</option>
                                            <option value="07.30 pm"
                                                {{ $listingSchedules['sat_close'] == '07.30 pm' ? 'selected' : '' }}>
                                                07:30 pm</option>
                                            <option value="08 pm"
                                                {{ $listingSchedules['sat_close'] == '08 pm' ? 'selected' : '' }}>
                                                08 pm</option>
                                            <option value="08.30 pm"
                                                {{ $listingSchedules['sat_close'] == '08.30 pm' ? 'selected' : '' }}>
                                                08:30 pm</option>
                                            <option value="09 pm"
                                                {{ $listingSchedules['sat_close'] == '09 pm' ? 'selected' : '' }}>
                                                09 pm</option>
                                            <option value="09.30 pm"
                                                {{ $listingSchedules['sat_close'] == '09.30 pm' ? 'selected' : '' }}>
                                                09:30 pm</option>
                                            <option value="10 pm"
                                                {{ $listingSchedules['sat_close'] == '10 pm' ? 'selected' : '' }}>
                                                10 pm</option>
                                            <option value="10.30 pm"
                                                {{ $listingSchedules['sat_close'] == '10.30 pm' ? 'selected' : '' }}>
                                                10:30 pm</option>
                                            <option value="11 pm"
                                                {{ $listingSchedules['sat_close'] == '11 pm' ? 'selected' : '' }}>
                                                11 pm</option>
                                            <option value="11.30 pm"
                                                {{ $listingSchedules['sat_close'] == '11.30 pm' ? 'selected' : '' }}>
                                                11:30 pm</option>
                                            <option value="closed"
                                                {{ $listingSchedules['sat_close'] == 'closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Sunday') }}</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="sunday_opening" id="sunday_opening">
                                            <option value="" class="d-none">{{ __('Opening Time') }}
                                            </option>
                                            <option value="24 hours"
                                                {{ $listingSchedules['sun_open'] == '24 hours' ? 'selected' : '' }}>
                                                24 hours</option>
                                            <option value="12 am"
                                                {{ $listingSchedules['sun_open'] == '12 am' ? 'selected' : '' }}>
                                                12 am</option>
                                            <option value="12.30 am"
                                                {{ $listingSchedules['sun_open'] == '12.30 am' ? 'selected' : '' }}>
                                                12:30 am</option>
                                            <option value="01 am"
                                                {{ $listingSchedules['sun_open'] == '01 am' ? 'selected' : '' }}>
                                                01 am</option>
                                            <option value="01.30 am"
                                                {{ $listingSchedules['sun_open'] == '01.30 am' ? 'selected' : '' }}>
                                                01:30 am</option>
                                            <option value="02 am"
                                                {{ $listingSchedules['sun_open'] == '02 am' ? 'selected' : '' }}>
                                                02 am</option>
                                            <option value="02.30 am"
                                                {{ $listingSchedules['sun_open'] == '02.30 am' ? 'selected' : '' }}>
                                                02:30 am</option>
                                            <option value="03 am"
                                                {{ $listingSchedules['sun_open'] == '03 am' ? 'selected' : '' }}>
                                                03 am</option>
                                            <option value="03.30 am"
                                                {{ $listingSchedules['sun_open'] == '03.30 am' ? 'selected' : '' }}>
                                                03:30 am</option>
                                            <option value="04 am"
                                                {{ $listingSchedules['sun_open'] == '04 am' ? 'selected' : '' }}>
                                                04 am</option>
                                            <option value="04.30 am"
                                                {{ $listingSchedules['sun_open'] == '04.30 am' ? 'selected' : '' }}>
                                                04:30 am</option>
                                            <option value="05 am"
                                                {{ $listingSchedules['sun_open'] == '05 am' ? 'selected' : '' }}>
                                                05 am</option>
                                            <option value="05.30 am"
                                                {{ $listingSchedules['sun_open'] == '05.30 am' ? 'selected' : '' }}>
                                                05:30 am</option>
                                            <option value="06 am"
                                                {{ $listingSchedules['sun_open'] == '06 am' ? 'selected' : '' }}>
                                                06 am</option>
                                            <option value="06.30 am"
                                                {{ $listingSchedules['sun_open'] == '06.30 am' ? 'selected' : '' }}>
                                                06:30 am</option>
                                            <option value="07 am"
                                                {{ $listingSchedules['sun_open'] == '07 am' ? 'selected' : '' }}>
                                                07 am</option>
                                            <option value="07.30 am"
                                                {{ $listingSchedules['sun_open'] == '07.30 am' ? 'selected' : '' }}>
                                                07:30 am</option>
                                            <option value="08 am"
                                                {{ $listingSchedules['sun_open'] == '08 am' ? 'selected' : '' }}>
                                                08 am</option>
                                            <option value="08.30 am"
                                                {{ $listingSchedules['sun_open'] == '08.30 am' ? 'selected' : '' }}>
                                                08:30 am</option>
                                            <option value="09 am"
                                                {{ $listingSchedules['sun_open'] == '09 am' ? 'selected' : '' }}>
                                                09 am</option>
                                            <option value="09.30 am"
                                                {{ $listingSchedules['sun_open'] == '09.30 am' ? 'selected' : '' }}>
                                                09:30 am</option>
                                            <option value="10 am"
                                                {{ $listingSchedules['sun_open'] == '10 am' ? 'selected' : '' }}>
                                                10 am</option>
                                            <option value="10.30 am"
                                                {{ $listingSchedules['sun_open'] == '10.30 am' ? 'selected' : '' }}>
                                                10:30 am</option>
                                            <option value="11 am"
                                                {{ $listingSchedules['sun_open'] == '11 am' ? 'selected' : '' }}>
                                                11 am</option>
                                            <option value="11.30 am"
                                                {{ $listingSchedules['sun_open'] == '11.30 am' ? 'selected' : '' }}>
                                                11:30 am</option>
                                            <option value="12 pm"
                                                {{ $listingSchedules['sun_open'] == '12 pm' ? 'selected' : '' }}>
                                                12 pm</option>
                                            <option value="12.30 pm"
                                                {{ $listingSchedules['sun_open'] == '12.30 pm' ? 'selected' : '' }}>
                                                12:30 pm</option>
                                            <option value="01 pm"
                                                {{ $listingSchedules['sun_open'] == '01 pm' ? 'selected' : '' }}>
                                                01 pm</option>
                                            <option value="01.30 pm"
                                                {{ $listingSchedules['sun_open'] == '01.30 pm' ? 'selected' : '' }}>
                                                01:30 pm</option>
                                            <option value="02 pm"
                                                {{ $listingSchedules['sun_open'] == '02 pm' ? 'selected' : '' }}>
                                                02 pm</option>
                                            <option value="02.30 pm"
                                                {{ $listingSchedules['sun_open'] == '02.30 pm' ? 'selected' : '' }}>
                                                02:30 pm</option>
                                            <option value="03 pm"
                                                {{ $listingSchedules['sun_open'] == '03 pm' ? 'selected' : '' }}>
                                                03 pm</option>
                                            <option value="03.30 pm"
                                                {{ $listingSchedules['sun_open'] == '03.30 pm' ? 'selected' : '' }}>
                                                03:30 pm</option>
                                            <option value="04 pm"
                                                {{ $listingSchedules['sun_open'] == '04 pm' ? 'selected' : '' }}>
                                                04 pm</option>
                                            <option value="04.30 pm"
                                                {{ $listingSchedules['sun_open'] == '04.30 pm' ? 'selected' : '' }}>
                                                04:30 pm</option>
                                            <option value="05 pm"
                                                {{ $listingSchedules['sun_open'] == '05 pm' ? 'selected' : '' }}>
                                                05 pm</option>
                                            <option value="05.30 pm"
                                                {{ $listingSchedules['sun_open'] == '05.30 pm' ? 'selected' : '' }}>
                                                05:30 pm</option>
                                            <option value="06 pm"
                                                {{ $listingSchedules['sun_open'] == '06 pm' ? 'selected' : '' }}>
                                                06 pm</option>
                                            <option value="06.30 pm"
                                                {{ $listingSchedules['sun_open'] == '06.30 pm' ? 'selected' : '' }}>
                                                06:30 pm</option>
                                            <option value="07 pm"
                                                {{ $listingSchedules['sun_open'] == '07 pm' ? 'selected' : '' }}>
                                                07 pm</option>
                                            <option value="07.30 pm"
                                                {{ $listingSchedules['sun_open'] == '07.30 pm' ? 'selected' : '' }}>
                                                07:30 pm</option>
                                            <option value="08 pm"
                                                {{ $listingSchedules['sun_open'] == '08 pm' ? 'selected' : '' }}>
                                                08 pm</option>
                                            <option value="08.30 pm"
                                                {{ $listingSchedules['sun_open'] == '08.30 pm' ? 'selected' : '' }}>
                                                08:30 pm</option>
                                            <option value="09 pm"
                                                {{ $listingSchedules['sun_open'] == '09 pm' ? 'selected' : '' }}>
                                                09 pm</option>
                                            <option value="09.30 pm"
                                                {{ $listingSchedules['sun_open'] == '09.30 pm' ? 'selected' : '' }}>
                                                09:30 pm</option>
                                            <option value="10 pm"
                                                {{ $listingSchedules['sun_open'] == '10 pm' ? 'selected' : '' }}>
                                                10 pm</option>
                                            <option value="10.30 pm"
                                                {{ $listingSchedules['sun_open'] == '10.30 pm' ? 'selected' : '' }}>
                                                10:30 pm</option>
                                            <option value="11 pm"
                                                {{ $listingSchedules['sun_open'] == '11 pm' ? 'selected' : '' }}>
                                                11 pm</option>
                                            <option value="11.30 pm"
                                                {{ $listingSchedules['sun_open'] == '11.30 pm' ? 'selected' : '' }}>
                                                11:30 pm</option>
                                            <option value="closed"
                                                {{ $listingSchedules['sun_open'] == 'closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="sunday_closing" id="sunday_closing">
                                            <option value="" class="d-none">{{ __('Closing Time') }}
                                            </option>
                                            <option value="24 hours"
                                                {{ $listingSchedules['sun_close'] == '24 hours' ? 'selected' : '' }}>
                                                24 hours</option>
                                            <option value="12 am"
                                                {{ $listingSchedules['sun_close'] == '12 am' ? 'selected' : '' }}>
                                                12 am</option>
                                            <option value="12.30 am"
                                                {{ $listingSchedules['sun_close'] == '12.30 am' ? 'selected' : '' }}>
                                                12:30 am</option>
                                            <option value="01 am"
                                                {{ $listingSchedules['sun_close'] == '01 am' ? 'selected' : '' }}>
                                                01 am</option>
                                            <option value="01.30 am"
                                                {{ $listingSchedules['sun_close'] == '01.30 am' ? 'selected' : '' }}>
                                                01:30 am</option>
                                            <option value="02 am"
                                                {{ $listingSchedules['sun_close'] == '02 am' ? 'selected' : '' }}>
                                                02 am</option>
                                            <option value="02.30 am"
                                                {{ $listingSchedules['sun_close'] == '02.30 am' ? 'selected' : '' }}>
                                                02:30 am</option>
                                            <option value="03 am"
                                                {{ $listingSchedules['sun_close'] == '03 am' ? 'selected' : '' }}>
                                                03 am</option>
                                            <option value="03.30 am"
                                                {{ $listingSchedules['sun_close'] == '03.30 am' ? 'selected' : '' }}>
                                                03:30 am</option>
                                            <option value="04 am"
                                                {{ $listingSchedules['sun_close'] == '04 am' ? 'selected' : '' }}>
                                                04 am</option>
                                            <option value="04.30 am"
                                                {{ $listingSchedules['sun_close'] == '04.30 am' ? 'selected' : '' }}>
                                                04:30 am</option>
                                            <option value="05 am"
                                                {{ $listingSchedules['sun_close'] == '05 am' ? 'selected' : '' }}>
                                                05 am</option>
                                            <option value="05.30 am"
                                                {{ $listingSchedules['sun_close'] == '05.30 am' ? 'selected' : '' }}>
                                                05:30 am</option>
                                            <option value="06 am"
                                                {{ $listingSchedules['sun_close'] == '06 am' ? 'selected' : '' }}>
                                                06 am</option>
                                            <option value="06.30 am"
                                                {{ $listingSchedules['sun_close'] == '06.30 am' ? 'selected' : '' }}>
                                                06:30 am</option>
                                            <option value="07 am"
                                                {{ $listingSchedules['sun_close'] == '07 am' ? 'selected' : '' }}>
                                                07 am</option>
                                            <option value="07.30 am"
                                                {{ $listingSchedules['sun_close'] == '07.30 am' ? 'selected' : '' }}>
                                                07:30 am</option>
                                            <option value="08 am"
                                                {{ $listingSchedules['sun_close'] == '08 am' ? 'selected' : '' }}>
                                                08 am</option>
                                            <option value="08.30 am"
                                                {{ $listingSchedules['sun_close'] == '08.30 am' ? 'selected' : '' }}>
                                                08:30 am</option>
                                            <option value="09 am"
                                                {{ $listingSchedules['sun_close'] == '09 am' ? 'selected' : '' }}>
                                                09 am</option>
                                            <option value="09.30 am"
                                                {{ $listingSchedules['sun_close'] == '09.30 am' ? 'selected' : '' }}>
                                                09:30 am</option>
                                            <option value="10 am"
                                                {{ $listingSchedules['sun_close'] == '10 am' ? 'selected' : '' }}>
                                                10 am</option>
                                            <option value="10.30 am"
                                                {{ $listingSchedules['sun_close'] == '10.30 am' ? 'selected' : '' }}>
                                                10:30 am</option>
                                            <option value="11 am"
                                                {{ $listingSchedules['sun_close'] == '11 am' ? 'selected' : '' }}>
                                                11 am</option>
                                            <option value="11.30 am"
                                                {{ $listingSchedules['sun_close'] == '11.30 am' ? 'selected' : '' }}>
                                                11:30 am</option>
                                            <option value="12 pm"
                                                {{ $listingSchedules['sun_close'] == '12 pm' ? 'selected' : '' }}>
                                                12 pm</option>
                                            <option value="12.30 pm"
                                                {{ $listingSchedules['sun_close'] == '12.30 pm' ? 'selected' : '' }}>
                                                12:30 pm</option>
                                            <option value="01 pm"
                                                {{ $listingSchedules['sun_close'] == '01 pm' ? 'selected' : '' }}>
                                                01 pm</option>
                                            <option value="01.30 pm"
                                                {{ $listingSchedules['sun_close'] == '01.30 pm' ? 'selected' : '' }}>
                                                01:30 pm</option>
                                            <option value="02 pm"
                                                {{ $listingSchedules['sun_close'] == '02 pm' ? 'selected' : '' }}>
                                                02 pm</option>
                                            <option value="02.30 pm"
                                                {{ $listingSchedules['sun_close'] == '02.30 pm' ? 'selected' : '' }}>
                                                02:30 pm</option>
                                            <option value="03 pm"
                                                {{ $listingSchedules['sun_close'] == '03 pm' ? 'selected' : '' }}>
                                                03 pm</option>
                                            <option value="03.30 pm"
                                                {{ $listingSchedules['sun_close'] == '03.30 pm' ? 'selected' : '' }}>
                                                03:30 pm</option>
                                            <option value="04 pm"
                                                {{ $listingSchedules['sun_close'] == '04 pm' ? 'selected' : '' }}>
                                                04 pm</option>
                                            <option value="04.30 pm"
                                                {{ $listingSchedules['sun_close'] == '04.30 pm' ? 'selected' : '' }}>
                                                04:30 pm</option>
                                            <option value="05 pm"
                                                {{ $listingSchedules['sun_close'] == '05 pm' ? 'selected' : '' }}>
                                                05 pm</option>
                                            <option value="05.30 pm"
                                                {{ $listingSchedules['sun_close'] == '05.30 pm' ? 'selected' : '' }}>
                                                05:30 pm</option>
                                            <option value="06 pm"
                                                {{ $listingSchedules['sun_close'] == '06 pm' ? 'selected' : '' }}>
                                                06 pm</option>
                                            <option value="06.30 pm"
                                                {{ $listingSchedules['sun_close'] == '06.30 pm' ? 'selected' : '' }}>
                                                06:30 pm</option>
                                            <option value="07 pm"
                                                {{ $listingSchedules['sun_close'] == '07 pm' ? 'selected' : '' }}>
                                                07 pm</option>
                                            <option value="07.30 pm"
                                                {{ $listingSchedules['sun_close'] == '07.30 pm' ? 'selected' : '' }}>
                                                07:30 pm</option>
                                            <option value="08 pm"
                                                {{ $listingSchedules['sun_close'] == '08 pm' ? 'selected' : '' }}>
                                                08 pm</option>
                                            <option value="08.30 pm"
                                                {{ $listingSchedules['sun_close'] == '08.30 pm' ? 'selected' : '' }}>
                                                08:30 pm</option>
                                            <option value="09 pm"
                                                {{ $listingSchedules['sun_close'] == '09 pm' ? 'selected' : '' }}>
                                                09 pm</option>
                                            <option value="09.30 pm"
                                                {{ $listingSchedules['sun_close'] == '09.30 pm' ? 'selected' : '' }}>
                                                09:30 pm</option>
                                            <option value="10 pm"
                                                {{ $listingSchedules['sun_close'] == '10 pm' ? 'selected' : '' }}>
                                                10 pm</option>
                                            <option value="10.30 pm"
                                                {{ $listingSchedules['sun_close'] == '10.30 pm' ? 'selected' : '' }}>
                                                10:30 pm</option>
                                            <option value="11 pm"
                                                {{ $listingSchedules['sun_close'] == '11 pm' ? 'selected' : '' }}>
                                                11 pm</option>
                                            <option value="11.30 pm"
                                                {{ $listingSchedules['sun_close'] == '11.30 pm' ? 'selected' : '' }}>
                                                11:30 pm</option>
                                            <option value="closed"
                                                {{ $listingSchedules['sun_close'] == 'closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Monday') }}</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="monday_opening" id="monday_opening">
                                            <option value="" class="d-none">{{ __('Opening Time') }}
                                            </option>
                                            <option value="24 hours"
                                                {{ $listingSchedules['mon_open'] == '24 hours' ? 'selected' : '' }}>
                                                24 hours</option>
                                            <option value="12 am"
                                                {{ $listingSchedules['mon_open'] == '12 am' ? 'selected' : '' }}>
                                                12 am</option>
                                            <option value="1.:30 am"
                                                {{ $listingSchedules['mon_open'] == '12.30 am' ? 'selected' : '' }}>
                                                12:30 am</option>
                                            <option value="01 am"
                                                {{ $listingSchedules['mon_open'] == '01 am' ? 'selected' : '' }}>
                                                01 am</option>
                                            <option value="0.:30 am"
                                                {{ $listingSchedules['mon_open'] == '01.30 am' ? 'selected' : '' }}>
                                                01:30 am</option>
                                            <option value="02 am"
                                                {{ $listingSchedules['mon_open'] == '02 am' ? 'selected' : '' }}>
                                                02 am</option>
                                            <option value="0.:30 am"
                                                {{ $listingSchedules['mon_open'] == '02.30 am' ? 'selected' : '' }}>
                                                02:30 am</option>
                                            <option value="03 am"
                                                {{ $listingSchedules['mon_open'] == '03 am' ? 'selected' : '' }}>
                                                03 am</option>
                                            <option value="0.:30 am"
                                                {{ $listingSchedules['mon_open'] == '03.30 am' ? 'selected' : '' }}>
                                                03:30 am</option>
                                            <option value="04 am"
                                                {{ $listingSchedules['mon_open'] == '04 am' ? 'selected' : '' }}>
                                                04 am</option>
                                            <option value="0.:30 am"
                                                {{ $listingSchedules['mon_open'] == '04.30 am' ? 'selected' : '' }}>
                                                04:30 am</option>
                                            <option value="05 am"
                                                {{ $listingSchedules['mon_open'] == '05 am' ? 'selected' : '' }}>
                                                05 am</option>
                                            <option value="0.:30 am"
                                                {{ $listingSchedules['mon_open'] == '05.30 am' ? 'selected' : '' }}>
                                                05:30 am</option>
                                            <option value="06 am"
                                                {{ $listingSchedules['mon_open'] == '06 am' ? 'selected' : '' }}>
                                                06 am</option>
                                            <option value="0.:30 am"
                                                {{ $listingSchedules['mon_open'] == '06.30 am' ? 'selected' : '' }}>
                                                06:30 am</option>
                                            <option value="07 am"
                                                {{ $listingSchedules['mon_open'] == '07 am' ? 'selected' : '' }}>
                                                07 am</option>
                                            <option value="0.:30 am"
                                                {{ $listingSchedules['mon_open'] == '07.30 am' ? 'selected' : '' }}>
                                                07:30 am</option>
                                            <option value="08 am"
                                                {{ $listingSchedules['mon_open'] == '08 am' ? 'selected' : '' }}>
                                                08 am</option>
                                            <option value="0.:30 am"
                                                {{ $listingSchedules['mon_open'] == '08.30 am' ? 'selected' : '' }}>
                                                08:30 am</option>
                                            <option value="09 am"
                                                {{ $listingSchedules['mon_open'] == '09 am' ? 'selected' : '' }}>
                                                09 am</option>
                                            <option value="0.:30 am"
                                                {{ $listingSchedules['mon_open'] == '09.30 am' ? 'selected' : '' }}>
                                                09:30 am</option>
                                            <option value="10 am"
                                                {{ $listingSchedules['mon_open'] == '10 am' ? 'selected' : '' }}>
                                                10 am</option>
                                            <option value="1.:30 am"
                                                {{ $listingSchedules['mon_open'] == '10.30 am' ? 'selected' : '' }}>
                                                10:30 am</option>
                                            <option value="11 am"
                                                {{ $listingSchedules['mon_open'] == '11 am' ? 'selected' : '' }}>
                                                11 am</option>
                                            <option value="1.:30 am"
                                                {{ $listingSchedules['mon_open'] == '11.30 am' ? 'selected' : '' }}>
                                                11:30 am</option>
                                            <option value="12 pm"
                                                {{ $listingSchedules['mon_open'] == '12 pm' ? 'selected' : '' }}>
                                                12 pm</option>
                                            <option value="1.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '12.30 pm' ? 'selected' : '' }}>
                                                12:30 pm</option>
                                            <option value="01 pm"
                                                {{ $listingSchedules['mon_open'] == '01 pm' ? 'selected' : '' }}>
                                                01 pm</option>
                                            <option value="0.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '01.30 pm' ? 'selected' : '' }}>
                                                01:30 pm</option>
                                            <option value="02 pm"
                                                {{ $listingSchedules['mon_open'] == '02 pm' ? 'selected' : '' }}>
                                                02 pm</option>
                                            <option value="0.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '02.30 pm' ? 'selected' : '' }}>
                                                02:30 pm</option>
                                            <option value="03 pm"
                                                {{ $listingSchedules['mon_open'] == '03 pm' ? 'selected' : '' }}>
                                                03 pm</option>
                                            <option value="0.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '03.30 pm' ? 'selected' : '' }}>
                                                03:30 pm</option>
                                            <option value="04 pm"
                                                {{ $listingSchedules['mon_open'] == '04 pm' ? 'selected' : '' }}>
                                                04 pm</option>
                                            <option value="0.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '04.30 pm' ? 'selected' : '' }}>
                                                04:30 pm</option>
                                            <option value="05 pm"
                                                {{ $listingSchedules['mon_open'] == '05 pm' ? 'selected' : '' }}>
                                                05 pm</option>
                                            <option value="0.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '05.30 pm' ? 'selected' : '' }}>
                                                05:30 pm</option>
                                            <option value="06 pm"
                                                {{ $listingSchedules['mon_open'] == '06 pm' ? 'selected' : '' }}>
                                                06 pm</option>
                                            <option value="0.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '06.30 pm' ? 'selected' : '' }}>
                                                06:30 pm</option>
                                            <option value="07 pm"
                                                {{ $listingSchedules['mon_open'] == '07 pm' ? 'selected' : '' }}>
                                                07 pm</option>
                                            <option value="0.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '07.30 pm' ? 'selected' : '' }}>
                                                07:30 pm</option>
                                            <option value="08 pm"
                                                {{ $listingSchedules['mon_open'] == '08 pm' ? 'selected' : '' }}>
                                                08 pm</option>
                                            <option value="0.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '08.30 pm' ? 'selected' : '' }}>
                                                08:30 pm</option>
                                            <option value="09 pm"
                                                {{ $listingSchedules['mon_open'] == '09 pm' ? 'selected' : '' }}>
                                                09 pm</option>
                                            <option value="0.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '09.30 pm' ? 'selected' : '' }}>
                                                09:30 pm</option>
                                            <option value="10 pm"
                                                {{ $listingSchedules['mon_open'] == '10 pm' ? 'selected' : '' }}>
                                                10 pm</option>
                                            <option value="1.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '10.30 pm' ? 'selected' : '' }}>
                                                10:30 pm</option>
                                            <option value="11 pm"
                                                {{ $listingSchedules['mon_open'] == '11 pm' ? 'selected' : '' }}>
                                                11 pm</option>
                                            <option value="1.:30 pm"
                                                {{ $listingSchedules['mon_open'] == '11.30 pm' ? 'selected' : '' }}>
                                                11:30 pm</option>
                                            <option value="closed"
                                                {{ $listingSchedules['mon_open'] == 'closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="monday_closing" id="monday_closing">
                                            <option value="" class="d-none">{{ __('Closing Time') }}
                                            </option>
                                            <option value="24 hours"
                                                {{ $listingSchedules['mon_close'] == '24 hours' ? 'selected' : '' }}>
                                                24 hours</option>
                                            <option value="12 am"
                                                {{ $listingSchedules['mon_close'] == '12 am' ? 'selected' : '' }}>
                                                12 am</option>
                                            <option value="12.30 am"
                                                {{ $listingSchedules['mon_close'] == '12.30 am' ? 'selected' : '' }}>
                                                12:30 am</option>
                                            <option value="01 am"
                                                {{ $listingSchedules['mon_close'] == '01 am' ? 'selected' : '' }}>
                                                01 am</option>
                                            <option value="01.30 am"
                                                {{ $listingSchedules['mon_close'] == '01.30 am' ? 'selected' : '' }}>
                                                01:30 am</option>
                                            <option value="02 am"
                                                {{ $listingSchedules['mon_close'] == '02 am' ? 'selected' : '' }}>
                                                02 am</option>
                                            <option value="02.30 am"
                                                {{ $listingSchedules['mon_close'] == '02.30 am' ? 'selected' : '' }}>
                                                02:30 am</option>
                                            <option value="03 am"
                                                {{ $listingSchedules['mon_close'] == '03 am' ? 'selected' : '' }}>
                                                03 am</option>
                                            <option value="03.30 am"
                                                {{ $listingSchedules['mon_close'] == '03.30 am' ? 'selected' : '' }}>
                                                03:30 am</option>
                                            <option value="04 am"
                                                {{ $listingSchedules['mon_close'] == '04 am' ? 'selected' : '' }}>
                                                04 am</option>
                                            <option value="04.30 am"
                                                {{ $listingSchedules['mon_close'] == '04.30 am' ? 'selected' : '' }}>
                                                04:30 am</option>
                                            <option value="05 am"
                                                {{ $listingSchedules['mon_close'] == '05 am' ? 'selected' : '' }}>
                                                05 am</option>
                                            <option value="05.30 am"
                                                {{ $listingSchedules['mon_close'] == '05.30 am' ? 'selected' : '' }}>
                                                05:30 am</option>
                                            <option value="06 am"
                                                {{ $listingSchedules['mon_close'] == '06 am' ? 'selected' : '' }}>
                                                06 am</option>
                                            <option value="06.30 am"
                                                {{ $listingSchedules['mon_close'] == '06.30 am' ? 'selected' : '' }}>
                                                06:30 am</option>
                                            <option value="07 am"
                                                {{ $listingSchedules['mon_close'] == '07 am' ? 'selected' : '' }}>
                                                07 am</option>
                                            <option value="07.30 am"
                                                {{ $listingSchedules['mon_close'] == '07.30 am' ? 'selected' : '' }}>
                                                07:30 am</option>
                                            <option value="08 am"
                                                {{ $listingSchedules['mon_close'] == '08 am' ? 'selected' : '' }}>
                                                08 am</option>
                                            <option value="08.30 am"
                                                {{ $listingSchedules['mon_close'] == '08.30 am' ? 'selected' : '' }}>
                                                08:30 am</option>
                                            <option value="09 am"
                                                {{ $listingSchedules['mon_close'] == '09 am' ? 'selected' : '' }}>
                                                09 am</option>
                                            <option value="09.30 am"
                                                {{ $listingSchedules['mon_close'] == '09.30 am' ? 'selected' : '' }}>
                                                09:30 am</option>
                                            <option value="10 am"
                                                {{ $listingSchedules['mon_close'] == '10 am' ? 'selected' : '' }}>
                                                10 am</option>
                                            <option value="10.30 am"
                                                {{ $listingSchedules['mon_close'] == '10.30 am' ? 'selected' : '' }}>
                                                10:30 am</option>
                                            <option value="11 am"
                                                {{ $listingSchedules['mon_close'] == '11 am' ? 'selected' : '' }}>
                                                11 am</option>
                                            <option value="11.30 am"
                                                {{ $listingSchedules['mon_close'] == '11.30 am' ? 'selected' : '' }}>
                                                11:30 am</option>
                                            <option value="12 pm"
                                                {{ $listingSchedules['mon_close'] == '12 pm' ? 'selected' : '' }}>
                                                12 pm</option>
                                            <option value="12.30 pm"
                                                {{ $listingSchedules['mon_close'] == '12.30 pm' ? 'selected' : '' }}>
                                                12:30 pm</option>
                                            <option value="01 pm"
                                                {{ $listingSchedules['mon_close'] == '01 pm' ? 'selected' : '' }}>
                                                01 pm</option>
                                            <option value="01.30 pm"
                                                {{ $listingSchedules['mon_close'] == '01.30 pm' ? 'selected' : '' }}>
                                                01:30 pm</option>
                                            <option value="02 pm"
                                                {{ $listingSchedules['mon_close'] == '02 pm' ? 'selected' : '' }}>
                                                02 pm</option>
                                            <option value="02.30 pm"
                                                {{ $listingSchedules['mon_close'] == '02.30 pm' ? 'selected' : '' }}>
                                                02:30 pm</option>
                                            <option value="03 pm"
                                                {{ $listingSchedules['mon_close'] == '03 pm' ? 'selected' : '' }}>
                                                03 pm</option>
                                            <option value="03.30 pm"
                                                {{ $listingSchedules['mon_close'] == '03.30 pm' ? 'selected' : '' }}>
                                                03:30 pm</option>
                                            <option value="04 pm"
                                                {{ $listingSchedules['mon_close'] == '04 pm' ? 'selected' : '' }}>
                                                04 pm</option>
                                            <option value="04.30 pm"
                                                {{ $listingSchedules['mon_close'] == '04.30 pm' ? 'selected' : '' }}>
                                                04:30 pm</option>
                                            <option value="05 pm"
                                                {{ $listingSchedules['mon_close'] == '05 pm' ? 'selected' : '' }}>
                                                05 pm</option>
                                            <option value="05.30 pm"
                                                {{ $listingSchedules['mon_close'] == '05.30 pm' ? 'selected' : '' }}>
                                                05:30 pm</option>
                                            <option value="06 pm"
                                                {{ $listingSchedules['mon_close'] == '06 pm' ? 'selected' : '' }}>
                                                06 pm</option>
                                            <option value="06.30 pm"
                                                {{ $listingSchedules['mon_close'] == '06.30 pm' ? 'selected' : '' }}>
                                                06:30 pm</option>
                                            <option value="07 pm"
                                                {{ $listingSchedules['mon_close'] == '07 pm' ? 'selected' : '' }}>
                                                07 pm</option>
                                            <option value="07.30 pm"
                                                {{ $listingSchedules['mon_close'] == '07.30 pm' ? 'selected' : '' }}>
                                                07:30 pm</option>
                                            <option value="08 pm"
                                                {{ $listingSchedules['mon_close'] == '08 pm' ? 'selected' : '' }}>
                                                08 pm</option>
                                            <option value="08.30 pm"
                                                {{ $listingSchedules['mon_close'] == '08.30 pm' ? 'selected' : '' }}>
                                                08:30 pm</option>
                                            <option value="09 pm"
                                                {{ $listingSchedules['mon_close'] == '09 pm' ? 'selected' : '' }}>
                                                09 pm</option>
                                            <option value="09.30 pm"
                                                {{ $listingSchedules['mon_close'] == '09.30 pm' ? 'selected' : '' }}>
                                                09:30 pm</option>
                                            <option value="10 pm"
                                                {{ $listingSchedules['mon_close'] == '10 pm' ? 'selected' : '' }}>
                                                10 pm</option>
                                            <option value="10.30 pm"
                                                {{ $listingSchedules['mon_close'] == '10.30 pm' ? 'selected' : '' }}>
                                                10:30 pm</option>
                                            <option value="11 pm"
                                                {{ $listingSchedules['mon_close'] == '11 pm' ? 'selected' : '' }}>
                                                11 pm</option>
                                            <option value="11.30 pm"
                                                {{ $listingSchedules['mon_close'] == '11.30 pm' ? 'selected' : '' }}>
                                                11:30 pm</option>
                                            <option value="closed"
                                                {{ $listingSchedules['mon_close'] == 'closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Tuesday') }}</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="tuesday_opening" id="tuesday_opening">
                                            <option value="" class="d-none">{{ __('Opening Time') }}
                                            </option>
                                            <option value="24 hours"
                                                {{ $listingSchedules['tue_open'] == '24 hours' ? 'selected' : '' }}>
                                                24 hours</option>
                                            <option value="12 am"
                                                {{ $listingSchedules['tue_open'] == '12 am' ? 'selected' : '' }}>
                                                12 am</option>
                                            <option value="12.30 am"
                                                {{ $listingSchedules['tue_open'] == '12.30 am' ? 'selected' : '' }}>
                                                12:30 am</option>
                                            <option value="01 am"
                                                {{ $listingSchedules['tue_open'] == '01 am' ? 'selected' : '' }}>
                                                01 am</option>
                                            <option value="01.30 am"
                                                {{ $listingSchedules['tue_open'] == '01.30 am' ? 'selected' : '' }}>
                                                01:30 am</option>
                                            <option value="02 am"
                                                {{ $listingSchedules['tue_open'] == '02 am' ? 'selected' : '' }}>
                                                02 am</option>
                                            <option value="02.30 am"
                                                {{ $listingSchedules['tue_open'] == '02.30 am' ? 'selected' : '' }}>
                                                02:30 am</option>
                                            <option value="03 am"
                                                {{ $listingSchedules['tue_open'] == '03 am' ? 'selected' : '' }}>
                                                03 am</option>
                                            <option value="03.30 am"
                                                {{ $listingSchedules['tue_open'] == '03.30 am' ? 'selected' : '' }}>
                                                03:30 am</option>
                                            <option value="04 am"
                                                {{ $listingSchedules['tue_open'] == '04 am' ? 'selected' : '' }}>
                                                04 am</option>
                                            <option value="04.30 am"
                                                {{ $listingSchedules['tue_open'] == '04.30 am' ? 'selected' : '' }}>
                                                04:30 am</option>
                                            <option value="05 am"
                                                {{ $listingSchedules['tue_open'] == '05 am' ? 'selected' : '' }}>
                                                05 am</option>
                                            <option value="05.30 am"
                                                {{ $listingSchedules['tue_open'] == '05.30 am' ? 'selected' : '' }}>
                                                05:30 am</option>
                                            <option value="06 am"
                                                {{ $listingSchedules['tue_open'] == '06 am' ? 'selected' : '' }}>
                                                06 am</option>
                                            <option value="06.30 am"
                                                {{ $listingSchedules['tue_open'] == '06.30 am' ? 'selected' : '' }}>
                                                06:30 am</option>
                                            <option value="07 am"
                                                {{ $listingSchedules['tue_open'] == '07 am' ? 'selected' : '' }}>
                                                07 am</option>
                                            <option value="07.30 am"
                                                {{ $listingSchedules['tue_open'] == '07.30 am' ? 'selected' : '' }}>
                                                07:30 am</option>
                                            <option value="08 am"
                                                {{ $listingSchedules['tue_open'] == '08 am' ? 'selected' : '' }}>
                                                08 am</option>
                                            <option value="08.30 am"
                                                {{ $listingSchedules['tue_open'] == '08.30 am' ? 'selected' : '' }}>
                                                08:30 am</option>
                                            <option value="09 am"
                                                {{ $listingSchedules['tue_open'] == '09 am' ? 'selected' : '' }}>
                                                09 am</option>
                                            <option value="09.30 am"
                                                {{ $listingSchedules['tue_open'] == '09.30 am' ? 'selected' : '' }}>
                                                09:30 am</option>
                                            <option value="10 am"
                                                {{ $listingSchedules['tue_open'] == '10 am' ? 'selected' : '' }}>
                                                10 am</option>
                                            <option value="10.30 am"
                                                {{ $listingSchedules['tue_open'] == '10.30 am' ? 'selected' : '' }}>
                                                10:30 am</option>
                                            <option value="11 am"
                                                {{ $listingSchedules['tue_open'] == '11 am' ? 'selected' : '' }}>
                                                11 am</option>
                                            <option value="11.30 am"
                                                {{ $listingSchedules['tue_open'] == '11.30 am' ? 'selected' : '' }}>
                                                11:30 am</option>
                                            <option value="12 pm"
                                                {{ $listingSchedules['tue_open'] == '12 pm' ? 'selected' : '' }}>
                                                12 pm</option>
                                            <option value="12.30 pm"
                                                {{ $listingSchedules['tue_open'] == '12.30 pm' ? 'selected' : '' }}>
                                                12:30 pm</option>
                                            <option value="01 pm"
                                                {{ $listingSchedules['tue_open'] == '01 pm' ? 'selected' : '' }}>
                                                01 pm</option>
                                            <option value="01.30 pm"
                                                {{ $listingSchedules['tue_open'] == '01.30 pm' ? 'selected' : '' }}>
                                                01:30 pm</option>
                                            <option value="02 pm"
                                                {{ $listingSchedules['tue_open'] == '02 pm' ? 'selected' : '' }}>
                                                02 pm</option>
                                            <option value="02.30 pm"
                                                {{ $listingSchedules['tue_open'] == '02.30 pm' ? 'selected' : '' }}>
                                                02:30 pm</option>
                                            <option value="03 pm"
                                                {{ $listingSchedules['tue_open'] == '03 pm' ? 'selected' : '' }}>
                                                03 pm</option>
                                            <option value="03.30 pm"
                                                {{ $listingSchedules['tue_open'] == '03.30 pm' ? 'selected' : '' }}>
                                                03:30 pm</option>
                                            <option value="04 pm"
                                                {{ $listingSchedules['tue_open'] == '04 pm' ? 'selected' : '' }}>
                                                04 pm</option>
                                            <option value="04.30 pm"
                                                {{ $listingSchedules['tue_open'] == '04.30 pm' ? 'selected' : '' }}>
                                                04:30 pm</option>
                                            <option value="05 pm"
                                                {{ $listingSchedules['tue_open'] == '05 pm' ? 'selected' : '' }}>
                                                05 pm</option>
                                            <option value="05.30 pm"
                                                {{ $listingSchedules['tue_open'] == '05.30 pm' ? 'selected' : '' }}>
                                                05:30 pm</option>
                                            <option value="06 pm"
                                                {{ $listingSchedules['tue_open'] == '06 pm' ? 'selected' : '' }}>
                                                06 pm</option>
                                            <option value="06.30 pm"
                                                {{ $listingSchedules['tue_open'] == '06.30 pm' ? 'selected' : '' }}>
                                                06:30 pm</option>
                                            <option value="07 pm"
                                                {{ $listingSchedules['tue_open'] == '07 pm' ? 'selected' : '' }}>
                                                07 pm</option>
                                            <option value="07.30 pm"
                                                {{ $listingSchedules['tue_open'] == '07.30 pm' ? 'selected' : '' }}>
                                                07:30 pm</option>
                                            <option value="08 pm"
                                                {{ $listingSchedules['tue_open'] == '08 pm' ? 'selected' : '' }}>
                                                08 pm</option>
                                            <option value="08.30 pm"
                                                {{ $listingSchedules['tue_open'] == '08.30 pm' ? 'selected' : '' }}>
                                                08:30 pm</option>
                                            <option value="09 pm"
                                                {{ $listingSchedules['tue_open'] == '09 pm' ? 'selected' : '' }}>
                                                09 pm</option>
                                            <option value="09.30 pm"
                                                {{ $listingSchedules['tue_open'] == '09.30 pm' ? 'selected' : '' }}>
                                                09:30 pm</option>
                                            <option value="10 pm"
                                                {{ $listingSchedules['tue_open'] == '10 pm' ? 'selected' : '' }}>
                                                10 pm</option>
                                            <option value="10.30 pm"
                                                {{ $listingSchedules['tue_open'] == '10.30 pm' ? 'selected' : '' }}>
                                                10:30 pm</option>
                                            <option value="11 pm"
                                                {{ $listingSchedules['tue_open'] == '11 pm' ? 'selected' : '' }}>
                                                11 pm</option>
                                            <option value="11.30 pm"
                                                {{ $listingSchedules['tue_open'] == '11.30 pm' ? 'selected' : '' }}>
                                                11:30 pm</option>
                                            <option value="closed"
                                                {{ $listingSchedules['tue_open'] == 'closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="tuesday_closing" id="tuesday_closing">
                                            <option value="" class="d-none">{{ __('Closing Time') }}
                                            </option>
                                            <option value="24 hours"
                                                {{ $listingSchedules['tue_close'] == '24 hours' ? 'selected' : '' }}>
                                                24 hours</option>
                                            <option value="12 am"
                                                {{ $listingSchedules['tue_close'] == '12 am' ? 'selected' : '' }}>
                                                12 am</option>
                                            <option value="12.30 am"
                                                {{ $listingSchedules['tue_close'] == '12.30 am' ? 'selected' : '' }}>
                                                12:30 am</option>
                                            <option value="01 am"
                                                {{ $listingSchedules['tue_close'] == '01 am' ? 'selected' : '' }}>
                                                01 am</option>
                                            <option value="01.30 am"
                                                {{ $listingSchedules['tue_close'] == '01.30 am' ? 'selected' : '' }}>
                                                01:30 am</option>
                                            <option value="02 am"
                                                {{ $listingSchedules['tue_close'] == '02 am' ? 'selected' : '' }}>
                                                02 am</option>
                                            <option value="02.30 am"
                                                {{ $listingSchedules['tue_close'] == '02.30 am' ? 'selected' : '' }}>
                                                02:30 am</option>
                                            <option value="03 am"
                                                {{ $listingSchedules['tue_close'] == '03 am' ? 'selected' : '' }}>
                                                03 am</option>
                                            <option value="03.30 am"
                                                {{ $listingSchedules['tue_close'] == '03.30 am' ? 'selected' : '' }}>
                                                03:30 am</option>
                                            <option value="04 am"
                                                {{ $listingSchedules['tue_close'] == '04 am' ? 'selected' : '' }}>
                                                04 am</option>
                                            <option value="04.30 am"
                                                {{ $listingSchedules['tue_close'] == '04.30 am' ? 'selected' : '' }}>
                                                04:30 am</option>
                                            <option value="05 am"
                                                {{ $listingSchedules['tue_close'] == '05 am' ? 'selected' : '' }}>
                                                05 am</option>
                                            <option value="05.30 am"
                                                {{ $listingSchedules['tue_close'] == '05.30 am' ? 'selected' : '' }}>
                                                05:30 am</option>
                                            <option value="06 am"
                                                {{ $listingSchedules['tue_close'] == '06 am' ? 'selected' : '' }}>
                                                06 am</option>
                                            <option value="06.30 am"
                                                {{ $listingSchedules['tue_close'] == '06.30 am' ? 'selected' : '' }}>
                                                06:30 am</option>
                                            <option value="07 am"
                                                {{ $listingSchedules['tue_close'] == '07 am' ? 'selected' : '' }}>
                                                07 am</option>
                                            <option value="07.30 am"
                                                {{ $listingSchedules['tue_close'] == '07.30 am' ? 'selected' : '' }}>
                                                07:30 am</option>
                                            <option value="08 am"
                                                {{ $listingSchedules['tue_close'] == '08 am' ? 'selected' : '' }}>
                                                08 am</option>
                                            <option value="08.30 am"
                                                {{ $listingSchedules['tue_close'] == '08.30 am' ? 'selected' : '' }}>
                                                08:30 am</option>
                                            <option value="09 am"
                                                {{ $listingSchedules['tue_close'] == '09 am' ? 'selected' : '' }}>
                                                09 am</option>
                                            <option value="09.30 am"
                                                {{ $listingSchedules['tue_close'] == '09.30 am' ? 'selected' : '' }}>
                                                09:30 am</option>
                                            <option value="10 am"
                                                {{ $listingSchedules['tue_close'] == '10 am' ? 'selected' : '' }}>
                                                10 am</option>
                                            <option value="10.30 am"
                                                {{ $listingSchedules['tue_close'] == '10.30 am' ? 'selected' : '' }}>
                                                10:30 am</option>
                                            <option value="11 am"
                                                {{ $listingSchedules['tue_close'] == '11 am' ? 'selected' : '' }}>
                                                11 am</option>
                                            <option value="11.30 am"
                                                {{ $listingSchedules['tue_close'] == '11.30 am' ? 'selected' : '' }}>
                                                11:30 am</option>
                                            <option value="12 pm"
                                                {{ $listingSchedules['tue_close'] == '12 pm' ? 'selected' : '' }}>
                                                12 pm</option>
                                            <option value="12.30 pm"
                                                {{ $listingSchedules['tue_close'] == '12.30 pm' ? 'selected' : '' }}>
                                                12:30 pm</option>
                                            <option value="01 pm"
                                                {{ $listingSchedules['tue_close'] == '01 pm' ? 'selected' : '' }}>
                                                01 pm</option>
                                            <option value="01.30 pm"
                                                {{ $listingSchedules['tue_close'] == '01.30 pm' ? 'selected' : '' }}>
                                                01:30 pm</option>
                                            <option value="02 pm"
                                                {{ $listingSchedules['tue_close'] == '02 pm' ? 'selected' : '' }}>
                                                02 pm</option>
                                            <option value="02.30 pm"
                                                {{ $listingSchedules['tue_close'] == '02.30 pm' ? 'selected' : '' }}>
                                                02:30 pm</option>
                                            <option value="03 pm"
                                                {{ $listingSchedules['tue_close'] == '03 pm' ? 'selected' : '' }}>
                                                03 pm</option>
                                            <option value="03.30 pm"
                                                {{ $listingSchedules['tue_close'] == '03.30 pm' ? 'selected' : '' }}>
                                                03:30 pm</option>
                                            <option value="04 pm"
                                                {{ $listingSchedules['tue_close'] == '04 pm' ? 'selected' : '' }}>
                                                04 pm</option>
                                            <option value="04.30 pm"
                                                {{ $listingSchedules['tue_close'] == '04.30 pm' ? 'selected' : '' }}>
                                                04:30 pm</option>
                                            <option value="05 pm"
                                                {{ $listingSchedules['tue_close'] == '05 pm' ? 'selected' : '' }}>
                                                05 pm</option>
                                            <option value="05.30 pm"
                                                {{ $listingSchedules['tue_close'] == '05.30 pm' ? 'selected' : '' }}>
                                                05:30 pm</option>
                                            <option value="06 pm"
                                                {{ $listingSchedules['tue_close'] == '06 pm' ? 'selected' : '' }}>
                                                06 pm</option>
                                            <option value="06.30 pm"
                                                {{ $listingSchedules['tue_close'] == '06.30 pm' ? 'selected' : '' }}>
                                                06:30 pm</option>
                                            <option value="07 pm"
                                                {{ $listingSchedules['tue_close'] == '07 pm' ? 'selected' : '' }}>
                                                07 pm</option>
                                            <option value="07.30 pm"
                                                {{ $listingSchedules['tue_close'] == '07.30 pm' ? 'selected' : '' }}>
                                                07:30 pm</option>
                                            <option value="08 pm"
                                                {{ $listingSchedules['tue_close'] == '08 pm' ? 'selected' : '' }}>
                                                08 pm</option>
                                            <option value="08.30 pm"
                                                {{ $listingSchedules['tue_close'] == '08.30 pm' ? 'selected' : '' }}>
                                                08:30 pm</option>
                                            <option value="09 pm"
                                                {{ $listingSchedules['tue_close'] == '09 pm' ? 'selected' : '' }}>
                                                09 pm</option>
                                            <option value="09.30 pm"
                                                {{ $listingSchedules['tue_close'] == '09.30 pm' ? 'selected' : '' }}>
                                                09:30 pm</option>
                                            <option value="10 pm"
                                                {{ $listingSchedules['tue_close'] == '10 pm' ? 'selected' : '' }}>
                                                10 pm</option>
                                            <option value="10.30 pm"
                                                {{ $listingSchedules['tue_close'] == '10.30 pm' ? 'selected' : '' }}>
                                                10:30 pm</option>
                                            <option value="11 pm"
                                                {{ $listingSchedules['tue_close'] == '11 pm' ? 'selected' : '' }}>
                                                11 pm</option>
                                            <option value="11.30 pm"
                                                {{ $listingSchedules['tue_close'] == '11.30 pm' ? 'selected' : '' }}>
                                                11:30 pm</option>
                                            <option value="closed"
                                                {{ $listingSchedules['tue_close'] == 'closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Wednesday') }}</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="wednesday_opening" id="wednesday_opening">
                                            <option value="" class="d-none">{{ __('Opening Time') }}
                                            </option>
                                            <option value="24 hours"
                                                {{ $listingSchedules['wed_open'] == '24 hours' ? 'selected' : '' }}>
                                                24 hours</option>
                                            <option value="12 am"
                                                {{ $listingSchedules['wed_open'] == '12 am' ? 'selected' : '' }}>
                                                12 am</option>
                                            <option value="12.30 am"
                                                {{ $listingSchedules['wed_open'] == '12.30 am' ? 'selected' : '' }}>
                                                12:30 am</option>
                                            <option value="01 am"
                                                {{ $listingSchedules['wed_open'] == '01 am' ? 'selected' : '' }}>
                                                01 am</option>
                                            <option value="01.30 am"
                                                {{ $listingSchedules['wed_open'] == '01.30 am' ? 'selected' : '' }}>
                                                01:30 am</option>
                                            <option value="02 am"
                                                {{ $listingSchedules['wed_open'] == '02 am' ? 'selected' : '' }}>
                                                02 am</option>
                                            <option value="02.30 am"
                                                {{ $listingSchedules['wed_open'] == '02.30 am' ? 'selected' : '' }}>
                                                02:30 am</option>
                                            <option value="03 am"
                                                {{ $listingSchedules['wed_open'] == '03 am' ? 'selected' : '' }}>
                                                03 am</option>
                                            <option value="03.30 am"
                                                {{ $listingSchedules['wed_open'] == '03.30 am' ? 'selected' : '' }}>
                                                03:30 am</option>
                                            <option value="04 am"
                                                {{ $listingSchedules['wed_open'] == '04 am' ? 'selected' : '' }}>
                                                04 am</option>
                                            <option value="04.30 am"
                                                {{ $listingSchedules['wed_open'] == '04.30 am' ? 'selected' : '' }}>
                                                04:30 am</option>
                                            <option value="05 am"
                                                {{ $listingSchedules['wed_open'] == '05 am' ? 'selected' : '' }}>
                                                05 am</option>
                                            <option value="05.30 am"
                                                {{ $listingSchedules['wed_open'] == '05.30 am' ? 'selected' : '' }}>
                                                05:30 am</option>
                                            <option value="06 am"
                                                {{ $listingSchedules['wed_open'] == '06 am' ? 'selected' : '' }}>
                                                06 am</option>
                                            <option value="06.30 am"
                                                {{ $listingSchedules['wed_open'] == '06.30 am' ? 'selected' : '' }}>
                                                06:30 am</option>
                                            <option value="07 am"
                                                {{ $listingSchedules['wed_open'] == '07 am' ? 'selected' : '' }}>
                                                07 am</option>
                                            <option value="07.30 am"
                                                {{ $listingSchedules['wed_open'] == '07.30 am' ? 'selected' : '' }}>
                                                07:30 am</option>
                                            <option value="08 am"
                                                {{ $listingSchedules['wed_open'] == '08 am' ? 'selected' : '' }}>
                                                08 am</option>
                                            <option value="08.30 am"
                                                {{ $listingSchedules['wed_open'] == '08.30 am' ? 'selected' : '' }}>
                                                08:30 am</option>
                                            <option value="09 am"
                                                {{ $listingSchedules['wed_open'] == '09 am' ? 'selected' : '' }}>
                                                09 am</option>
                                            <option value="09.30 am"
                                                {{ $listingSchedules['wed_open'] == '09.30 am' ? 'selected' : '' }}>
                                                09:30 am</option>
                                            <option value="10 am"
                                                {{ $listingSchedules['wed_open'] == '10 am' ? 'selected' : '' }}>
                                                10 am</option>
                                            <option value="10.30 am"
                                                {{ $listingSchedules['wed_open'] == '10.30 am' ? 'selected' : '' }}>
                                                10:30 am</option>
                                            <option value="11 am"
                                                {{ $listingSchedules['wed_open'] == '11 am' ? 'selected' : '' }}>
                                                11 am</option>
                                            <option value="11.30 am"
                                                {{ $listingSchedules['wed_open'] == '11.30 am' ? 'selected' : '' }}>
                                                11:30 am</option>
                                            <option value="12 pm"
                                                {{ $listingSchedules['wed_open'] == '12 pm' ? 'selected' : '' }}>
                                                12 pm</option>
                                            <option value="12.30 pm"
                                                {{ $listingSchedules['wed_open'] == '12.30 pm' ? 'selected' : '' }}>
                                                12:30 pm</option>
                                            <option value="01 pm"
                                                {{ $listingSchedules['wed_open'] == '01 pm' ? 'selected' : '' }}>
                                                01 pm</option>
                                            <option value="01.30 pm"
                                                {{ $listingSchedules['wed_open'] == '01.30 pm' ? 'selected' : '' }}>
                                                01:30 pm</option>
                                            <option value="02 pm"
                                                {{ $listingSchedules['wed_open'] == '02 pm' ? 'selected' : '' }}>
                                                02 pm</option>
                                            <option value="02.30 pm"
                                                {{ $listingSchedules['wed_open'] == '02.30 pm' ? 'selected' : '' }}>
                                                02:30 pm</option>
                                            <option value="03 pm"
                                                {{ $listingSchedules['wed_open'] == '03 pm' ? 'selected' : '' }}>
                                                03 pm</option>
                                            <option value="03.30 pm"
                                                {{ $listingSchedules['wed_open'] == '03.30 pm' ? 'selected' : '' }}>
                                                03:30 pm</option>
                                            <option value="04 pm"
                                                {{ $listingSchedules['wed_open'] == '04 pm' ? 'selected' : '' }}>
                                                04 pm</option>
                                            <option value="04.30 pm"
                                                {{ $listingSchedules['wed_open'] == '04.30 pm' ? 'selected' : '' }}>
                                                04:30 pm</option>
                                            <option value="05 pm"
                                                {{ $listingSchedules['wed_open'] == '05 pm' ? 'selected' : '' }}>
                                                05 pm</option>
                                            <option value="05.30 pm"
                                                {{ $listingSchedules['wed_open'] == '05.30 pm' ? 'selected' : '' }}>
                                                05:30 pm</option>
                                            <option value="06 pm"
                                                {{ $listingSchedules['wed_open'] == '06 pm' ? 'selected' : '' }}>
                                                06 pm</option>
                                            <option value="06.30 pm"
                                                {{ $listingSchedules['wed_open'] == '06.30 pm' ? 'selected' : '' }}>
                                                06:30 pm</option>
                                            <option value="07 pm"
                                                {{ $listingSchedules['wed_open'] == '07 pm' ? 'selected' : '' }}>
                                                07 pm</option>
                                            <option value="07.30 pm"
                                                {{ $listingSchedules['wed_open'] == '07.30 pm' ? 'selected' : '' }}>
                                                07:30 pm</option>
                                            <option value="08 pm"
                                                {{ $listingSchedules['wed_open'] == '08 pm' ? 'selected' : '' }}>
                                                08 pm</option>
                                            <option value="08.30 pm"
                                                {{ $listingSchedules['wed_open'] == '08.30 pm' ? 'selected' : '' }}>
                                                08:30 pm</option>
                                            <option value="09 pm"
                                                {{ $listingSchedules['wed_open'] == '09 pm' ? 'selected' : '' }}>
                                                09 pm</option>
                                            <option value="09.30 pm"
                                                {{ $listingSchedules['wed_open'] == '09.30 pm' ? 'selected' : '' }}>
                                                09:30 pm</option>
                                            <option value="10 pm"
                                                {{ $listingSchedules['wed_open'] == '10 pm' ? 'selected' : '' }}>
                                                10 pm</option>
                                            <option value="10.30 pm"
                                                {{ $listingSchedules['wed_open'] == '10.30 pm' ? 'selected' : '' }}>
                                                10:30 pm</option>
                                            <option value="11 pm"
                                                {{ $listingSchedules['wed_open'] == '11 pm' ? 'selected' : '' }}>
                                                11 pm</option>
                                            <option value="11.30 pm"
                                                {{ $listingSchedules['wed_open'] == '11.30 pm' ? 'selected' : '' }}>
                                                11:30 pm</option>
                                            <option value="closed"
                                                {{ $listingSchedules['wed_open'] == 'closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="wednesday_closing" id="wednesday_closing">
                                            <option value="" class="d-none">{{ __('Closing Time') }}</option>
                                            <option value="24 hours"
                                                {{ $listingSchedules['wed_close'] == '24 hours' ? 'selected' : '' }}>24
                                                hours</option>
                                            <option value="12 am"
                                                {{ $listingSchedules['wed_close'] == '12 am' ? 'selected' : '' }}>12 am
                                            </option>
                                            <option value="12.30 am"
                                                {{ $listingSchedules['wed_close'] == '12.30 am' ? 'selected' : '' }}>12:30
                                                am</option>
                                            <option value="01 am"
                                                {{ $listingSchedules['wed_close'] == '01 am' ? 'selected' : '' }}>01 am
                                            </option>
                                            <option value="01.30 am"
                                                {{ $listingSchedules['wed_close'] == '01.30 am' ? 'selected' : '' }}>01:30
                                                am</option>
                                            <option value="02 am"
                                                {{ $listingSchedules['wed_close'] == '02 am' ? 'selected' : '' }}>02 am
                                            </option>
                                            <option value="02.30 am"
                                                {{ $listingSchedules['wed_close'] == '02.30 am' ? 'selected' : '' }}>02:30
                                                am</option>
                                            <option value="03 am"
                                                {{ $listingSchedules['wed_close'] == '03 am' ? 'selected' : '' }}>03 am
                                            </option>
                                            <option value="03.30 am"
                                                {{ $listingSchedules['wed_close'] == '03.30 am' ? 'selected' : '' }}>03:30
                                                am</option>
                                            <option value="04 am"
                                                {{ $listingSchedules['wed_close'] == '04 am' ? 'selected' : '' }}>04 am
                                            </option>
                                            <option value="04.30 am"
                                                {{ $listingSchedules['wed_close'] == '04.30 am' ? 'selected' : '' }}>04:30
                                                am</option>
                                            <option value="05 am"
                                                {{ $listingSchedules['wed_close'] == '05 am' ? 'selected' : '' }}>05 am
                                            </option>
                                            <option value="05.30 am"
                                                {{ $listingSchedules['wed_close'] == '05.30 am' ? 'selected' : '' }}>05:30
                                                am</option>
                                            <option value="06 am"
                                                {{ $listingSchedules['wed_close'] == '06 am' ? 'selected' : '' }}>06 am
                                            </option>
                                            <option value="06.30 am"
                                                {{ $listingSchedules['wed_close'] == '06.30 am' ? 'selected' : '' }}>06:30
                                                am</option>
                                            <option value="07 am"
                                                {{ $listingSchedules['wed_close'] == '07 am' ? 'selected' : '' }}>07 am
                                            </option>
                                            <option value="07.30 am"
                                                {{ $listingSchedules['wed_close'] == '07.30 am' ? 'selected' : '' }}>07:30
                                                am</option>
                                            <option value="08 am"
                                                {{ $listingSchedules['wed_close'] == '08 am' ? 'selected' : '' }}>08 am
                                            </option>
                                            <option value="08.30 am"
                                                {{ $listingSchedules['wed_close'] == '08.30 am' ? 'selected' : '' }}>08:30
                                                am</option>
                                            <option value="09 am"
                                                {{ $listingSchedules['wed_close'] == '09 am' ? 'selected' : '' }}>09 am
                                            </option>
                                            <option value="09.30 am"
                                                {{ $listingSchedules['wed_close'] == '09.30 am' ? 'selected' : '' }}>09:30
                                                am</option>
                                            <option value="10 am"
                                                {{ $listingSchedules['wed_close'] == '10 am' ? 'selected' : '' }}>10 am
                                            </option>
                                            <option value="10.30 am"
                                                {{ $listingSchedules['wed_close'] == '10.30 am' ? 'selected' : '' }}>10:30
                                                am</option>
                                            <option value="11 am"
                                                {{ $listingSchedules['wed_close'] == '11 am' ? 'selected' : '' }}>11 am
                                            </option>
                                            <option value="11.30 am"
                                                {{ $listingSchedules['wed_close'] == '11.30 am' ? 'selected' : '' }}>11:30
                                                am</option>
                                            <option value="12 pm"
                                                {{ $listingSchedules['wed_close'] == '12 pm' ? 'selected' : '' }}>12 pm
                                            </option>
                                            <option value="12.30 pm"
                                                {{ $listingSchedules['wed_close'] == '12.30 pm' ? 'selected' : '' }}>12:30
                                                pm</option>
                                            <option value="01 pm"
                                                {{ $listingSchedules['wed_close'] == '01 pm' ? 'selected' : '' }}>01 pm
                                            </option>
                                            <option value="01.30 pm"
                                                {{ $listingSchedules['wed_close'] == '01.30 pm' ? 'selected' : '' }}>01:30
                                                pm</option>
                                            <option value="02 pm"
                                                {{ $listingSchedules['wed_close'] == '02 pm' ? 'selected' : '' }}>02 pm
                                            </option>
                                            <option value="02.30 pm"
                                                {{ $listingSchedules['wed_close'] == '02.30 pm' ? 'selected' : '' }}>02:30
                                                pm</option>
                                            <option value="03 pm"
                                                {{ $listingSchedules['wed_close'] == '03 pm' ? 'selected' : '' }}>03 pm
                                            </option>
                                            <option value="03.30 pm"
                                                {{ $listingSchedules['wed_close'] == '03.30 pm' ? 'selected' : '' }}>03:30
                                                pm</option>
                                            <option value="04 pm"
                                                {{ $listingSchedules['wed_close'] == '04 pm' ? 'selected' : '' }}>04 pm
                                            </option>
                                            <option value="04.30 pm"
                                                {{ $listingSchedules['wed_close'] == '04.30 pm' ? 'selected' : '' }}>04:30
                                                pm</option>
                                            <option value="05 pm"
                                                {{ $listingSchedules['wed_close'] == '05 pm' ? 'selected' : '' }}>05 pm
                                            </option>
                                            <option value="05.30 pm"
                                                {{ $listingSchedules['wed_close'] == '05.30 pm' ? 'selected' : '' }}>05:30
                                                pm</option>
                                            <option value="06 pm"
                                                {{ $listingSchedules['wed_close'] == '06 pm' ? 'selected' : '' }}>06 pm
                                            </option>
                                            <option value="06.30 pm"
                                                {{ $listingSchedules['wed_close'] == '06.30 pm' ? 'selected' : '' }}>06:30
                                                pm</option>
                                            <option value="07 pm"
                                                {{ $listingSchedules['wed_close'] == '07 pm' ? 'selected' : '' }}>07 pm
                                            </option>
                                            <option value="07.30 pm"
                                                {{ $listingSchedules['wed_close'] == '07.30 pm' ? 'selected' : '' }}>07:30
                                                pm</option>
                                            <option value="08 pm"
                                                {{ $listingSchedules['wed_close'] == '08 pm' ? 'selected' : '' }}>08 pm
                                            </option>
                                            <option value="08.30 pm"
                                                {{ $listingSchedules['wed_close'] == '08.30 pm' ? 'selected' : '' }}>08:30
                                                pm</option>
                                            <option value="09 pm"
                                                {{ $listingSchedules['wed_close'] == '09 pm' ? 'selected' : '' }}>09 pm
                                            </option>
                                            <option value="09.30 pm"
                                                {{ $listingSchedules['wed_close'] == '09.30 pm' ? 'selected' : '' }}>09:30
                                                pm</option>
                                            <option value="10 pm"
                                                {{ $listingSchedules['wed_close'] == '10 pm' ? 'selected' : '' }}>10 pm
                                            </option>
                                            <option value="10.30 pm"
                                                {{ $listingSchedules['wed_close'] == '10.30 pm' ? 'selected' : '' }}>10:30
                                                pm</option>
                                            <option value="11 pm"
                                                {{ $listingSchedules['wed_close'] == '11 pm' ? 'selected' : '' }}>11 pm
                                            </option>
                                            <option value="11.30 pm"
                                                {{ $listingSchedules['wed_close'] == '11.30 pm' ? 'selected' : '' }}>11:30
                                                pm</option>
                                            <option value="closed"
                                                {{ $listingSchedules['wed_close'] == 'closed' ? 'selected' : '' }}>Closed
                                            </option>
                                        </select>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Thursday') }}</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="thursday_opening" id="thursday_opening">
                                            <option value="" class="d-none">{{ __('Opening Time') }}</option>
                                            <option value="24 hours" {{ $listingSchedules['thu_open'] == '24 hours' ? 'selected' : '' }}>24 hours</option>
                                            <option value="12 am" {{ $listingSchedules['thu_open'] == '12 am' ? 'selected' : '' }}>12 am</option>
                                            <option value="12.30 am" {{ $listingSchedules['thu_open'] == '12.30 am' ? 'selected' : '' }}>12:30 am</option>
                                            <option value="01 am" {{ $listingSchedules['thu_open'] == '01 am' ? 'selected' : '' }}>01 am</option>
                                            <option value="01.30 am" {{ $listingSchedules['thu_open'] == '01.30 am' ? 'selected' : '' }}>01:30 am</option>
                                            <option value="02 am" {{ $listingSchedules['thu_open'] == '02 am' ? 'selected' : '' }}>02 am</option>
                                            <option value="02.30 am" {{ $listingSchedules['thu_open'] == '02.30 am' ? 'selected' : '' }}>02:30 am</option>
                                            <option value="03 am" {{ $listingSchedules['thu_open'] == '03 am' ? 'selected' : '' }}>03 am</option>
                                            <option value="03.30 am" {{ $listingSchedules['thu_open'] == '03.30 am' ? 'selected' : '' }}>03:30 am</option>
                                            <option value="04 am" {{ $listingSchedules['thu_open'] == '04 am' ? 'selected' : '' }}>04 am</option>
                                            <option value="04.30 am" {{ $listingSchedules['thu_open'] == '04.30 am' ? 'selected' : '' }}>04:30 am</option>
                                            <option value="05 am" {{ $listingSchedules['thu_open'] == '05 am' ? 'selected' : '' }}>05 am</option>
                                            <option value="05.30 am" {{ $listingSchedules['thu_open'] == '05.30 am' ? 'selected' : '' }}>05:30 am</option>
                                            <option value="06 am" {{ $listingSchedules['thu_open'] == '06 am' ? 'selected' : '' }}>06 am</option>
                                            <option value="06.30 am" {{ $listingSchedules['thu_open'] == '06.30 am' ? 'selected' : '' }}>06:30 am</option>
                                            <option value="07 am" {{ $listingSchedules['thu_open'] == '07 am' ? 'selected' : '' }}>07 am</option>
                                            <option value="07.30 am" {{ $listingSchedules['thu_open'] == '07.30 am' ? 'selected' : '' }}>07:30 am</option>
                                            <option value="08 am" {{ $listingSchedules['thu_open'] == '08 am' ? 'selected' : '' }}>08 am</option>
                                            <option value="08.30 am" {{ $listingSchedules['thu_open'] == '08.30 am' ? 'selected' : '' }}>08:30 am</option>
                                            <option value="09 am" {{ $listingSchedules['thu_open'] == '09 am' ? 'selected' : '' }}>09 am</option>
                                            <option value="09.30 am" {{ $listingSchedules['thu_open'] == '09.30 am' ? 'selected' : '' }}>09:30 am</option>
                                            <option value="10 am" {{ $listingSchedules['thu_open'] == '10 am' ? 'selected' : '' }}>10 am</option>
                                            <option value="10.30 am" {{ $listingSchedules['thu_open'] == '10.30 am' ? 'selected' : '' }}>10:30 am</option>
                                            <option value="11 am" {{ $listingSchedules['thu_open'] == '11 am' ? 'selected' : '' }}>11 am</option>
                                            <option value="11.30 am" {{ $listingSchedules['thu_open'] == '11.30 am' ? 'selected' : '' }}>11:30 am</option>
                                            <option value="12 pm" {{ $listingSchedules['thu_open'] == '12 pm' ? 'selected' : '' }}>12 pm</option>
                                            <option value="12.30 pm" {{ $listingSchedules['thu_open'] == '12.30 pm' ? 'selected' : '' }}>12:30 pm</option>
                                            <option value="01 pm" {{ $listingSchedules['thu_open'] == '01 pm' ? 'selected' : '' }}>01 pm</option>
                                            <option value="01.30 pm" {{ $listingSchedules['thu_open'] == '01.30 pm' ? 'selected' : '' }}>01:30 pm</option>
                                            <option value="02 pm" {{ $listingSchedules['thu_open'] == '02 pm' ? 'selected' : '' }}>02 pm</option>
                                            <option value="02.30 pm" {{ $listingSchedules['thu_open'] == '02.30 pm' ? 'selected' : '' }}>02:30 pm</option>
                                            <option value="03 pm" {{ $listingSchedules['thu_open'] == '03 pm' ? 'selected' : '' }}>03 pm</option>
                                            <option value="03.30 pm" {{ $listingSchedules['thu_open'] == '03.30 pm' ? 'selected' : '' }}>03:30 pm</option>
                                            <option value="04 pm" {{ $listingSchedules['thu_open'] == '04 pm' ? 'selected' : '' }}>04 pm</option>
                                            <option value="04.30 pm" {{ $listingSchedules['thu_open'] == '04.30 pm' ? 'selected' : '' }}>04:30 pm</option>
                                            <option value="05 pm" {{ $listingSchedules['thu_open'] == '05 pm' ? 'selected' : '' }}>05 pm</option>
                                            <option value="05.30 pm" {{ $listingSchedules['thu_open'] == '05.30 pm' ? 'selected' : '' }}>05:30 pm</option>
                                            <option value="06 pm" {{ $listingSchedules['thu_open'] == '06 pm' ? 'selected' : '' }}>06 pm</option>
                                            <option value="06.30 pm" {{ $listingSchedules['thu_open'] == '06.30 pm' ? 'selected' : '' }}>06:30 pm</option>
                                            <option value="07 pm" {{ $listingSchedules['thu_open'] == '07 pm' ? 'selected' : '' }}>07 pm</option>
                                            <option value="07.30 pm" {{ $listingSchedules['thu_open'] == '07.30 pm' ? 'selected' : '' }}>07:30 pm</option>
                                            <option value="08 pm" {{ $listingSchedules['thu_open'] == '08 pm' ? 'selected' : '' }}>08 pm</option>
                                            <option value="08.30 pm" {{ $listingSchedules['thu_open'] == '08.30 pm' ? 'selected' : '' }}>08:30 pm</option>
                                            <option value="09 pm" {{ $listingSchedules['thu_open'] == '09 pm' ? 'selected' : '' }}>09 pm</option>
                                            <option value="09.30 pm" {{ $listingSchedules['thu_open'] == '09.30 pm' ? 'selected' : '' }}>09:30 pm</option>
                                            <option value="10 pm" {{ $listingSchedules['thu_open'] == '10 pm' ? 'selected' : '' }}>10 pm</option>
                                            <option value="10.30 pm" {{ $listingSchedules['thu_open'] == '10.30 pm' ? 'selected' : '' }}>10:30 pm</option>
                                            <option value="11 pm" {{ $listingSchedules['thu_open'] == '11 pm' ? 'selected' : '' }}>11 pm</option>
                                            <option value="11.30 pm" {{ $listingSchedules['thu_open'] == '11.30 pm' ? 'selected' : '' }}>11:30 pm</option>
                                            <option value="closed" {{ $listingSchedules['thu_open'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                        
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="thursday_closing" id="thursday_closing">
                                            <option value="" class="d-none">{{ __('Closing Time') }}</option>
                                            <option value="24 hours" {{ $listingSchedules['thu_close'] == '24 hours' ? 'selected' : '' }}>24 hours</option>
                                            <option value="12 am" {{ $listingSchedules['thu_close'] == '12 am' ? 'selected' : '' }}>12 am</option>
                                            <option value="12.30 am" {{ $listingSchedules['thu_close'] == '12.30 am' ? 'selected' : '' }}>12:30 am</option>
                                            <option value="01 am" {{ $listingSchedules['thu_close'] == '01 am' ? 'selected' : '' }}>01 am</option>
                                            <option value="01.30 am" {{ $listingSchedules['thu_close'] == '01.30 am' ? 'selected' : '' }}>01:30 am</option>
                                            <option value="02 am" {{ $listingSchedules['thu_close'] == '02 am' ? 'selected' : '' }}>02 am</option>
                                            <option value="02.30 am" {{ $listingSchedules['thu_close'] == '02.30 am' ? 'selected' : '' }}>02:30 am</option>
                                            <option value="03 am" {{ $listingSchedules['thu_close'] == '03 am' ? 'selected' : '' }}>03 am</option>
                                            <option value="03.30 am" {{ $listingSchedules['thu_close'] == '03.30 am' ? 'selected' : '' }}>03:30 am</option>
                                            <option value="04 am" {{ $listingSchedules['thu_close'] == '04 am' ? 'selected' : '' }}>04 am</option>
                                            <option value="04.30 am" {{ $listingSchedules['thu_close'] == '04.30 am' ? 'selected' : '' }}>04:30 am</option>
                                            <option value="05 am" {{ $listingSchedules['thu_close'] == '05 am' ? 'selected' : '' }}>05 am</option>
                                            <option value="05.30 am" {{ $listingSchedules['thu_close'] == '05.30 am' ? 'selected' : '' }}>05:30 am</option>
                                            <option value="06 am" {{ $listingSchedules['thu_close'] == '06 am' ? 'selected' : '' }}>06 am</option>
                                            <option value="06.30 am" {{ $listingSchedules['thu_close'] == '06.30 am' ? 'selected' : '' }}>06:30 am</option>
                                            <option value="07 am" {{ $listingSchedules['thu_close'] == '07 am' ? 'selected' : '' }}>07 am</option>
                                            <option value="07.30 am" {{ $listingSchedules['thu_close'] == '07.30 am' ? 'selected' : '' }}>07:30 am</option>
                                            <option value="08 am" {{ $listingSchedules['thu_close'] == '08 am' ? 'selected' : '' }}>08 am</option>
                                            <option value="08.30 am" {{ $listingSchedules['thu_close'] == '08.30 am' ? 'selected' : '' }}>08:30 am</option>
                                            <option value="09 am" {{ $listingSchedules['thu_close'] == '09 am' ? 'selected' : '' }}>09 am</option>
                                            <option value="09.30 am" {{ $listingSchedules['thu_close'] == '09.30 am' ? 'selected' : '' }}>09:30 am</option>
                                            <option value="10 am" {{ $listingSchedules['thu_close'] == '10 am' ? 'selected' : '' }}>10 am</option>
                                            <option value="10.30 am" {{ $listingSchedules['thu_close'] == '10.30 am' ? 'selected' : '' }}>10:30 am</option>
                                            <option value="11 am" {{ $listingSchedules['thu_close'] == '11 am' ? 'selected' : '' }}>11 am</option>
                                            <option value="11.30 am" {{ $listingSchedules['thu_close'] == '11.30 am' ? 'selected' : '' }}>11:30 am</option>
                                            <option value="12 pm" {{ $listingSchedules['thu_close'] == '12 pm' ? 'selected' : '' }}>12 pm</option>
                                            <option value="12.30 pm" {{ $listingSchedules['thu_close'] == '12.30 pm' ? 'selected' : '' }}>12:30 pm</option>
                                            <option value="01 pm" {{ $listingSchedules['thu_close'] == '01 pm' ? 'selected' : '' }}>01 pm</option>
                                            <option value="01.30 pm" {{ $listingSchedules['thu_close'] == '01.30 pm' ? 'selected' : '' }}>01:30 pm</option>
                                            <option value="02 pm" {{ $listingSchedules['thu_close'] == '02 pm' ? 'selected' : '' }}>02 pm</option>
                                            <option value="02.30 pm" {{ $listingSchedules['thu_close'] == '02.30 pm' ? 'selected' : '' }}>02:30 pm</option>
                                            <option value="03 pm" {{ $listingSchedules['thu_close'] == '03 pm' ? 'selected' : '' }}>03 pm</option>
                                            <option value="03.30 pm" {{ $listingSchedules['thu_close'] == '03.30 pm' ? 'selected' : '' }}>03:30 pm</option>
                                            <option value="04 pm" {{ $listingSchedules['thu_close'] == '04 pm' ? 'selected' : '' }}>04 pm</option>
                                            <option value="04.30 pm" {{ $listingSchedules['thu_close'] == '04.30 pm' ? 'selected' : '' }}>04:30 pm</option>
                                            <option value="05 pm" {{ $listingSchedules['thu_close'] == '05 pm' ? 'selected' : '' }}>05 pm</option>
                                            <option value="05.30 pm" {{ $listingSchedules['thu_close'] == '05.30 pm' ? 'selected' : '' }}>05:30 pm</option>
                                            <option value="06 pm" {{ $listingSchedules['thu_close'] == '06 pm' ? 'selected' : '' }}>06 pm</option>
                                            <option value="06.30 pm" {{ $listingSchedules['thu_close'] == '06.30 pm' ? 'selected' : '' }}>06:30 pm</option>
                                            <option value="07 pm" {{ $listingSchedules['thu_close'] == '07 pm' ? 'selected' : '' }}>07 pm</option>
                                            <option value="07.30 pm" {{ $listingSchedules['thu_close'] == '07.30 pm' ? 'selected' : '' }}>07:30 pm</option>
                                            <option value="08 pm" {{ $listingSchedules['thu_close'] == '08 pm' ? 'selected' : '' }}>08 pm</option>
                                            <option value="08.30 pm" {{ $listingSchedules['thu_close'] == '08.30 pm' ? 'selected' : '' }}>08:30 pm</option>
                                            <option value="09 pm" {{ $listingSchedules['thu_close'] == '09 pm' ? 'selected' : '' }}>09 pm</option>
                                            <option value="09.30 pm" {{ $listingSchedules['thu_close'] == '09.30 pm' ? 'selected' : '' }}>09:30 pm</option>
                                            <option value="10 pm" {{ $listingSchedules['thu_close'] == '10 pm' ? 'selected' : '' }}>10 pm</option>
                                            <option value="10.30 pm" {{ $listingSchedules['thu_close'] == '10.30 pm' ? 'selected' : '' }}>10:30 pm</option>
                                            <option value="11 pm" {{ $listingSchedules['thu_close'] == '11 pm' ? 'selected' : '' }}>11 pm</option>
                                            <option value="11.30 pm" {{ $listingSchedules['thu_close'] == '11.30 pm' ? 'selected' : '' }}>11:30 pm</option>
                                            <option value="closed" {{ $listingSchedules['thu_close'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Friday') }}</label>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="friday_opening" id="friday_opening">
                                            <option value="" class="d-none">{{ __('Opening Time') }}</option>
                                            <option value="24 hours" {{ $listingSchedules['fri_open'] == '24 hours' ? 'selected' : '' }}>24 hours</option>
                                            <option value="12 am" {{ $listingSchedules['fri_open'] == '12 am' ? 'selected' : '' }}>12 am</option>
                                            <option value="12.30 am" {{ $listingSchedules['fri_open'] == '12.30 am' ? 'selected' : '' }}>12:30 am</option>
                                            <option value="01 am" {{ $listingSchedules['fri_open'] == '01 am' ? 'selected' : '' }}>01 am</option>
                                            <option value="01.30 am" {{ $listingSchedules['fri_open'] == '01.30 am' ? 'selected' : '' }}>01:30 am</option>
                                            <option value="02 am" {{ $listingSchedules['fri_open'] == '02 am' ? 'selected' : '' }}>02 am</option>
                                            <option value="02.30 am" {{ $listingSchedules['fri_open'] == '02.30 am' ? 'selected' : '' }}>02:30 am</option>
                                            <option value="03 am" {{ $listingSchedules['fri_open'] == '03 am' ? 'selected' : '' }}>03 am</option>
                                            <option value="03.30 am" {{ $listingSchedules['fri_open'] == '03.30 am' ? 'selected' : '' }}>03:30 am</option>
                                            <option value="04 am" {{ $listingSchedules['fri_open'] == '04 am' ? 'selected' : '' }}>04 am</option>
                                            <option value="04.30 am" {{ $listingSchedules['fri_open'] == '04.30 am' ? 'selected' : '' }}>04:30 am</option>
                                            <option value="05 am" {{ $listingSchedules['fri_open'] == '05 am' ? 'selected' : '' }}>05 am</option>
                                            <option value="05.30 am" {{ $listingSchedules['fri_open'] == '05.30 am' ? 'selected' : '' }}>05:30 am</option>
                                            <option value="06 am" {{ $listingSchedules['fri_open'] == '06 am' ? 'selected' : '' }}>06 am</option>
                                            <option value="06.30 am" {{ $listingSchedules['fri_open'] == '06.30 am' ? 'selected' : '' }}>06:30 am</option>
                                            <option value="07 am" {{ $listingSchedules['fri_open'] == '07 am' ? 'selected' : '' }}>07 am</option>
                                            <option value="07.30 am" {{ $listingSchedules['fri_open'] == '07.30 am' ? 'selected' : '' }}>07:30 am</option>
                                            <option value="08 am" {{ $listingSchedules['fri_open'] == '08 am' ? 'selected' : '' }}>08 am</option>
                                            <option value="08.30 am" {{ $listingSchedules['fri_open'] == '08.30 am' ? 'selected' : '' }}>08:30 am</option>
                                            <option value="09 am" {{ $listingSchedules['fri_open'] == '09 am' ? 'selected' : '' }}>09 am</option>
                                            <option value="09.30 am" {{ $listingSchedules['fri_open'] == '09.30 am' ? 'selected' : '' }}>09:30 am</option>
                                            <option value="10 am" {{ $listingSchedules['fri_open'] == '10 am' ? 'selected' : '' }}>10 am</option>
                                            <option value="10.30 am" {{ $listingSchedules['fri_open'] == '10.30 am' ? 'selected' : '' }}>10:30 am</option>
                                            <option value="11 am" {{ $listingSchedules['fri_open'] == '11 am' ? 'selected' : '' }}>11 am</option>
                                            <option value="11.30 am" {{ $listingSchedules['fri_open'] == '11.30 am' ? 'selected' : '' }}>11:30 am</option>
                                            <option value="12 pm" {{ $listingSchedules['fri_open'] == '12 pm' ? 'selected' : '' }}>12 pm</option>
                                            <option value="12.30 pm" {{ $listingSchedules['fri_open'] == '12.30 pm' ? 'selected' : '' }}>12:30 pm</option>
                                            <option value="01 pm" {{ $listingSchedules['fri_open'] == '01 pm' ? 'selected' : '' }}>01 pm</option>
                                            <option value="01.30 pm" {{ $listingSchedules['fri_open'] == '01.30 pm' ? 'selected' : '' }}>01:30 pm</option>
                                            <option value="02 pm" {{ $listingSchedules['fri_open'] == '02 pm' ? 'selected' : '' }}>02 pm</option>
                                            <option value="02.30 pm" {{ $listingSchedules['fri_open'] == '02.30 pm' ? 'selected' : '' }}>02:30 pm</option>
                                            <option value="03 pm" {{ $listingSchedules['fri_open'] == '03 pm' ? 'selected' : '' }}>03 pm</option>
                                            <option value="03.30 pm" {{ $listingSchedules['fri_open'] == '03.30 pm' ? 'selected' : '' }}>03:30 pm</option>
                                            <option value="04 pm" {{ $listingSchedules['fri_open'] == '04 pm' ? 'selected' : '' }}>04 pm</option>
                                            <option value="04.30 pm" {{ $listingSchedules['fri_open'] == '04.30 pm' ? 'selected' : '' }}>04:30 pm</option>
                                            <option value="05 pm" {{ $listingSchedules['fri_open'] == '05 pm' ? 'selected' : '' }}>05 pm</option>
                                            <option value="05.30 pm" {{ $listingSchedules['fri_open'] == '05.30 pm' ? 'selected' : '' }}>05:30 pm</option>
                                            <option value="06 pm" {{ $listingSchedules['fri_open'] == '06 pm' ? 'selected' : '' }}>06 pm</option>
                                            <option value="06.30 pm" {{ $listingSchedules['fri_open'] == '06.30 pm' ? 'selected' : '' }}>06:30 pm</option>
                                            <option value="07 pm" {{ $listingSchedules['fri_open'] == '07 pm' ? 'selected' : '' }}>07 pm</option>
                                            <option value="07.30 pm" {{ $listingSchedules['fri_open'] == '07.30 pm' ? 'selected' : '' }}>07:30 pm</option>
                                            <option value="08 pm" {{ $listingSchedules['fri_open'] == '08 pm' ? 'selected' : '' }}>08 pm</option>
                                            <option value="08.30 pm" {{ $listingSchedules['fri_open'] == '08.30 pm' ? 'selected' : '' }}>08:30 pm</option>
                                            <option value="09 pm" {{ $listingSchedules['fri_open'] == '09 pm' ? 'selected' : '' }}>09 pm</option>
                                            <option value="09.30 pm" {{ $listingSchedules['fri_open'] == '09.30 pm' ? 'selected' : '' }}>09:30 pm</option>
                                            <option value="10 pm" {{ $listingSchedules['fri_open'] == '10 pm' ? 'selected' : '' }}>10 pm</option>
                                            <option value="10.30 pm" {{ $listingSchedules['fri_open'] == '10.30 pm' ? 'selected' : '' }}>10:30 pm</option>
                                            <option value="11 pm" {{ $listingSchedules['fri_open'] == '11 pm' ? 'selected' : '' }}>11 pm</option>
                                            <option value="11.30 pm" {{ $listingSchedules['fri_open'] == '11.30 pm' ? 'selected' : '' }}>11:30 pm</option>
                                            <option value="closed" {{ $listingSchedules['fri_open'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                        
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="input-field" name="friday_closing" id="friday_closing">
                                            <option value="" class="d-none">{{ __('Closing Time') }}</option>
                                            <option value="24 hours" {{ $listingSchedules['fri_close'] == '24 hours' ? 'selected' : '' }}>24 hours</option>
                                            <option value="12 am" {{ $listingSchedules['fri_close'] == '12 am' ? 'selected' : '' }}>12 am</option>
                                            <option value="12.30 am" {{ $listingSchedules['fri_close'] == '12.30 am' ? 'selected' : '' }}>12:30 am</option>
                                            <option value="01 am" {{ $listingSchedules['fri_close'] == '01 am' ? 'selected' : '' }}>01 am</option>
                                            <option value="01.30 am" {{ $listingSchedules['fri_close'] == '01.30 am' ? 'selected' : '' }}>01:30 am</option>
                                            <option value="02 am" {{ $listingSchedules['fri_close'] == '02 am' ? 'selected' : '' }}>02 am</option>
                                            <option value="02.30 am" {{ $listingSchedules['fri_close'] == '02.30 am' ? 'selected' : '' }}>02:30 am</option>
                                            <option value="03 am" {{ $listingSchedules['fri_close'] == '03 am' ? 'selected' : '' }}>03 am</option>
                                            <option value="03.30 am" {{ $listingSchedules['fri_close'] == '03.30 am' ? 'selected' : '' }}>03:30 am</option>
                                            <option value="04 am" {{ $listingSchedules['fri_close'] == '04 am' ? 'selected' : '' }}>04 am</option>
                                            <option value="04.30 am" {{ $listingSchedules['fri_close'] == '04.30 am' ? 'selected' : '' }}>04:30 am</option>
                                            <option value="05 am" {{ $listingSchedules['fri_close'] == '05 am' ? 'selected' : '' }}>05 am</option>
                                            <option value="05.30 am" {{ $listingSchedules['fri_close'] == '05.30 am' ? 'selected' : '' }}>05:30 am</option>
                                            <option value="06 am" {{ $listingSchedules['fri_close'] == '06 am' ? 'selected' : '' }}>06 am</option>
                                            <option value="06.30 am" {{ $listingSchedules['fri_close'] == '06.30 am' ? 'selected' : '' }}>06:30 am</option>
                                            <option value="07 am" {{ $listingSchedules['fri_close'] == '07 am' ? 'selected' : '' }}>07 am</option>
                                            <option value="07.30 am" {{ $listingSchedules['fri_close'] == '07.30 am' ? 'selected' : '' }}>07:30 am</option>
                                            <option value="08 am" {{ $listingSchedules['fri_close'] == '08 am' ? 'selected' : '' }}>08 am</option>
                                            <option value="08.30 am" {{ $listingSchedules['fri_close'] == '08.30 am' ? 'selected' : '' }}>08:30 am</option>
                                            <option value="09 am" {{ $listingSchedules['fri_close'] == '09 am' ? 'selected' : '' }}>09 am</option>
                                            <option value="09.30 am" {{ $listingSchedules['fri_close'] == '09.30 am' ? 'selected' : '' }}>09:30 am</option>
                                            <option value="10 am" {{ $listingSchedules['fri_close'] == '10 am' ? 'selected' : '' }}>10 am</option>
                                            <option value="10.30 am" {{ $listingSchedules['fri_close'] == '10.30 am' ? 'selected' : '' }}>10:30 am</option>
                                            <option value="11 am" {{ $listingSchedules['fri_close'] == '11 am' ? 'selected' : '' }}>11 am</option>
                                            <option value="11.30 am" {{ $listingSchedules['fri_close'] == '11.30 am' ? 'selected' : '' }}>11:30 am</option>
                                            <option value="12 pm" {{ $listingSchedules['fri_close'] == '12 pm' ? 'selected' : '' }}>12 pm</option>
                                            <option value="12.30 pm" {{ $listingSchedules['fri_close'] == '12.30 pm' ? 'selected' : '' }}>12:30 pm</option>
                                            <option value="01 pm" {{ $listingSchedules['fri_close'] == '01 pm' ? 'selected' : '' }}>01 pm</option>
                                            <option value="01.30 pm" {{ $listingSchedules['fri_close'] == '01.30 pm' ? 'selected' : '' }}>01:30 pm</option>
                                            <option value="02 pm" {{ $listingSchedules['fri_close'] == '02 pm' ? 'selected' : '' }}>02 pm</option>
                                            <option value="02.30 pm" {{ $listingSchedules['fri_close'] == '02.30 pm' ? 'selected' : '' }}>02:30 pm</option>
                                            <option value="03 pm" {{ $listingSchedules['fri_close'] == '03 pm' ? 'selected' : '' }}>03 pm</option>
                                            <option value="03.30 pm" {{ $listingSchedules['fri_close'] == '03.30 pm' ? 'selected' : '' }}>03:30 pm</option>
                                            <option value="04 pm" {{ $listingSchedules['fri_close'] == '04 pm' ? 'selected' : '' }}>04 pm</option>
                                            <option value="04.30 pm" {{ $listingSchedules['fri_close'] == '04.30 pm' ? 'selected' : '' }}>04:30 pm</option>
                                            <option value="05 pm" {{ $listingSchedules['fri_close'] == '05 pm' ? 'selected' : '' }}>05 pm</option>
                                            <option value="05.30 pm" {{ $listingSchedules['fri_close'] == '05.30 pm' ? 'selected' : '' }}>05:30 pm</option>
                                            <option value="06 pm" {{ $listingSchedules['fri_close'] == '06 pm' ? 'selected' : '' }}>06 pm</option>
                                            <option value="06.30 pm" {{ $listingSchedules['fri_close'] == '06.30 pm' ? 'selected' : '' }}>06:30 pm</option>
                                            <option value="07 pm" {{ $listingSchedules['fri_close'] == '07 pm' ? 'selected' : '' }}>07 pm</option>
                                            <option value="07.30 pm" {{ $listingSchedules['fri_close'] == '07.30 pm' ? 'selected' : '' }}>07:30 pm</option>
                                            <option value="08 pm" {{ $listingSchedules['fri_close'] == '08 pm' ? 'selected' : '' }}>08 pm</option>
                                            <option value="08.30 pm" {{ $listingSchedules['fri_close'] == '08.30 pm' ? 'selected' : '' }}>08:30 pm</option>
                                            <option value="09 pm" {{ $listingSchedules['fri_close'] == '09 pm' ? 'selected' : '' }}>09 pm</option>
                                            <option value="09.30 pm" {{ $listingSchedules['fri_close'] == '09.30 pm' ? 'selected' : '' }}>09:30 pm</option>
                                            <option value="10 pm" {{ $listingSchedules['fri_close'] == '10 pm' ? 'selected' : '' }}>10 pm</option>
                                            <option value="10.30 pm" {{ $listingSchedules['fri_close'] == '10.30 pm' ? 'selected' : '' }}>10:30 pm</option>
                                            <option value="11 pm" {{ $listingSchedules['fri_close'] == '11 pm' ? 'selected' : '' }}>11 pm</option>
                                            <option value="11.30 pm" {{ $listingSchedules['fri_close'] == '11.30 pm' ? 'selected' : '' }}>11:30 pm</option>
                                            <option value="closed" {{ $listingSchedules['fri_close'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                        
                                    </div>
                                </div>
                            </div>
                            {{-- contact --}}
                            <div class="">
                                <h4 class="mb-3">Contact</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <label class="form-label">{{ __('Website') }}</label>
                                        <input type="text" class="input-field" id="inp-website" name="website"
                                            placeholder="{{ __('Website') }}" value="{{ $data->website }}">
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <label class="form-label">{{ __('Email') }}</label>
                                        <input type="text" class="input-field" name="email"
                                            placeholder="{{ __('Email') }}" value="{{ $data->email }}">
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <label class="form-label">{{ __('Phone number') }}</label>
                                        <input type="text" class="input-field" id="phone_number" name="phone_number"
                                            placeholder="{{ __('Phone number') }}" value="{{ $data->phone_number }}">
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <label class="form-label">{{ __('Whatsapp number') }}</label>
                                        <input type="text" class="input-field" id="whatsapp_number" name="whatsapp_number"
                                            placeholder="{{ __('Whatsapp number') }}" value="{{ $data->whatsapp_number }}">
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <label class="form-label">{{ __('Facebook') }}</label>
                                        <input type="text" class="input-field" name="facebook"
                                            placeholder="{{ __('Facebook') }}" value="{{ $data->facebook }}">
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <label class="form-label">{{ __('Twitter') }}</label>
                                        <input type="text" class="input-field" name="twitter"
                                            placeholder="{{ __('Twitter') }}" value="{{ $data->twitter }}">
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <label class="form-label">{{ __('Linkedin') }}</label>
                                        <input type="text" class="input-field" name="linkedin"
                                            placeholder="{{ __('Linkedin') }}" value="{{ $data->linkedin }}">
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <label class="form-label">{{ __('Youtube') }}</label>
                                        <input type="text" class="input-field" name="youtube"
                                            value="{{ $data->youtube }}" placeholder="{{ __('Youtube') }}">
                                    </div>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <label class="form-label">{{ __('Instagram') }}</label>
                                        <input type="text" class="input-field" name="instagram"
                                            value="{{ $data->instagram }}" placeholder="{{ __('Instagram') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Meta Keywords') }}</label>
                                        <input type="text" class="input-field" name="meta_keywords"
                                            value="{{ $data->meta_keywords }}"
                                            placeholder="{{ __('Meta Keywords') }}" id="keywords">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Meta Title') }} *</label>
                                        <input type="text" class="input-field" name="meta_title" id="meta_title"
                                            placeholder="{{ __('Meta Title') }}" value="{{ $data->name }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">{{ __('Meta Description') }}</label>
                                        <textarea name="meta_description" class="input-field mb-0" maxlength="160" id="meta_description"
                                            cols="30" rows="10" placeholder="{{ __('Meta Description') }}">{{ $data->meta_description }}</textarea>
                                        <span>You have write maximum <span id="characters-counter">160</span>
                                            characters</span>
                                    </div>
                                </div>
                            </div>

                            @if ($category == 'restaurants')
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="menu-section-area">
                                                @includeIf('partials.admin.listing.menu', [
                                                    'menus' => $menus,
                                                ])
                                            </div>

                                            <div class="row my-2">
                                                <div class="col-lg-12 text-center">
                                                    <a href="javascript:;" id="menud-add-btn" class="btn btn-info"><i
                                                            class="fa fa-plus"></i> {{ __('Add More') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($category == 'hotels')
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="room-section-area">
                                                @includeIf('partials.admin.listing.room', [
                                                    'rooms' => $rooms,
                                                ])
                                            </div>

                                            <div class="row my-2">
                                                <div class="col-lg-12 text-center">
                                                    <a href="javascript:;" id="room-add-btn" class="btn btn-info"><i
                                                            class="fa fa-plus"></i> {{ __('Add More') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endif
                            @if ($category == 'beauty-spa' || $category == 'universities' || $category == 'lawyers' || $category == 'ac-repair-services')
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="beauty-section-area">
                                                @includeIf('partials.admin.listing.beauty', ['beauties' => $beauties])
                                            </div>

                                            <div class="row my-2">
                                                <div class="col-lg-12 text-center">
                                                    <a href="javascript:;" id="beauty-add-btn" class="btn btn-info"><i
                                                            class="fa fa-plus"></i> {{ __('Add More') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endif

                            @if ($category == 'real-estate')
                                @includeIf('partials.admin.listing.real_estate')
                            @endif

                            @if ($category == 'car')
                                @includeIf('partials.admin.listing.car')
                            @endif

                            <div class="faq-section-area mt-2">
                                @includeIf('partials.admin.listing.faq')
                            </div>

                            <div class="row my-2">
                                <div class="col-lg-12 text-center">
                                    <a href="javascript:;" id="faq-add-btn" class="btn btn-info"><i
                                            class="fa fa-plus"></i> {{ __('Add Faq') }}</a>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="is_feature" class="form-check-input" value="1"
                                        id="is_feature" {{ $data->is_feature == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="is_feature">{{ __('Check if this directory is featured') }}</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="status" class="form-check-input" value="1"
                                        id="approve_status" {{ $data->status == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="approve_status">{{ __('Check to approve this listing') }}</label>
                                </div>
                            </div>
                            @if (!$hasGatewayPayment)
                                <div class="col-sm-6 col-xl-4 mt-3" id="offline_payment_period_wrap" style="display: none;">
                                    <label class="form-label">{{ __('Offline Payment Period') }}{{ $data->status != 1 ? ' *' : '' }}</label>
                                    <select name="offline_payment_period" id="offline_payment_period" class="input-field">
                                        <option value="">{{ $data->status == 1 ? __('Keep Current Period') : __('Select Payment Period') }}</option>
                                        <option value="monthly">{{ __('Monthly (30 days)') }}</option>
                                        <option value="yearly">{{ __('Yearly (12 months)') }}</option>
                                        <option value="lifetime">{{ __('Lifetime (no expiry)') }}</option>
                                    </select>
                                    <p class="sub-heading mb-0">
                                        @if ($data->plan_start_date || $data->plan_end_date)
                                            {{ __('Current period:') }}
                                            {{ $data->plan_start_date ? \Carbon\Carbon::parse($data->plan_start_date)->format('d M Y') : __('N/A') }}
                                            -
                                            {{ $data->plan_end_date ? \Carbon\Carbon::parse($data->plan_end_date)->format('d M Y') : __('Lifetime') }}
                                            <br>
                                        @endif
                                        {{ __('Select Monthly, Yearly or Lifetime to update this offline payment period.') }}
                                    </p>
                                    <label class="form-label mt-2">{{ __('Offline Payment Amount (TK)') }}</label>
                                    <input type="number" name="offline_payment_amount" id="offline_payment_amount"
                                        class="input-field" step="0.01" min="0"
                                        placeholder="{{ __('Amount actually paid (blank = plan price)') }}">
                                    <p class="sub-heading mb-0">
                                        {{ __('Recorded as an OFFLINE transaction on the Transactions page when you save the approval.') }}
                                    </p>
                                </div>
                            @elseif ($hasGatewayPayment)
                                <div class="col-12 mt-3">
                                    <div class="alert alert-info mb-0">
                                        {{ __('Payment gateway already set this listing payment period.') }}
                                        @if ($data->plan_start_date || $data->plan_end_date)
                                            {{ __('Current period:') }}
                                            {{ $data->plan_start_date ? \Carbon\Carbon::parse($data->plan_start_date)->format('d M Y') : __('N/A') }}
                                            -
                                            {{ $data->plan_end_date ? \Carbon\Carbon::parse($data->plan_end_date)->format('d M Y') : __('Lifetime') }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="row justify-content-center mt-3">
                                <button type="submit" id="submit-btn"
                                    class="btn btn-primary text-center">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade dynamic-modal" id="deleteModal" tabindex="-1" role="dialog"
        aria-labelledby="dynamicModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Confirm Delete') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">
                        {{ __('You are about to delete this Item. Every informtation under this item will be deleted.') }}
                    </p>
                    <p class="text-center">{{ __('Do you want to proceed?') }}</p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</a>
                    <a href="javascript:;" class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery"
        aria-modal="true">
        <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Image Gallery') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="top-area">
                        <div class="row text-center text-sm-left">
                            <div class="col-sm-6 text-sm-right">
                                <div class="upload-img-btn">
                                    <form method="POST" enctype="multipart/form-data" id="form-gallery">
                                        {{ csrf_field() }}
                                        <input type="hidden" id="listing_id" name="listing_id"
                                            value="{{ $data->id }}">
                                        <input type="file" name="gallery[]" class="hidden"
                                            id="property_upload_gallery_edit" accept="image/*" multiple>
                                        <label id="property_gallery_edit"><i
                                                class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <a href="javascript:;" class="upload-done" data-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-check"></i> {{ __('Done') }}</a>
                            </div>
                            <div class="col-sm-12 text-center">(
                                <small>{{ __('You can upload multiple Images.') }}</small> )
                            </div>
                        </div>
                    </div>
                    <div class="gallery-images">
                        <div class="selected-image">
                            <div class="row">


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/image_gallary.js') }}"></script>
    <script>
        // select 2
        $(document).ready(function() {
            $('.select2').select2();
        })
        $(document).ready(function() {
            var alreadyApproved = {{ $data->status == 1 ? 'true' : 'false' }};

            function toggleOfflinePaymentPeriod() {
                var wrapper = $('#offline_payment_period_wrap');
                var period = $('#offline_payment_period');

                if (!wrapper.length) {
                    return;
                }

                if ($('#approve_status').is(':checked')) {
                    wrapper.show();
                    period.prop('required', !alreadyApproved);
                } else {
                    wrapper.hide();
                    period.prop('required', false).val('');
                }
            }

            $('#approve_status').on('change', toggleOfflinePaymentPeriod);
            toggleOfflinePaymentPeriod();
        })
        // mata description 160 limit content
        $(document).ready(function() {
            var maxCharacters = 160;
            $('#meta_description').on('input', function() {
                $('#characters-counter').text(maxCharacters - $(this).val().length);
            });
        });

        // slug generate and unique check with ajax
        $(document).ready(function() {
            // Function to generate a slug from the title
            function generateSlug(title) {
                return title.trim().toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
            }
            $("#listingTitle").keyup(function() {
                var titleValue = $(this).val();
                var id = $('#listing_id').val();
                var slug = generateSlug(titleValue);
                $('#listingSlug').val(slug);
                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.listing.update.slug.check') }}/" + id,
                    data: {
                        slug: slug
                    },
                    beforeSend: function() {
                        if ($('#listingSlug').val() == '') {
                            $('#slugData').fadeOut();
                        } else {
                            $('#slugData').fadeIn();
                        }
                    },
                    success: function(response) {
                        if (response.unique) {
                            $('#slugData').html(
                                '<span class="text-success">This slug is available</span>');
                        } else {
                            $('#slugData').html(
                                '<span class="text-danger">This slug is already exists</span>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>

    <script>
        'use strict';
        @if (isset($menus))
            let menuId = '{{ count($menus) > 0 ? count($menus) : 1 }}';
            $('#menud-add-btn').on('click', function() {
                $('.menu-section-area').append('' +
                    `<div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('Menu Items') }}</h6>
                        <button class="border-0"><i class="fas fa-trash-alt text-danger"></i></button>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Menu Name') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="input-field" name="menu_title[]" placeholder="{{ __('Enter Name') }}" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Menu Price') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="number" class="input-field" id="menu_price" name="menu_price[]" placeholder="{{ __('Enter Price') }}" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Menu Photo') }}</h4>
                                    <p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="wrapper-image-preview">
                                    <div class="box">
                                        <div class="back-preview-image" style="background-image: url({{ asset('assets/images/placeholder.jpg') }});"></div>
                                        <div class="upload-options">
                                            <label class="img-upload-label" for="menu-items-${menuId}"><i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
                                            <input id="menu-items-${menuId}" type="file" class="image-upload" name="menu_photo[]" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>` +
                    '')
                menuId++;
            })
        @endif

        @if (isset($rooms))
            let roomId = '{{ count($rooms) > 0 ? count($rooms) : 1 }}';
            $('#room-add-btn').on('click', function() {
                $('.room-section-area').append('' +
                    `<div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('Hotel room') }}</h6>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Room Name') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="input-field" name="room_name[]" placeholder="{{ __('Enter Name') }}" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Room Price') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="input-group">
                                <input type="text" class="form-control" name="room_price[]"
                                    placeholder="{{ __('Enter Price') }}" value="">
                                    <select name="currency_symbol[]" id="currency_symbol[]" class="input-field input-group-text"
                                        style="width:100px !important;">
                                        <option value="tk">TK</option>
                                        <option value="usd">USD</option>
                                    </select>
                                 </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Room Photo') }}</h4>
                                    <p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="wrapper-image-preview">
                                    <div class="box">
                                        <div class="back-preview-image" style="background-image: url({{ asset('assets/images/placeholder.jpg') }});"></div>
                                        <div class="upload-options">
                                            <label class="img-upload-label" for="room-items-${roomId}"><i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
                                            <input id="room-items-${roomId}" type="file" class="image-upload" name="room_photo[]" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Room amenities') }}</h4>
                                    <p class="sub-heading">{{ __('Seperated By Comma(,)') }}</p>
                                </div>
                            </div>

                            <div class="col-lg-9">
                                <input type="text" id="tags" class="mytags tagify${roomId}" name="room_amenities[]" placeholder="{{ __('Room amenities') }}"  value="">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Room description') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <textarea class="input-field" name="room_description[]" placeholder="{{ __('Room description') }}" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="javascript:;" class="btn btn-danger removeRoom">{{ __('Remove') }}</a>
                        </div>
                    </div>
                </div>
                ` +
                    '')
                $('.tagify' + roomId).tagify({
                    focus: true
                });
                roomId++;
                $('.room-section-area').on('click', '.removeRoom', function() {
                    $(this).closest('.card').remove();
                });
            })
        @endif

        @if (isset($beauties))
            let beautyId = '{{ count($beauties) > 0 ? count($beauties) : 1 }}';
            $('#beauty-add-btn').on('click', function() {
                $('.beauty-section-area').append('' +
                    `<div class="card mb-4">
                        <div class="card-header py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="m-0 font-weight-bold text-primary">{{ __('Service Items') }}</h6>
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="removeBeauty" title="Remove">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Service Name') }} </h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="input-field" name="service_name[]" placeholder="{{ __('Enter Name') }}" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Service Price') }} </h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="number" class="input-field" name="service_price[]" id="service_price" placeholder="{{ __('Enter Price') }}" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Service Photo') }}</h4>
                                    <p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="wrapper-image-preview">
                                    <div class="box">
                                        <div class="back-preview-image" style="background-image: url({{ asset('assets/images/placeholder.jpg') }});"></div>
                                        <div class="upload-options">
                                            <label class="img-upload-label" for="service-items-${beautyId}"><i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
                                            <input id="service-items-${beautyId}" type="file" class="image-upload" name="service_photo[]" accept="image/">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Service Time') }} </h4>
                                </div>
                            </div>

                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" class="input-field" name="service_from[]" placeholder="{{ __('Enter time') }}" value="">
                                    </div>

                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('To') }} </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="service_to[]" placeholder="{{ __('Enter time') }}" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Service Duration') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="number" class="input-field" name="service_duration[]" placeholder="{{ __('Enter Duration') }}" value="">
                            </div>
                        </div>
                    </div>
                </div>` +
                    '')
                beautyId++;
            })
        @endif
        $('.card').on('click', '.removeBeauty', function() {
            $(this).closest('.card').remove();
        });


        @if (isset($faqs))
            $("#faq-add-btn").on('click', function() {
                $('.faq-section-area').append('' +
                    `<div class="card mb-4">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="m-0 font-weight-bold text-primary">{{ __('FAQ Items') }}</h6>
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="remove-faq" title="Remove">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Name') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="input-field" name="faq_name[]" placeholder="{{ __('Enter Name') }}"  value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Description') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <textarea name="faq_details[]" class="input-field" placeholder="{{ __('Description') }}" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>` +
                    '')
                $('.faq-section-area').on('click', '.remove-faq', function() {
                    $(this).closest('.card').remove();
                });
            })
        @endif

        $('.card').on('click', '.removeFaq', function() {
            $(this).closest('.card').remove();
        });


        // division to city
        $(document).ready(function() {
            $(document).on('change', '#division_id', function() {
                let id = $(this).val();
                if (id !== "") {
                    $.ajax({
                        url: "{{ route('admin.division.to.city') }}/" + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="city_id"]').empty();
                            $('select[name="area_id"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="city_id"]').append(
                                    '<option value="" class="d-none">Select</option>' +
                                    '<option value="' + value.id + '">' + value
                                    .name + '</option>'
                                );

                            });
                        }
                    });
                } else {
                    $('select[name="city_id"]').empty();
                }
            });
        });


        // city to area
        $(document).ready(function() {
            $(document).on('change', '#city_id', function() {
                let id = $(this).val();
                if (id !== "") {
                    $.ajax({
                        url: "{{ route('admin.city.to.area') }}/" + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="area_id"]').empty();
                            $('select[name="area_id"]').append(
                                '<option value="" class="d-none">Select</option>');
                            $.each(data, function(key, value) {
                                $('select[name="area_id"]').append(
                                    '<option value="' + value.id + '">' + value
                                    .title + '</option>'
                                );
                            });
                            $('select[name="area_id"]').select2();
                        }
                    });
                } else {
                    $('select[name="area_id"]').empty();
                }
            });
        });

        // schedule time check
        $(document).ready(function() {
            function openingTime() {
                var selectedOpening = $('#saturday_opening').val();
                if ($('#openTime').prop('checked') && selectedOpening !== "") {
                    $('#sunday_opening').val(selectedOpening);
                    $('#monday_opening').val(selectedOpening);
                    $('#tuesday_opening').val(selectedOpening);
                    $('#wednesday_opening').val(selectedOpening);
                    $('#thursday_opening').val(selectedOpening);
                    $('#friday_opening').val(selectedOpening);
                } else {
                    $('#sunday_opening').val("");
                    $('#monday_opening').val("");
                    $('#tuesday_opening').val("");
                    $('#wednesday_opening').val("");
                    $('#thursday_opening').val("");
                    $('#friday_opening').val("");
                }
            }
            $('#openTime').change(function() {
                openingTime();
            });
            $('#saturday_opening').change(function() {
                openingTime();
            });
        });

        $(document).ready(function() {
            function closignTime() {
                var selectedClosing = $('#saturday_closing').val();
                if ($('#closeTime').prop('checked') && selectedClosing !== "") {
                    $('#sunday_closing').val(selectedClosing);
                    $('#monday_closing').val(selectedClosing);
                    $('#tuesday_closing').val(selectedClosing);
                    $('#wednesday_closing').val(selectedClosing);
                    $('#thursday_closing').val(selectedClosing);
                    $('#friday_closing').val(selectedClosing);
                } else {
                    $('#sunday_closing').val("");
                    $('#monday_closing').val("");
                    $('#tuesday_closing').val("");
                    $('#wednesday_closing').val("");
                    $('#thursday_closing').val("");
                    $('#friday_closing').val("");
                }
            }
            $('#closeTime').change(function() {
                closignTime();
            });
            $('#saturday_closing').change(function() {
                closignTime();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#menu_price').on('input', function() {
                var inputValue = $(this).val();
                var sanitizedValue = inputValue.replace(/[^\d-_]/g);
                $(this).val(sanitizedValue);
            });
        });

        // description field text limit
        $(document).ready(function() {
            var maxLength = 4500;

            function updateDescriptionCount() {
                var html = $('#descriptionField').summernote('code') || '';
                var text = $('<div>').html(html).text().trim();
                var length = text.length;

                if (length > maxLength) {
                    $('.word_limit').text(maxLength + '/4500');
                    return;
                }

                $('.word_limit').text(length + '/4500');
            }

            $('#descriptionField').on('summernote.change', function() {
                updateDescriptionCount();
            });

            updateDescriptionCount();
        });
    </script>
    <script>
$('#generateDescription').on('click', function() {

    let descriptionField = $('#descriptionField');

    // ✅ Confirm BEFORE sending request
    if($('<div>').html(descriptionField.summernote('code') || '').text().trim().length > 20){
        if(!confirm("This will overwrite existing description. Continue?")){
            return;
        }
    }

    let button = this;
    button.innerHTML = "Generating...";
    button.disabled = true;

    fetch("{{ route('admin.generate.description') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            type: @json(optional($data->listingType)->name),
            category: @json(optional($data->category)->title),
            division: @json(optional($data->division)->name),
            city: @json(optional($data->city)->name),
            title: @json($data->name),
            description: @json($data->description ?? '')
        })
    })
    .then(response => response.json())
    .then(data => {

        //console.log("AI Result:", data); // ✅ Debug

        if(data.error){
            alert(data.error);
            button.innerHTML = "🤖 Generate with AI";
            button.disabled = false;
            return;
        }

        // ✅ Assign values safely
        document.getElementById('listingTitle').value = data.title ?? '';
        descriptionField.summernote('code', data.description ?? '');
        document.getElementById('meta_title').value = data.meta_title ?? '';
        document.getElementById('keywords').value = data.meta_keywords ?? '';
        document.getElementById('meta_description').value = data.meta_description ?? '';

        button.innerHTML = "🤖 Generate with AI";
        button.disabled = false;
    })
    .catch(error => {

        if (window.console) {
            console.log(error);
        }

        alert("Something went wrong!");

        button.innerHTML = "🤖 Generate with AI";
        button.disabled = false;
    });

});
</script>
<script>
$(document).on('input', '#whatsapp_number', function() {
    this.value = this.value.replace(/-/g, '');
});

function formatBangladeshWhatsappNumber(phone) {
    let number = (phone || '').replace(/-/g, '').replace(/\s+/g, '');

    if (number === '') {
        return '';
    }

    number = number.replace(/[^\d+]/g, '');

    if (number.charAt(0) === '+') {
        number = '+' + number.substring(1).replace(/\D/g, '');
        return number.indexOf('+88') === 0 ? number : '+88' + number.substring(1);
    }

    number = number.replace(/\D/g, '');

    if (number.indexOf('88') === 0) {
        return '+' + number;
    }

    return '+88' + number;
}

$('#generateDescription').off('click').on('click', function() {
    let descriptionField = $('#descriptionField');
    let currentDescription = descriptionField.summernote('code') || '';
    let currentDescriptionText = $('<div>').html(currentDescription).text().trim();
    let phoneNumber = $('#phone_number').val();
    let whatsappNumber = formatBangladeshWhatsappNumber(phoneNumber);
    let button = this;
    let originalButtonText = button.innerHTML;

    if (phoneNumber && whatsappNumber) {
        $('#whatsapp_number').val(whatsappNumber);
    }

    if (currentDescriptionText.length > 20) {
        if (!confirm("This will overwrite existing description. Continue?")) {
            return;
        }
    }

    button.innerHTML = "Generating...";
    button.disabled = true;

    fetch("{{ route('admin.generate.description') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            type: @json(optional($data->listingType)->name),
            category: @json(optional($data->category)->title),
            division: @json(optional($data->division)->name),
            city: @json(optional($data->city)->name),
            title: $('#listingTitle').val(),
            description: currentDescription
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
            button.innerHTML = originalButtonText;
            button.disabled = false;
            return;
        }

        document.getElementById('listingTitle').value = data.title ?? '';
        descriptionField.summernote('code', data.description ?? '');
        document.getElementById('meta_title').value = data.meta_title ?? '';
        document.getElementById('keywords').value = data.meta_keywords ?? '';
        document.getElementById('meta_description').value = data.meta_description ?? '';

        button.innerHTML = originalButtonText;
        button.disabled = false;
    })
    .catch(error => {
        if (window.console) {
            console.log(error);
        }

        alert("Something went wrong!");

        button.innerHTML = originalButtonText;
        button.disabled = false;
    });
});
</script>
@endsection
