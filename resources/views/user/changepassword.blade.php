@extends('layouts.front')
@section('content')
<div class="bg_light px-4 py-2">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a></li>
<li class="breadcrumb-item"><a href="#" class="">@lang('Change Password')</a></li>
</ol>
</nav>
</div>
</div>
</div>
<div class="dashboard-wrap px-4 pt-3 pb-5 bg-light">
<a class="mobNavigation" data-bs-toggle="collapse" href="#MobNav" role="button" aria-expanded="false" aria-controls="MobNav">
<i class="fas fa-bars me-2"></i>@lang('Dashboard Navigation')
</a>
<div class="collapse" id="MobNav">
@includeIf('partials.user.sidebar')
</div>
<div class="dashboard-content">
<div class="dashboard-widg-bar d-block">
<div class="row">
<div class="col-xl-12 col-lg-12 col-md-12">
<div class="_dashboard_content bg-white rounded mb-4 border">
<div class="_dashboard_content_header br-bottom py-3 px-3">
<div class="_dashboard__header_flex">
<h4 class="mb-0 ft-medium fs-md"><i class="fa fa-lock me-2 theme-cl fs-sm"></i>@lang("Change Password")</h4>
</div>
</div>
<div class="_dashboard_content_body py-3 px-3">
<form class="row submit-form" action="{{ route('user.change.password') }}" method="POST" enctype="multipart/form-data">
@csrf
@includeIf('includes.flash')
<div class="col-xl-8 col-lg-9 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang("Old Password")</label>
<input type="password" name="cpass" class="form-control rounded" placeholder="@lang('Current Password')" required>
</div>
</div>
<div class="col-xl-8 col-lg-9 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang("New Password")</label>
<input type="password" name="newpass" class="form-control rounded" placeholder="@lang('New Password')" required>
</div>
</div>
<div class="col-xl-8 col-lg-9 col-md-12 col-sm-12">
<div class="form-group mb-3">
<label class="form-label">@lang("Confirm Password")</label>
<input type="password" name="renewpass" class="form-control rounded" placeholder="@lang('Confirm Password')" required>
</div>
</div>
<div class="col-xl-12 col-lg-12">
<div class="form-group mb-3">
<button type="submit" class="btn btn-md ft-medium text-light rounded theme-bg">@lang("Save Changes")</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

@endsection
@push('js')
@endpush
