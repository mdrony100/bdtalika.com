@extends('layouts.front')

@push('css')

@endpush

@section('content')
<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('front.index') }}">@lang('Home')</a></li>
<li class="breadcrumb-item active theme-cl" aria-current="page">@lang('Pricing')</li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<section class="space min gray">
<div class="container">

<div class="row justify-content-center">
<div class="col-xl-5 col-lg-7 col-md-9 col-sm-12">
<div class="sec_title position-relative text-center mb-5">
<h6 class="theme-cl mb-0">{{ $ps->plan_title }}</h6>
<h2 class="ft-bold">{{ $ps->plan_subtitle }}</h2>
</div>
</div>
</div>

<div class="row">
@foreach ($plans as $key => $data)
<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
<div class="Rego-price-wrap">
<div class="Rego-author-header">
<div class="Rego-price-currency">
<h3>
<span class="Rego-new-price" style="color:{{ $data->price_color}}">{{ globalCurrency()->sign}}<del>{{ showAmount($data->price) }}</del></span>
@if ($data->prev_price>0)
<span class="Rego-old-price">{{ globalCurrency()->sign}}<del>{{ showAmount($data->prev_price) }}</del></span>
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
@if ($data->attribute != NULL)
@foreach (json_decode($data->attribute) as $key => $attribute)
<li><i class="fa fa-angle-right"></i>{{ $attribute }}</li>
@endforeach
@endif
</ul>
</div>
<div class="Rego-price-bottom">
<a class="Rego-price-btn" href="{{ route('user.package.subscription',$data->id) }}"><i class="fas fa-shopping-basket"></i>@lang('Purchase Now')</a>
</div>
</div>
</div>
@endforeach
</div>

</div>
</section>

@endsection

@push('js')

@endpush
