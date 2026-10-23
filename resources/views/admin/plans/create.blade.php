@extends('layouts.admin')
@section('styles')
    <style>
        .add-fild-btn {
    border: 1px solid #ddd;
    padding: 8px 20px;
    border-radius: 5px;
    font-size: 15px;
    font-weight: 600;
}
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Add New Plan') }} <a class="btn btn-primary btn-rounded btn-sm"
                    href="{{ route('admin.plans.index') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Manage Plan') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Add New Plan Form') }}</h6>
                </div>
                <div class="card-body">
                    <div class="gocover"
                        style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                    </div>
                    <form class="geniusform" action="{{ route('admin.plans.store') }}" method="POST"
                        enctype="multipart/form-data">

                        @include('includes.admin.form-both')

                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-lg-6 col-xl-4">
                                <label for="inp-title">{{ __('Plan Name') }}</label>
                                <input type="text" class="form-control" id="inp-title" name="title"
                                    placeholder="{{ __('Enter plan name') }}" value="" required>
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <label for="plan_type">{{ __('Plan Type') }}</label>
                                <select name="plan_type" class="form-control" id="plan_type">
                                    <option value="free"> {{ __('Free') }} </option>
                                    <option value="monthly"> {{ __('Monthly') }} </option>
                                    <option value="yearly"> {{ __('Yearly') }} </option>
                                    <option value="lifetime"> {{ __('Life Time') }} </option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <label for="price">{{ __('Price') }} ({{ $currency->name }})</label>
                                <input type="number" class="form-control" id="price" name="price"
                                    placeholder="{{ __('Plan Price') }}" min="0" step="0.01" value="">
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <label for="regular_price">{{ __('Regular Price') }} ({{ $currency->name }})</label>
                                <input type="number" class="form-control" id="regular_price" name="regular_price"
                                    placeholder="{{ __('Regular Price') }}" min="0" step="0.01" value="">
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <label for="sorting">{{ __('Sorting Order') }}</label>
                                <input type="number" class="form-control" id="sorting" name="sorting"
                                    placeholder="0" min="0" step="1" value="0">
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <label for="limit">{{ __('Ad Duration') }} (@lang('Days'))</label>
                                <input type="number" class="form-control" id="limit" name="post_duration"
                                    placeholder="0" min="0" value="">
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <label for="photos">Number of Photos</label>
                                <input type="number" class="form-control" id="photos" name="number_of_photos"
                                    placeholder="0" min="0" value="">
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <label for="keywords">Number of Keywords</label>
                                <input type="number" class="form-control" id="keywords" name="keywords"
                                    placeholder="0" min="0" value="">
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <label for="status">{{ __('Status') }}</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="1"> {{ __('activated') }} </option>
                                    <option value="0"> {{ __('deactivated') }} </option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-xl-4">
                                <label class="d-block">{{ __('Visibility') }}</label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" name="is_private" type="checkbox" value="1"
                                        id="is_private">
                                    <label class="form-check-label" for="is_private">
                                        {{ __('Mark as private plan') }}
                                    </label>
                                </div>
                                <small class="text-muted">{{ __('Private plans are hidden from the frontend package page.') }}</small>
                            </div>
                            <div class="col-12 mb-2">
                                <h6>Choose Plan Features</h6>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="google_map" type="checkbox"
                                                value="1" id="google_map">
                                            <label class="form-check-label" for="google_map">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="google map">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="video_url" type="checkbox"
                                                value="1" id="video_url">
                                            <label class="form-check-label" for="video_url">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="Video Url">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="google_review" type="checkbox"
                                                value="1" id="google_review">
                                            <label class="form-check-label" for="google_review">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="Import google reviews">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="ai_assistant" type="checkbox"
                                                value="1" id="ai_assistant">
                                            <label class="form-check-label" for="ai_assistant">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="AI Assistant">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="features" type="checkbox" value="1"
                                                id="features">
                                            <label class="form-check-label" for="features">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="Featured badge">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="listing" type="checkbox"
                                                value="1" id="listing">
                                            <label class="form-check-label" for="listing">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control"
                                        value="Appear on the top position of the listing page">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="home" type="checkbox"
                                                value="1" id="home">
                                            <label class="form-check-label" for="home">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="Appear at home page">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="follow" type="checkbox"
                                                value="1" id="follow">
                                            <label class="form-check-label" for="follow">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="Do follow attribute">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="analytics" type="checkbox"
                                                value="1" id="analytics">
                                            <label class="form-check-label" for="analytics">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="Analytics">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="indexing" type="checkbox"
                                                value="1" id="indexing">
                                            <label class="form-check-label" for="indexing">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="Urgent indexing">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="notification" type="checkbox"
                                                value="1" id="notification">
                                            <label class="form-check-label" for="notification">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="Monthly Visitors Notification">
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 mb-3">
                                <div class="input-group">
                                    <div class="input-group-text rounded-0 border-right-0">
                                        <div class="form-check d-flex align-items-center justify-content-center rounded-0">
                                            <input class="form-check-input" name="public" type="checkbox"
                                                value="1" id="public">
                                            <label class="form-check-label" for="public">
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" readonly class="form-control" value="Public Profile">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-2 text-right p-0">
                            <div class="featured-keyword-area">
                                <div class="lang-tag-top-filds" id="lang-section">
                                </div>
                                <a href="javascript:;" id="lang-btn" class="add-fild-btn">
                                    <i class="icofont-plus"></i> {{ __('Add More Feature') }}
                                </a>
                            </div>
                        </div>
                        {{-- <input type="hidden" name="manage_schedule_id" value="" id="scheduleId"> --}}
                        <div class="row d-flex justify-content-center pb-5">
                            <div class="col-xl-5">
                                <button type="submit" id="submit-btn" class="btn btn-primary w-100 mt-5">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        "use strict";

        function isEmpty(el) {
            return !$.trim(el.html())
        }


        $("#lang-btn").on('click', function() {

            $("#lang-section").append('' +
                '<div class="lang-area mb-3">' +
                '<span class="remove lang-remove"><i class="fas fa-times"></i></span>' +
                '<div class="row">' +
                '<div class="col-md-12">' +
                '<input type="text" class="form-control" name="attribute[]" placeholder="{{ __('Enter Plan Attribute') }}" value="" required>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '');

        });

        $(document).on('click', '.lang-remove', function() {

            $(this.parentNode).remove();

        });
    </script>
@endsection
