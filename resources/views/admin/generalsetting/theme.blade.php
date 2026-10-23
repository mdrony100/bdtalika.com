@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="d-sm-flex align-items-center justify-content-between py-3">
      <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Manage Theme') }}</h5>
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:;">{{ __('General Settings') }}</a></li>
        </ol>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-6">
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('Theme 1') }}</h6>
        </div>

        <div class="card-body">
          <div class="row justify-content-center">

          <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
            <form action="{{ route('admin.gs.update') }}" method="POST" enctype="multipart/form-data">
                @include('includes.admin.form-both')

                {{ csrf_field() }}

                <div class="form-group">
                    <div class="wrapper-image-preview-settings">
                        <div class="box-settings">
                            <div class="back-preview-image" style="background-image: url({{ asset('assets/images/1.png') }});"></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="theme" value="theme-1">
                @if ($gs->theme == 'theme-1')
                    <button type="submit" class="btn btn-secondary btn-block" disabled>{{ __('Selected') }}</button>
                @else
                    <button type="submit" id="submit-btn" class="btn btn-primary btn-block">{{ __('Select as active') }}</button>
                @endif

            </form>
        </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('Theme 2') }}</h6>
        </div>

        <div class="card-body">
          <div class="row justify-content-center">

          <form action="{{ route('admin.gs.update') }}" method="POST" enctype="multipart/form-data">
              @include('includes.admin.form-both')

              {{ csrf_field() }}

              <div class="form-group">
                  <div class="wrapper-image-preview-settings">
                      <div class="box-settings">
                        <div class="back-preview-image" style="background-image: url({{ asset('assets/images/2.png') }});"></div>
                      </div>
                  </div>
              </div>
              <input type="hidden" name="theme" value="theme-2">
                @if ($gs->theme == 'theme-2')
                    <button type="submit" class="btn btn-secondary btn-block" disabled>{{ __('Selected') }}</button>
                @else
                    <button type="submit" id="submit-btn" class="btn btn-primary btn-block">{{ __('Select as active') }}</button>
                @endif

          </form>
        </div>
        </div>
      </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-6">
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('Theme 3') }}</h6>
        </div>

        <div class="card-body">
          <div class="row justify-content-center">

          <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
            <form action="{{ route('admin.gs.update') }}" method="POST" enctype="multipart/form-data">
                @include('includes.admin.form-both')

                {{ csrf_field() }}
                <div class="form-group">
                    <div class="wrapper-image-preview-settings">
                        <div class="box-settings">
                            <div class="back-preview-image" style="background-image: url({{ asset('assets/images/3.png') }});"></div>
                        </div>
                    </div>
                </div>
                    <input type="hidden" name="theme" value="theme-3">
                    @if ($gs->theme == 'theme-3')
                        <button type="submit" class="btn btn-secondary btn-block" disabled>{{ __('Selected') }}</button>
                    @else
                        <button type="submit" id="submit-btn" class="btn btn-primary btn-block">{{ __('Select as active') }}</button>
                    @endif

            </form>
        </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('Theme 4') }}</h6>
        </div>

        <div class="card-body">
            <div class="row justify-content-center">
                <form action="{{ route('admin.gs.update') }}" method="POST" enctype="multipart/form-data">
                    @include('includes.admin.form-both')

                    {{ csrf_field() }}

                    <div class="form-group">
                        <div class="wrapper-image-preview-settings">
                            <div class="box-settings">
                                <div class="back-preview-image" style="background-image: url({{ asset('assets/images/4.png') }});"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="theme" value="theme-4">
                    @if ($gs->theme == 'theme-4')
                        <button type="submit" class="btn btn-secondary btn-block" disabled>{{ __('Selected') }}</button>
                    @else
                        <button type="submit" id="submit-btn" class="btn btn-primary btn-block">{{ __('Select as active') }}</button>
                    @endif

                </form>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="row d-plex justify-content-center mt-3">
    <div class="col-lg-6">
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">{{ __('Theme 5') }}</h6>
        </div>

        <div class="card-body">
          <div class="row justify-content-center">

          <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
          <form action="{{ route('admin.gs.update') }}" method="POST" enctype="multipart/form-data">
              @include('includes.admin.form-both')

              {{ csrf_field() }}

              <div class="form-group">
                  <div class="wrapper-image-preview-settings">
                      <div class="box-settings">
                          <div class="back-preview-image" style="background-image: url({{ asset('assets/images/5.png') }});"></div>
                      </div>
                  </div>
              </div>
              <input type="hidden" name="theme" value="theme-5">
                @if ($gs->theme == 'theme-5')
                    <button type="submit" class="btn btn-secondary btn-block" disabled>{{ __('Selected') }}</button>
                @else
                    <button type="submit" id="submit-btn" class="btn btn-primary btn-block">{{ __('Select as active') }}</button>
                @endif

          </form>
        </div>
        </div>
      </div>
    </div>
</div>

@endsection
