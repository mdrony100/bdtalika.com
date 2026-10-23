@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center py-3 justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('About Section') }}</h5>
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Page Settings') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="card mb-4 mt-3">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('About Section') }}</h6>
        </div>

        <div class="card-body">
            <div class="gocover"
                style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
            </div>
            <form class="geniusform" action="{{ route('admin.ps.update') }}" method="POST">
                @include('includes.admin.form-both')
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="download_text">{{ __('Details') }} *</label>
                    <textarea class="form-control summernote" name="about_details" id="about_details" cols="30" rows="10">{{ $ps->about_details }}</textarea>
                </div>
                <button type="submit" id="submit-btn" class="btn btn-primary mt-2 w-100">{{ __('Submit') }}</button>

            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
