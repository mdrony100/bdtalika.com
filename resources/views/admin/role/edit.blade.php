@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="d-sm-flex align-items-center justify-content-between py-3">
    <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Edit Role') }} <a class="btn btn-primary btn-rounded btn-sm" href="{{route('admin.role.index')}}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h5>
    <ol class="breadcrumb py-0 m-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="javascript:;">{{ __('Roles') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}">{{ __('Manage Roles') }}</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.role.edit',$data->id)}}">{{ __('Edit Role') }}</a></li>
    </ol>
    </div>
</div>

<div class="row justify-content-center mt-3">
  <div class="col-md-10">
    <!-- Form Basic -->
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Edit Role Form') }}</h6>
      </div>

      <div class="card-body">
        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
        <form class="geniusform" action="{{route('admin.role.update',$data->id)}}" method="POST" enctype="multipart/form-data">

            @include('includes.admin.form-both')

            {{ csrf_field() }}

            <div class="form-group">
              <label for="inp-name">{{ __('Role Name') }}</label>
              <input type="text" class="form-control" id="inp-name" name="name"  placeholder="{{ __('Enter Role Name') }}" value="{{$data->name}}" required>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Menu Builder" {{ $data->sectionCheck('Menu Builder') ? 'checked' : '' }} class="custom-control-input" id="menu_builder">
                  <label class="custom-control-label" for="menu_builder">{{__('Menu Builder')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Manage Plan" {{ $data->sectionCheck('Manage Plan') ? 'checked' : '' }} class="custom-control-input" id="manage_plann">
                    <label class="custom-control-label" for="manage_plann">{{__('Manage Plan')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Categories" {{ $data->sectionCheck('Categories') ? 'checked' : '' }} class="custom-control-input" id="Categories">
                    <label class="custom-control-label" for="Categories">{{__('Categories')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Amenities" {{ $data->sectionCheck('Amenities') ? 'checked' : '' }} class="custom-control-input" id="Amenities">
                    <label class="custom-control-label" for="Amenities">{{__('Amenities')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Locations" {{ $data->sectionCheck('Locations') ? 'checked' : '' }} class="custom-control-input" id="Locations">
                    <label class="custom-control-label" for="Locations">{{__('Locations')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Directory Listing" {{ $data->sectionCheck('Directory Listing') ? 'checked' : '' }} class="custom-control-input" id="Directory Listing">
                    <label class="custom-control-label" for="Directory Listing">{{__('Directory Listing')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Directory Reviews" {{ $data->sectionCheck('Directory Reviews') ? 'checked' : '' }} class="custom-control-input" id="Directory Reviews">
                    <label class="custom-control-label" for="Directory Reviews">{{__('Directory Reviews')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Booking Request" {{ $data->sectionCheck('Booking Request') ? 'checked' : '' }} class="custom-control-input" id="Booking Request">
                    <label class="custom-control-label" for="Booking Request">{{__('Booking Request')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Manage Customers" {{ $data->sectionCheck('Manage Customers') ? 'checked' : '' }} class="custom-control-input" id="manage_customers">
                  <label class="custom-control-label" for="manage_customers">{{__('Manage Customers')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="CRM Leads" {{ $data->sectionCheck('CRM Leads') ? 'checked' : '' }} class="custom-control-input" id="crm_leads">
                  <label class="custom-control-label" for="crm_leads">{{__('CRM - Leads')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Transactions" {{ $data->sectionCheck('Transactions') ? 'checked' : '' }} class="custom-control-input" id="transactions">
                  <label class="custom-control-label" for="transactions">{{__('Transactions')}}</label>
                  </div>
              </div>
            </div>


            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Manage Blog" {{ $data->sectionCheck('Manage Blog') ? 'checked' : '' }} class="custom-control-input" id="manage_blog">
                  <label class="custom-control-label" for="manage_blog">{{__('Manage Blog')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="General Settings" {{ $data->sectionCheck('General Settings') ? 'checked' : '' }} class="custom-control-input" id="general_setting">
                  <label class="custom-control-label" for="general_setting">{{__('General Setting')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Home Page Manage" {{ $data->sectionCheck('Home Page Manage') ? 'checked' : '' }} class="custom-control-input" id="homepage_setting">
                  <label class="custom-control-label" for="homepage_setting">{{__('Home Page Manage')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Email Settings" {{ $data->sectionCheck('Email Settings') ? 'checked' : '' }} class="custom-control-input" id="email_setting">
                  <label class="custom-control-label" for="email_setting">{{__('Email Settings')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="SMS Settings" {{ $data->sectionCheck('SMS Settings') ? 'checked' : '' }} class="custom-control-input" id="sms_setting">
                    <label class="custom-control-label" for="sms_setting">{{__('SMS Settings')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="section[]" value="Message" {{ $data->sectionCheck('Message') ? 'checked' : '' }} class="custom-control-input" id="Message">
                        <label class="custom-control-label" for="Message">{{__('Message')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Payment Settings" {{ $data->sectionCheck('Payment Settings') ? 'checked' : '' }} class="custom-control-input" id="payment_setting">
                    <label class="custom-control-label" for="payment_setting">{{__('Payment Settings')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="section[]" value="Manage Roles" {{ $data->sectionCheck('Manage Roles') ? 'checked' : '' }} class="custom-control-input" id="manage_roles">
                        <label class="custom-control-label" for="manage_roles">{{__('Manage Roles')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Manage Staff" {{ $data->sectionCheck('Manage Staff') ? 'checked' : '' }} class="custom-control-input" id="manage_staff">
                  <label class="custom-control-label" for="manage_staff">{{__('Manage Staff')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Manage KYC Form" {{ $data->sectionCheck('Manage KYC Form') ? 'checked' : '' }} class="custom-control-input" id="manage_kyc">
                    <label class="custom-control-label" for="manage_kyc">{{__('Manage KYC Form')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Language Manage" {{ $data->sectionCheck('Language Manage') ? 'checked' : '' }} class="custom-control-input" id="language_setting">
                    <label class="custom-control-label" for="language_setting">{{__('Language Manage')}}</label>
                    </div>
                </div>
              </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Fonts" {{ $data->sectionCheck('Fonts') ? 'checked' : '' }} class="custom-control-input" id="font">
                    <label class="custom-control-label" for="font">{{__('Fonts')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="section[]" value="Menu Page Settings" {{ $data->sectionCheck('Menu Page Settings') ? 'checked' : '' }} class="custom-control-input" id="menupage_setting">
                    <label class="custom-control-label" for="menupage_setting">{{__('Menu Page Settings')}}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Seo Tools" {{ $data->sectionCheck('Seo Tools') ? 'checked' : '' }} class="custom-control-input" id="seo_tools">
                  <label class="custom-control-label" for="seo_tools">{{__('Seo Tools')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Sitemaps" {{ $data->sectionCheck('Sitemaps') ? 'checked' : '' }} class="custom-control-input" id="Sitemaps">
                  <label class="custom-control-label" for="Sitemaps">{{__('Sitemaps')}}</label>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <div class="custom-control custom-switch">
                  <input type="checkbox" name="section[]" value="Subscribers" {{ $data->sectionCheck('Subscribers') ? 'checked' : '' }} class="custom-control-input" id="subscribers">
                  <label class="custom-control-label" for="subscribers">{{__('Subscribers')}}</label>
                  </div>
              </div>
            </div>

            <button type="submit" id="submit-btn" class="btn btn-primary w-100">{{ __('Submit') }}</button>

        </form>
      </div>
    </div>
  </div>

</div>
<!--Row-->

@endsection
