@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="d-sm-flex align-items-center py-3 justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Listing submit Section') }}</h5>
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="javascript:;">{{ __('Home Page Setting') }}</a></li>
            </ol>
        </div>
    </div>

    <div class="card mb-4 mt-3">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Listing submit Section') }}</h6>
      </div>

      <div class="card-body">
        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
        <form class="geniusform" action="{{route('admin.ps.update')}}" method="POST" enctype="multipart/form-data">

            @include('includes.admin.form-both')

            {{ csrf_field() }}

            <div class="form-group">
                <label for="listing_title">{{ __('Title') }} *</label>
                <input type="text" class="form-control" id="listing_title" name="listing_title" placeholder="{{ __('Title') }}" value="{{ $ps->listing_title }}" required>
            </div>

            <div class="form-group">
              <label for="listing_subtitle">{{ __('Subtitle') }} *</label>
              <input type="text" class="form-control" id="listing_subtitle" name="listing_subtitle" placeholder="{{ __('Subtitle') }}" value="{{ $ps->listing_subtitle }}" required>
            </div>

            <div class="form-group">
                <label>{{ __('Set Banner Image') }}</label>
                <div class="wrapper-image-preview">
                    <div class="box">
                        <div class="back-preview-image" style="background-image: url({{$ps->listing_photo ? asset('assets/images/'.$ps->listing_photo) : asset('assets/images/default.png') }});"></div>
                        <div class="upload-options">
                            <label class="img-upload-label" for="img-upload"> <i class="fas fa-camera"></i> {{ __('Upload Picture') }} </label>
                            <input id="img-upload" type="file" class="image-upload" name="listing_photo" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="download_text">{{ __('Details') }} *</label>
                <textarea class="form-control summernote" name="listing_details" id="download_text" cols="30" rows="10">{{ $ps->listing_details}}</textarea>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-primary w-100">{{ __('Submit') }}</button>

        </form>
      </div>
    </div>

@endsection
