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
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Add New Listing') }} <a class="btn btn-primary btn-rounded btn-sm"
                    href="{{ route('admin.listing.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('admin.listing.create', ['type' => request()->type]) }}">{{ __('Add New Listing') }}</a>
                </li>
            </ol>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Add New Listing Form') }}</h6>
                </div>

                <div class="card-body">
                    <div class="gocover"
                        style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                    </div>
                    <form class="geniusform" action="{{ route('admin.listing.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @include('includes.admin.form-both')
                        {{ csrf_field() }}

                        <input type="hidden" name="type" value="{{ request()->type }}">
                        <div class="container pl-0 pr-0 ml-0 mr-0 w-100 mw-100">
                            <div id="tabs">
                                <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link bsc active" data-toggle="pill"
                                            href="#basic">{{ __('Basic') }}</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link amenities" data-toggle="pill"
                                            href="#amenities">{{ __('Amenities') }}</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link media" data-toggle="pill" href="#media">{{ __('Media') }}</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link schedule" data-toggle="pill"
                                            href="#schedule">{{ __('Schedule') }}</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link contact" data-toggle="pill"
                                            href="#contact">{{ __('Contact') }}</a>
                                    </li>

                                    @if (request()->type == 'restaurant')
                                        <li class="nav-item">
                                            <a class="nav-link dynamic" data-toggle="pill"
                                                href="#dynamic">{{ ucfirst(request()->type) }} {{ __('Item') }}</a>
                                        </li>
                                    @endif

                                    @if (request()->type == 'hotel')
                                        <li class="nav-item">
                                            <a class="nav-link dynamic" data-toggle="pill"
                                                href="#dynamic">{{ ucfirst(request()->type) }} {{ __('Room') }}</a>
                                        </li>
                                    @endif

                                    @if (request()->type == 'beauty')
                                        <li class="nav-item">
                                            <a class="nav-link dynamic" data-toggle="pill"
                                                href="#dynamic">{{ ucfirst(request()->type) }} {{ __('Service') }}</a>
                                        </li>
                                    @endif

                                    @if (request()->type == 'real_estate')
                                        <li class="nav-item">
                                            <a class="nav-link dynamic" data-toggle="pill"
                                                href="#dynamic">{{ ucfirst(str_replace('_', ' ', request()->type)) }}
                                                {{ __('Info') }}</a>
                                        </li>
                                    @endif

                                    @if (request()->type == 'car')
                                        <li class="nav-item">
                                            <a class="nav-link dynamic" data-toggle="pill"
                                                href="#dynamic">{{ ucfirst(request()->type) }}
                                                {{ __('Specification') }}</a>
                                        </li>
                                    @endif

                                    <li class="nav-item">
                                        <a class="nav-link faq" data-toggle="pill" href="#faq">{{ __('FAQ') }}</a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div id="basic" class="container tab-pane active"><br>
                                        @if (request()->type == 'hospitals')
                                            <div class="row mb-2">
                                                <div class="col-lg-3">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Listing Title') }} *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input type="text" class="input-field" name="name"
                                                        id="listingTitle" placeholder="{{ __('Listing Title') }}"
                                                        value="">
                                                </div>
                                            </div>

                                            <div class="row mb-1">
                                                <div class="col-lg-3">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Listing Slug') }} *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input type="text" class="input-field mb-0" name="slug"
                                                        id="listingSlug" placeholder="{{ __('Listing Slug') }}"
                                                        value="">
                                                    <div style="height:25px">
                                                        <span id="slugData"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row mb-2">
                                                <div class="col-lg-3">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Listing Title') }} *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input type="text" class="input-field" name="name"
                                                        id="listingTitle" placeholder="{{ __('Listing Title') }}"
                                                        value="">
                                                </div>
                                            </div>

                                            <div class="row mb-1">
                                                <div class="col-lg-3">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Listing Slug') }} *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input type="text" class="input-field mb-0" name="slug"
                                                        id="listingSlug" placeholder="{{ __('Listing Slug') }}"
                                                        value="">
                                                    <div style="height:25px">
                                                        <span id="slugData"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Category') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <select name="category_id" class="input-field">
                                                    <option value="" class="d-none">
                                                        {{ __('Please select a category') }}</option>
                                                    @foreach ($categories as $key => $category)
                                                        <option value="{{ $category->id }}">{{ $category->title }}
                                                        </option>
                                                        @if ($category->child)
                                                            @foreach ($category->child as $key => $childlocation)
                                                                <option value="{{ $childlocation->id }}">
                                                                    -{{ $childlocation->title }}</option>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Division') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <select name="division_id" id="division_id" class="input-field">
                                                    <option value="" class="d-none">
                                                        {{ __('Please select a division') }}</option>
                                                    @foreach ($divisions as $key => $division)
                                                        <option value="{{ $division->id }}">
                                                            {{ ucfirst($division->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('City') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <select name="city_id" id="city_id" class="input-field">
                                                    <option value="" class="d-none">
                                                        {{ __('Please select a city') }}</option>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Area') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <select name="area_id" id="area_id" class="input-field select2">
                                                    <option value="" class="d-none">
                                                        {{ __('Please select a area') }}</option>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Real address') }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="real_address"
                                                    placeholder="{{ __('Address') }}" value="">
                                            </div>
                                        </div>

                                        {{-- <div class="row mb-1">
                                        <div class="col-lg-3">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Map Latitude') }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <input type="text" class="input-field" name="latitude"  placeholder="{{ __('Latitude') }}" value="">
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="col-lg-3">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Map Longitude') }}</h4>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <input type="text" class="input-field" name="longitude"  placeholder="{{ __('Longitude') }}" value="">
                                        </div>
                                    </div> --}}

                                        @if (request()->type == 'hotel')
                                            <div class="row mb-1">
                                                <div class="col-lg-3">
                                                    <div class="left-area">
                                                        <h4 class="heading">{{ __('Distance') }}</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input type="text" class="input-field" name="distance"
                                                        placeholder="{{ __('Distance') }}" value="">
                                                </div>
                                            </div>
                                        @endif


                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Description') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <textarea name="description" class="input-field" placeholder="{{ __('Description') }}" cols="30"
                                                    rows="10"></textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Map Location (iframe code)') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <textarea class="input-field rounded" cols="5" rows="5" name="map_location"
                                                    placeholder="@lang('Location')"></textarea>
                                            </div>
                                        </div>


                                       <div class="row mt-3 is_top">
                                            <div class="col-lg-3">
                                                <div class="left-area">

                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="form-check">
                                                    <input type="checkbox" name="is_feature" class="form-check-input"
                                                        value="1" id="is_feature">
                                                    <label class="form-check-label"
                                                        for="is_feature">{{ __('Check if this directory is featured') }}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <ul class="list-inline mt-3 mb-0  text-center">
                                            <li class="list-inline-item">
                                                <a href="javascript:;" data-href=".amenities"
                                                    class="next-prev btn btn-primary"> {{ __('Next') }} </a>
                                            </li>
                                        </ul>

                                    </div>

                                    <div id="amenities" class="container tab-pane"><br>
                                        <div class="row mb-1">
                                            @if ($amenities)
                                                @foreach ($amenities as $key => $amenity)
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="amenities[{{ $amenity->icon }}][]"
                                                                value="{{ $amenity->name }}"
                                                                id="{{ $amenity->name }}-option-{{ $key }}">
                                                            <label class="form-check-label"
                                                                for="{{ $amenity->name }}-option-{{ $key }}">{{ $amenity->name }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <ul class="list-inline mt-3 mb-0  text-center">
                                            <li class="list-inline-item">
                                                <a href="javascript:;" data-href=".bsc"
                                                    class="next-prev btn btn-primary"> {{ __('Prev') }} </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="javascript:;" data-href=".media"
                                                    class="next-prev btn btn-primary"> {{ __('Next') }} </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div id="media" class="container tab-pane"><br>
                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Listing Video Provider') }}*</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <select name="listing_video_provider" class="input-field">
                                                    <option value="youtube">{{ __('Youtube') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Listing Video Url') }}*</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="listing_video"
                                                    placeholder="https://www.youtube.com/watch?v=AXrHbrMrun0"
                                                    value="">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Listing Thumbnail') }}*</h4>
                                                    <p class="sub-heading">({{ __('Preferred Size 600 x 600') }})</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="wrapper-image-preview">
                                                    <div class="box">
                                                        <div class="back-preview-image"
                                                            style="background-image: url({{ asset('assets/images/default.png') }});">
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
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Listing Gallary') }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <button type="button" class="btn btn-primary set-gallery"
                                                    data-toggle="modal" data-target="#setgallery" id="#myBtn">
                                                    <i class="icofont-plus"></i> {{ __('Set Gallery') }}
                                                </button>
                                                <input type="file" name="gallery[]" class="hidden"
                                                    id="propertyuploadgallery" accept="image/*" multiple>
                                            </div>
                                        </div>


                                        <ul class="list-inline mt-3 mb-0  text-center">
                                            <li class="list-inline-item">
                                                <a href="javascript:;" data-href=".amenities"
                                                    class="next-prev btn btn-primary"> {{ __('Prev') }} </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="javascript:;" data-href=".schedule"
                                                    class="next-prev btn btn-primary"> {{ __('Next') }} </a>
                                            </li>
                                        </ul>

                                    </div>

                                    <div id="schedule" class="container tab-pane"><br>

                                        <div class="row mb-1">
                                            <div class="col-lg-2">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Saturday') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="openTime">
                                                    <label class="form-check-label" for="openTime">
                                                        Check All Opening Time Same
                                                    </label>
                                                </div>
                                                <select class="input-field" name="saturday_opening"
                                                    id="saturday_opening">
                                                    <option value="" class="d-none">{{ __('Opening Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-5">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="closeTime">
                                                    <label class="form-check-label" for="closeTime">
                                                        Check All Closing Time Same
                                                    </label>
                                                </div>
                                                <select class="input-field" name="saturday_closing"
                                                    id="saturday_closing">
                                                    <option value="" class="d-none">{{ __('Closing Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-2">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Sunday') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <select class="input-field" name="sunday_opening" id="sunday_opening">
                                                    <option value="" class="d-none">{{ __('Opening Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-5">
                                                <select class="input-field" name="sunday_closing" id="sunday_closing">
                                                    <option value="" class="d-none">{{ __('Closing Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-2">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Monday') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <select class="input-field" name="monday_opening" id="monday_opening">
                                                    <option value="" class="d-none">{{ __('Opening Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-5">
                                                <select class="input-field" name="monday_closing" id="monday_closing">
                                                    <option value="" class="d-none">{{ __('Closing Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-2">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Tuesday') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <select class="input-field" name="tuesday_opening" id="tuesday_opening">
                                                    <option value="" class="d-none">{{ __('Opening Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-5">
                                                <select class="input-field" name="tuesday_closing" id="tuesday_closing">
                                                    <option value="" class="d-none">{{ __('Closing Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-2">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Wednesday') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <select class="input-field" name="wednesday_opening"
                                                    id="wednesday_opening">
                                                    <option value="" class="d-none">{{ __('Opening Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-5">
                                                <select class="input-field" name="wednesday_closing"
                                                    id="wednesday_closing">
                                                    <option value="" class="d-none">{{ __('Closing Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-2">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Thursday') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <select class="input-field" name="thursday_opening"
                                                    id="thursday_opening">
                                                    <option value="" class="d-none">{{ __('Opening Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-5">
                                                <select class="input-field" name="thursday_closing"
                                                    id="thursday_closing">
                                                    <option value="" class="d-none">{{ __('Closing Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-2">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Friday') }} *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <select class="input-field" name="friday_opening" id="friday_opening">
                                                    <option value="" class="d-none">{{ __('Opening Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-5">
                                                <select class="input-field" name="friday_closing" id="friday_closing">
                                                    <option value="" class="d-none">{{ __('Closing Time') }}
                                                    </option>
                                                    <option value="24 hours">24 hours</option>
                                                    <option value="12 am">12 am</option>
                                                    <option value="12.30 am">12.30 am</option>
                                                    <option value="01 am">01 am</option>
                                                    <option value="01.30 am">01.30 am</option>
                                                    <option value="02 am">02 am</option>
                                                    <option value="02.30 am">02.30 am</option>
                                                    <option value="03 am">03 am</option>
                                                    <option value="03.30 am">03.30 am</option>
                                                    <option value="04 am">04 am</option>
                                                    <option value="04.30 am">04.30 am</option>
                                                    <option value="05 am">05 am</option>
                                                    <option value="05.30 am">05.30 am</option>
                                                    <option value="06 am">06 am</option>
                                                    <option value="06.30 am">06.30 am</option>
                                                    <option value="07 am">07 am</option>
                                                    <option value="07.30 am">07.30 am</option>
                                                    <option value="08 am">08 am</option>
                                                    <option value="08.30 am">08.30 am</option>
                                                    <option value="09 am">09 am</option>
                                                    <option value="09.30 am">09.30 am</option>
                                                    <option value="10 am">10 am</option>
                                                    <option value="10.30 am">10.30 am</option>
                                                    <option value="11 am">11 am</option>
                                                    <option value="11.30 am">11.30 am</option>
                                                    <option value="12 pm">12 pm</option>
                                                    <option value="12.30 pm">12.30 pm</option>
                                                    <option value="01 pm">01 pm</option>
                                                    <option value="01.30 pm">01.30 pm</option>
                                                    <option value="02 pm">02 pm</option>
                                                    <option value="02.30 pm">02.30 pm</option>
                                                    <option value="03 pm">03 pm</option>
                                                    <option value="03.30 pm">03.30 pm</option>
                                                    <option value="04 pm">04 pm</option>
                                                    <option value="04.30 pm">04.30 pm</option>
                                                    <option value="05 pm">05 pm</option>
                                                    <option value="05.30 pm">05.30 pm</option>
                                                    <option value="06 pm">06 pm</option>
                                                    <option value="06.30 pm">06.30 pm</option>
                                                    <option value="07 pm">07 pm</option>
                                                    <option value="07.30 pm">07.30 pm</option>
                                                    <option value="08 pm">08 pm</option>
                                                    <option value="08.30 pm">08.30 pm</option>
                                                    <option value="09 pm">09 pm</option>
                                                    <option value="09.30 pm">09.30 pm</option>
                                                    <option value="10 pm">10 pm</option>
                                                    <option value="10.30 pm">10.30 pm</option>
                                                    <option value="11 pm">11 pm</option>
                                                    <option value="11.30 pm">11.30 pm</option>
                                                    <option value="closed">Closed</option>
                                                </select>
                                            </div>
                                        </div>

                                        <ul class="list-inline mt-3 mb-0  text-center">
                                            <li class="list-inline-item">
                                                <a href="javascript:;" data-href=".media"
                                                    class="next-prev btn btn-primary"> {{ __('Prev') }} </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="javascript:;" data-href=".contact"
                                                    class="next-prev btn btn-primary"> {{ __('Next') }} </a>
                                            </li>
                                        </ul>

                                    </div>

                                    <div id="contact" class="container tab-pane"><br>
                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Website') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" id="inp-website"
                                                    name="website" placeholder="{{ __('Website') }}">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Email') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="email"
                                                    placeholder="{{ __('Email') }}">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Phone number') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="phone_number"
                                                    placeholder="{{ __('Phone number') }}">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Facebook') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="facebook"
                                                    placeholder="{{ __('Facebook') }}">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Twitter') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="twitter"
                                                    placeholder="{{ __('Twitter') }}">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Linkedin') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="linkedin"
                                                    placeholder="{{ __('Linkedin') }}">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Youtube') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="youtube"
                                                    placeholder="{{ __('Youtube') }}">
                                            </div>
                                        </div>


                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Instagram') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="instagram"
                                                    placeholder="{{ __('Instagram') }}">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Meta Keywords') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <input type="text" class="input-field" name="meta_keywords"
                                                    placeholder="{{ __('Meta Keywords') }}">
                                            </div>
                                        </div>

                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Meta Description') }}</h4>
                                                </div>
                                            </div>

                                            <div class="col-lg-9">
                                                <textarea name="meta_description" class="input-field mb-0" id="meta_description" maxlength="160"
                                                    cols="30" rows="10" placeholder="{{ __('Meta Description') }}"></textarea>
                                                <span>You have write maximum <span id="characters-counter">160</span>
                                                    characters</span>
                                            </div>
                                        </div>

                                        <ul class="list-inline mt-3 mb-0  text-center">
                                            <li class="list-inline-item">
                                                <a href="javascript:;" data-href=".schedule"
                                                    class="next-prev btn btn-primary"> {{ __('Prev') }} </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="javascript:;" data-href=".dynamic"
                                                    class="next-prev btn btn-primary"> {{ __('Next') }} </a>
                                            </li>
                                        </ul>

                                    </div>

                                    <div id="dynamic" class="container tab-pane"><br>
                                        @if (request()->type == 'restaurant')
                                            <div class="menu-section-area">
                                                @includeIf('partials.admin.listing.menu')
                                            </div>

                                            <div class="row my-2">
                                                <div class="col-lg-12 text-center">
                                                    <a href="javascript:;" id="menud-add-btn" class="btn btn-info"><i
                                                            class="fa fa-plus"></i> {{ __('Add More') }}</a>
                                                </div>
                                            </div>
                                        @endif

                                        @if (request()->type == 'hotel')
                                            <div class="room-section-area">
                                                @includeIf('partials.admin.listing.room')
                                            </div>

                                            <div class="row my-2">
                                                <div class="col-lg-12 text-center">
                                                    <a href="javascript:;" id="room-add-btn" class="btn btn-info"><i
                                                            class="fa fa-plus"></i> {{ __('Add More') }}</a>
                                                </div>
                                            </div>
                                        @endif

                                        @if (request()->type == 'beauty')
                                            <div class="beauty-section-area">
                                                @includeIf('partials.admin.listing.beauty')
                                            </div>

                                            <div class="row my-2">
                                                <div class="col-lg-12 text-center">
                                                    <a href="javascript:;" id="beauty-add-btn" class="btn btn-info"><i
                                                            class="fa fa-plus"></i> {{ __('Add More') }}</a>
                                                </div>
                                            </div>
                                        @endif

                                        @if (request()->type == 'real_estate')
                                            @includeIf('partials.admin.listing.real_estate')
                                        @endif

                                        @if (request()->type == 'car')
                                            @includeIf('partials.admin.listing.car')
                                        @endif

                                    </div>

                                    <div id="faq" class="container tab-pane"><br>
                                        <div class="faq-section-area">
                                            @includeIf('partials.admin.listing.faq')
                                        </div>

                                        <div class="row my-2">
                                            <div class="col-lg-12 text-center">
                                                <a href="javascript:;" id="faq-add-btn" class="btn btn-info"><i
                                                        class="fa fa-plus"></i> {{ __('Add More') }}</a>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center mt-3">
                                            <button type="submit" id="submit-btn"
                                                class="btn btn-primary text-center">{{ __('Submit') }}</button>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
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
                                    <label id="property_gallery"><i
                                            class="icofont-upload-alt"></i>{{ __('Upload File') }}</label>
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
                            <div class="row mb-1">


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
    <script>
        // select 2
        $(document).ready(function() {
            $('.select2').select2();
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
                var generatedSlug = generateSlug(titleValue);
                $('#listingSlug').val(generatedSlug);
                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.listing.slug.check') }}",
                    data: {
                        slug: generatedSlug
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

        $(document).on('click', '.remove-img', function() {
            var id = $(this).find('input[type=hidden]').val();
            $(this).parent().parent().remove();
        });

        let menuId = 2;
        $('#menud-add-btn').on('click', function() {
            $('.menu-section-area').append('' +
                `<div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('Menu Items') }}</h6>
                        <button class="border-0"><i class="fas fa-trash-alt text-danger"></i></button>
                    </div>

                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Menu Name') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="input-field" name="menu_title[]" placeholder="{{ __('Enter Name') }}" value="">
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Menu Price') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="number" class="input-field" name="menu_price[]" placeholder="{{ __('Enter Price') }}" value="">
                            </div>
                        </div>

                        <div class="row mb-1">
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

        let roomId = 2;
        $('#room-add-btn').on('click', function() {
            $('.room-section-area').append('' +
                `<div class="card mb-4">
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Room Name') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="input-field" name="room_name[]" placeholder="{{ __('Enter Name') }}" value="">
                            </div>
                        </div>

                        <div class="row mb-1">
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

                        <div class="row mb-1">
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

        let beautyId = 2;
        $('#beauty-add-btn').on('click', function() {
            $('.beauty-section-area').append('' +
                `<div class="card mb-4">
                    <div class="card-header py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="m-0 font-weight-bold text-primary">{{ __('Service Items') }}</h6>
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="remove-beauty" title="Remove">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-1">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Service Name') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="input-field" name="service_name[]" placeholder="{{ __('Enter Name') }}" value="">
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Service Price') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="number" class="input-field" name="service_price[]" placeholder="{{ __('Enter Price') }}" id="service_price" value="">

                            </div>
                        </div>

                        <div class="row mb-1">
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
                                            <input id="service-items-${beautyId}" type="file" class="image-upload" name="service_photo[]" accept="image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Service Time') }}</h4>
                                </div>
                            </div>

                            <div class="col-lg-9">
                                <div class="row mb-1">
                                    <div class="col-md-4">
                                        <input type="text" class="input-field" name="service_from[]" placeholder="{{ __('Enter time') }}" value="">
                                    </div>

                                    <div class="col-md-5">
                                        <div class="row mb-1">
                                            <div class="col-lg-3">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('To') }}</h4>
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

                        <div class="row mb-1">
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

            $('.beauty-section-area').on('click', '.remove-beauty', function() {
                $(this).closest('.card').remove();
            });
        })

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
                        <div class="row mb-1">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Name') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <input type="text" class="input-field" name="faq_name[]" placeholder="{{ __('Enter Name') }}"  value="">
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Description') }}</h4>
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
    </script>   <script>
        $(document).ready(function() {
            $('#service_price').on('input', function() {
                var inputValue = $(this).val();
                var sanitizedValue = inputValue.replace(/[^0-9_\-]/g, '');
                $(this).val(sanitizedValue);
            });
        });

        $(document).ready(function() {
            $('#menu_price').on('input', function() {
                var inputValue = $(this).val();
                var sanitizedValue = inputValue.replace(/[^0-9_\-]/g, '');
                $(this).val(sanitizedValue);
            });
        });
    </script>
@endsection
