@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="d-sm-flex align-items-center justify-content-between py-3">
    <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Edit Homepage Customization') }} </h5>
    <ol class="breadcrumb py-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="javascript:;">{{ __('Manage Homepage Customization') }}</a></li>
    </ol>
    </div>
</div>

    <div class="card mb-4 mt-3">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Homepage Customization') }}</h6>
      </div>

      <div class="card-body">
        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
        <form class="geniusform" action="{{route('admin.ps.customization.update')}}" method="POST" enctype="multipart/form-data">

            @include('includes.admin.form-both')

            {{ csrf_field() }}

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="home_module[]" value="Banner" {{ $data->homeModuleCheck('Banner') ? 'checked' : '' }} class="custom-control-input" id="Banner">
                  <label class="custom-control-label" for="Banner">{{__('Banner')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Mission" {{ $data->homeModuleCheck('Mission') ? 'checked' : '' }} class="custom-control-input" id="Mission">
                        <label class="custom-control-label" for="Mission">{{__('Mission')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Process" {{ $data->homeModuleCheck('Process') ? 'checked' : '' }} class="custom-control-input" id="Process">
                        <label class="custom-control-label" for="Process">{{__('Process')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Location" {{ $data->homeModuleCheck('Location') ? 'checked' : '' }} class="custom-control-input" id="Location">
                        <label class="custom-control-label" for="Location">{{__('Location')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Submit listing" {{ $data->homeModuleCheck('Submit listing') ? 'checked' : '' }} class="custom-control-input" id="Submitlisting">
                        <label class="custom-control-label" for="Submitlisting">{{__('Submit listing')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Review" {{ $data->homeModuleCheck('Review') ? 'checked' : '' }} class="custom-control-input" id="Review">
                        <label class="custom-control-label" for="Review">{{__('Review')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Author" {{ $data->homeModuleCheck('Author') ? 'checked' : '' }} class="custom-control-input" id="Author">
                        <label class="custom-control-label" for="Author">{{__('Author')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Space" {{ $data->homeModuleCheck('Space') ? 'checked' : '' }} class="custom-control-input" id="Space">
                        <label class="custom-control-label" for="Space">{{__('Space')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Faq" {{ $data->homeModuleCheck('Faq') ? 'checked' : '' }} class="custom-control-input" id="Faq">
                        <label class="custom-control-label" for="Faq">{{__('Faq')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Category" {{ $data->homeModuleCheck('Category') ? 'checked' : '' }} class="custom-control-input" id="Category">
                        <label class="custom-control-label" for="Category">{{__('Category')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Feature Directory" {{ $data->homeModuleCheck('Feature Directory') ? 'checked' : '' }} class="custom-control-input" id="Feature Directory">
                        <label class="custom-control-label" for="Feature Directory">{{__('Feature Directory')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Blogs" {{ $data->homeModuleCheck('Blogs') ? 'checked' : '' }} class="custom-control-input" id="Blogs">
                        <label class="custom-control-label" for="Blogs">{{__('Blogs')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Packages" {{ $data->homeModuleCheck('Packages') ? 'checked' : '' }} class="custom-control-input" id="Packages">
                        <label class="custom-control-label" for="Packages">{{__('Packages')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Partners" {{ $data->homeModuleCheck('Partners') ? 'checked' : '' }} class="custom-control-input" id="Partners">
                        <label class="custom-control-label" for="Partners">{{__('Partners')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="Apps" {{ $data->homeModuleCheck('Apps') ? 'checked' : '' }} class="custom-control-input" id="Apps">
                        <label class="custom-control-label" for="Apps">{{__('Apps')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="home_module[]" value="CTAs" {{ $data->homeModuleCheck('CTAs') ? 'checked' : '' }} class="custom-control-input" id="CTAs">
                        <label class="custom-control-label" for="CTAs">{{__('CTAs')}}</label>
                    </div>
                </div>
            </div>

          </div>


            <button type="submit" id="submit-btn" class="btn btn-primary w-100">{{ __('Submit') }}</button>

        </form>
      </div>
    </div>

@endsection
