@extends('layouts.front')
@section('content')
<div class="bg_light px-4 py-2">
<div class="dashboard-tlbar d-block">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted">
<a href="{{ route('front.index') }}">@lang('Home')</a>
</li>
<li class="breadcrumb-item text-muted">
<a href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
</li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<div class="dashboard-wrap px-4 pt-3 pb-5 bg-light">
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
<div class="col-xl-12 col-lg-12">
<div class="dashboard-list-wraps bg-white rounded-3 mb-4 border-0 shadow-sm">
<div class="dashboard-list-wraps-head br-bottom py-3 px-3">
<div class="dashboard-list-wraps-flx">
<h4 class="mb-0 ft-medium fs-md"><i class="fas fa-shopping-basket me-2 theme-cl fs-sm"></i>@lang('All Plans')</h4>
</div>
</div>
<div class="dashboard-list-wraps-body py-3 px-3">
<div class="dashboard-bookings-wraps">
<div class="row">
@foreach ($plans as $key => $data)
<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
<div class="Rego-price-wrap">
<div class="Rego-author-header">
<div class="Rego-price-currency">
<h3>
<span class="Rego-new-price" style="color:{{ $data->price_color }}">{{ globalCurrency()->sign }}<del>{{ showAmount($data->price) }}</del></span>
@if ($data->prev_price > 0)
<span class="Rego-old-price">{{ globalCurrency()->sign }}<del>{{ showAmount($data->prev_price) }}</del></span>
@endif
</h3>
</div>
<div class="Rego-price-title">
<div class="Rego-price-tlt">
<h4>{{ $data->title }}</h4>
</div>
</div>
<div class="Rego-price-subtitle">{{ $data->subtitle }}</div>
</div>
<div class="Rego-price-body">
<ul class="price__features">
@if ($data->attribute != null)
@foreach (json_decode($data->attribute) as $key => $attribute)
<li><i class="fa fa-angle-right"></i>{{ $attribute }}
</li>
@endforeach
@endif
</ul>
</div>
<div class="Rego-price-bottom">
@if (auth()->user()->plan_id == $data->id)
<a class="Rego-price-btn" href="javascript:;">
@lang('Current Plan')
</a>
<div class="text-end mt-2">
({{ Carbon\Carbon::parse(auth()->user()->plan_end_date) ? Carbon\Carbon::parse(auth()->user()->plan_end_date)->toDateString() : '' }})
<a href="{{ route('user.package.subscription', $data->id) }}"
class="text--base">@lang('Renew Plan')</a>
</div>
@else
<a class="Rego-price-btn"
href="{{ route('user.package.subscription', $data->id) }}">
<i class="fas fa-shopping-basket"></i>
@lang('Purchase Now')
</a>
@endif
</div>
</div>
</div>
@endforeach
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
@endsection

