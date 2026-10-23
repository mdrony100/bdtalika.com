@extends('layouts.front')
@section('stepTwo', 'active')
@push('css')
    <link href="{{ asset('assets/admin/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 3px !important;
            height: 35px !important;
            padding: 4px 8px;
            font-size: 15px;
            border-color: #dbdbdb !important;
            color: #2b2e30;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 4px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 23px !important;
        }

        .select2-search--dropdown .select2-search__field {
            outline: none !important;
            box-shadow: none !important;
        }

        .select2-container {
            width: 100% !important;
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
                            <li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0)" class="">@lang('Create Listing')</a>
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
            <div class="dashboard-widg-bar d-block">
                <div class="row">
                    <form class="listingForm" action="{{ route('user.listing.update.step2', $data->id) }}" method="POST"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{-- tab menu --}}
                        <div class="tab-menu">
                            @include('user.listing.tabs_menu')
                        </div>
                        <div class="dashboard-list-wraps bg-white rounded border">
                            <!-- Amenties Options -->
                            <div class="dashboard-list-wraps bg-white rounded">
                                <div class="dashboard-list-wraps-body py-4 px-4 border">
                                    <div class="row">
                                        @if ($category == 'hotels')
                                            <h4 class="mb-0 ft-medium fs-md  pb-3">
                                                <i class="lni lni-coffee-cup me-2 theme-cl fs-sm"></i>@lang('Amenities')
                                            </h4>
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                                <div class="Rego-all-features-list">
                                                    <ul>
                                                        @if (isset($amenities))
                                                            @foreach ($amenities as $key => $amenity)
                                                                <li>
                                                                    <input class="checkbox-custom"
                                                                        name="amenities[{{ $amenity->name }}][]"
                                                                        value="{{ $amenity->name }}"
                                                                        id="{{ $amenity->name }}-option-{{ $key }}"
                                                                        type="checkbox"
                                                                        {{ isset($listingAmenities) ? (in_array($amenity->name, $listingAmenities) ? 'checked' : '') : '' }}>
                                                                    <label
                                                                        for="{{ $amenity->name }}-option-{{ $key }}"
                                                                        class="checkbox-custom-label">{{ $amenity->name }}</label>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                        <h4 class="mb-0 ft-medium fs-md pt-4 px-3">
                                            <i class="fa fa-clock me-2 theme-cl fs-sm"></i>@lang('Working Hours')
                                        </h4>
                                        <div class="dashboard-list-wraps-body py-3 px-3">
                                            <div class="row">
                                                <div class="form-group mb-2">
                                                    <div class="row align-items-center gy-2 gy-lg-0">
                                                        <label
                                                            class="control-label col-lg-2 col-md-2">@lang('Saturday')</label>
                                                        <div class="col-lg-5 col-md-5">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="" id="openTime">
                                                                <label class="form-check-label" for="openTime">
                                                                    Check All Opening Time Same
                                                                </label>
                                                            </div>
                                                            <select class="form-control chosen-select select2"
                                                                name="saturday_opening" id="saturday_opening">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Opening Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['sat_open'])
                                                                        ? $listingSchedules['sat_open']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="" id="closeTime">
                                                                <label class="form-check-label" for="closeTime">
                                                                    Check All Closing Time Same
                                                                </label>
                                                            </div>
                                                            <select class="form-control select2" name="saturday_closing"
                                                                id="saturday_closing">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Closing Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['sat_close'])
                                                                        ? $listingSchedules['sat_close']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <div class="row align-items-center gy-2 gy-lg-0">
                                                        <label
                                                            class="control-label col-lg-2 col-md-2">@lang('Sunday')</label>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control chosen-select select2"
                                                                name="sunday_opening" id="sunday_opening">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Opening Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['sun_open'])
                                                                        ? $listingSchedules['sun_open']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control select2" name="sunday_closing"
                                                                id="sunday_closing">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Closing Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['sun_close'])
                                                                        ? $listingSchedules['sun_close']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <div class="row align-items-center gy-2 gy-lg-0">
                                                        <label
                                                            class="control-label col-lg-2 col-md-2">@lang('Monday')</label>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control chosen-select select2"
                                                                name="monday_opening" id="monday_opening">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Opening Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['mon_open'])
                                                                        ? $listingSchedules['mon_open']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control select2" name="monday_closing"
                                                                id="monday_closing">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Closing Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['mon_close'])
                                                                        ? $listingSchedules['mon_close']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <div class="row align-items-center gy-2 gy-lg-0">
                                                        <label
                                                            class="control-label col-lg-2 col-md-2">@lang('Tuesday')</label>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control chosen-select select2"
                                                                name="tuesday_opening" id="tuesday_opening">
                                                                <option value="" class="d-none text-danger">Opening
                                                                    Time
                                                                </option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['tue_open'])
                                                                        ? $listingSchedules['tue_open']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control select2" name="tuesday_closing"
                                                                id="tuesday_closing">
                                                                <option value="" class="d-none text-danger">Closing
                                                                    Time
                                                                </option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['tue_close'])
                                                                        ? $listingSchedules['tue_close']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-2">
                                                    <div class="row align-items-center gy-2 gy-lg-0">
                                                        <label
                                                            class="control-label col-lg-2 col-md-2">@lang('Wednesday')</label>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control chosen-select select2"
                                                                name="wednesday_opening" id="wednesday_opening">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Opening Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['wed_open'])
                                                                        ? $listingSchedules['wed_open']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control select2" name="wednesday_closing"
                                                                id="wednesday_closing">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Closing Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset(
                                                                        $listingSchedules['wed_close']
                                                                )
                                                                        ? $listingSchedules['wed_close']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-2">
                                                    <div class="row align-items-center gy-2 gy-lg-0">
                                                        <label
                                                            class="control-label col-lg-2 col-md-2">@lang('Thursday')</label>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control chosen-select select2"
                                                                name="thursday_opening" id="thursday_opening">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Opening Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['thu_open'])
                                                                        ? $listingSchedules['thu_open']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control select2" name="thursday_closing"
                                                                id="thursday_closing">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Closing Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset(
                                                                        $listingSchedules['thu_close']
                                                                )
                                                                        ? $listingSchedules['thu_close']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <div class="row align-items-center gy-2 gy-lg-0">
                                                        <label
                                                            class="control-label col-lg-2 col-md-2">@lang('Friday')</label>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control chosen-select select2"
                                                                name="friday_opening" id="friday_opening">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Opening Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['fri_open'])
                                                                        ? $listingSchedules['fri_open']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5">
                                                            <select class="form-control select2" name="friday_closing"
                                                                id="friday_closing">
                                                                <option value="" class="d-none text-danger">
                                                                    @lang('Closing Time')</option>
                                                                @includeIf('partials.user.time', [
                                                                    'data' => isset($listingSchedules['mon_open'])
                                                                        ? $listingSchedules['mon_open']
                                                                        : null,
                                                                ])
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-0 text-center pt-3 pb-3 bg-white">
                            <input type="hidden" id="cancel_edit_input" name="cancel_edit" value="0">
                            <div class="d-flex align-items-center justify-content-center">
                                <div>
                                    <button type="submit" class="btn theme-bg rounded text-light">Save & Next</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        })

        // schedule time check
        $(document).ready(function() {
            $('#saturday_opening, #monday_opening, #tuesday_opening, #wednesday_opening, #thursday_opening, #friday_opening')
                .select2();

            function openingTime() {
                var selectedOpening = $('#saturday_opening').val();
                if ($('#openTime').prop('checked') && selectedOpening !== "") {
                    $('#sunday_opening, #monday_opening, #tuesday_opening, #wednesday_opening, #thursday_opening, #friday_opening')
                        .val(selectedOpening).trigger('change');
                } else {
                    $('#sunday_opening, #monday_opening, #tuesday_opening, #wednesday_opening, #thursday_opening, #friday_opening')
                        .val("").trigger('change');
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
            $('#saturday_closing, #monday_closing, #tuesday_closing, #wednesday_closing, #thursday_closing, #friday_closing')
                .select2();

            function closingTime() {
                var selectedOpening = $('#saturday_closing').val();
                if ($('#closeTime').prop('checked') && selectedOpening !== "") {
                    $('#sunday_closing, #monday_closing, #tuesday_closing, #wednesday_closing, #thursday_closing, #friday_closing')
                        .val(selectedOpening).trigger('change');
                } else {
                    $('#sunday_closing, #monday_closing, #tuesday_closing, #wednesday_closing, #thursday_closing, #friday_closing')
                        .val("").trigger('change');
                }
            }
            $('#closeTime').change(function() {
                closingTime();
            });
            $('#saturday_closing').change(function() {
                closingTime();
            });
        });

        function updateCancelEdit() {
            $('#cancel_edit_input').val(1);
            $('.listingForm').submit();
        }
    </script>
@endpush
