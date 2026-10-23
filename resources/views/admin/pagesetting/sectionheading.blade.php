@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="d-sm-flex align-items-center justify-content-between py-3">
        <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Section Heading') }}</h5>
        <ol class="breadcrumb py-0 m-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:;">{{ __('Home Page Manage') }}</a></li>
        </ol>
    </div>
</div>

<div class="card mb-4 mt-3">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Section Heading') }}</h6>
    </div>

    <div class="card-body">
        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
        <form class="geniusform" action="{{route('admin.ps.update')}}" method="POST" enctype="multipart/form-data">

            @include('includes.admin.form-both')

            {{ csrf_field() }}

            <div class="form-group">
                <label for="cat-title">{{ __('Category Title') }} *</label>
                <input type="text" class="form-control" id="cat-title" name="category_title"  placeholder="{{ __('Category Title') }}" value="{{ $data->category_title }}" required>
            </div>

            <div class="form-group">
                <label for="banner-subtitle">{{ __('Category Subtitle') }} *</label>
                <input type="text" class="form-control" id="cat-subtitle" name="category_subtitle"  placeholder="{{ __('Category subtitle') }}" value="{{ $data->category_subtitle }}" required>
            </div>

            <div class="form-group">
                <label for="explore-title">{{ __('Directory Title') }} *</label>
                <input type="text" class="form-control" id="explore-title" name="directory_title"  placeholder="{{ __('Directory Title') }}" value="{{ $data->directory_title }}" required>
            </div>

            <div class="form-group">
                <label for="explore-subtitle">{{ __('Directory Subtitle') }} *</label>
                <input type="text" class="form-control" id="explore-subtitle" name="directory_subtitle"  placeholder="{{ __('Directory Title') }}" value="{{ $data->directory_subtitle }}" required>
            </div>

            <div class="form-group">
                <label for="blog-title">{{ __('Blog Title') }} *</label>
                <input type="text" class="form-control" id="blog-title" name="blog_title"  placeholder="{{ __('Blog Title') }}" value="{{ $data->blog_title }}" required>
            </div>

            <div class="form-group">
                <label for="blog-subtitle">{{ __('Blog Subtitle') }} *</label>
                <input type="text" class="form-control" id="blog-subtitle" name="blog_subtitle"  placeholder="{{ __('Blog Subtitle') }}" value="{{ $data->blog_subtitle }}" required>
            </div>

            <div class="form-group">
                <label for="partner-title">{{ __('Partner Title') }} *</label>
                <input type="text" class="form-control" id="partner-title" name="partner_title"  placeholder="{{ __('Partner Title') }}" value="{{ $data->partner_title }}" required>
            </div>

            <div class="form-group">
                <label for="partner-subtitle">{{ __('Partner Subtitle') }} *</label>
                <input type="text" class="form-control" id="partner-subtitle" name="partner_subtitle"  placeholder="{{ __('Partner Subtitle') }}" value="{{ $data->partner_subtitle }}" required>
            </div>

            <div class="form-group">
                <label for="plan-title">{{ __('Plan Title') }} *</label>
                <input type="text" class="form-control" id="plan-title" name="plan_title"  placeholder="{{ __('Plan Title') }}" value="{{ $data->plan_title }}" required>
            </div>

            <div class="form-group">
                <label for="plan-subtitle">{{ __('Plan Subtitle') }} *</label>
                <input type="text" class="form-control" id="plan-subtitle" name="plan_subtitle"  placeholder="{{ __('Plan Subtitle') }}" value="{{ $data->plan_subtitle }}" required>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-primary w-100">{{ __('Submit') }}</button>

        </form>
    </div>
</div>
@endsection
