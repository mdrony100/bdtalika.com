@extends('layouts.front')

@push('css')

@endpush

@section('content')
<!-- =============================== Dashboard Header ========================== -->
{{-- @includeIf('partials.user.header') --}}
<!-- =============================== Dashboard Header ========================== -->

<!-- ======================= dashboard Detail ======================== -->
<div class="dashboard-tlbar d-block">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
{{-- <h1 class="ft-medium">@lang('Analytics Details')</h1> --}}
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a></li>
<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="">@lang('Dashboard')</a></li>
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
<div class="table-responsive table--mobile-lg">
<table class="table bg--body">
<thead>
<tr>
<th>@lang('No')</th>
<th>@lang('Device')</th>
<th>@lang('Browser')</th>
<th>@lang('Operating System')</th>
<th>@lang('Visited At')</th>
</tr>
</thead>
<tbody>
@if (count($listings) == 0)
<tr>
<td colspan="12">
<h4 class="text-center m-0 py-2">{{__('No Data Found')}}</h4>
</td>
</tr>
@else
@foreach($listings as $key=>$data)

<tr>
<td data-label="@lang('No')">
<div>

<span class="text-muted">{{ $loop->iteration }}</span>
</div>
</td>

<td data-label="@lang('Device')">
<div>
{{ strtoupper($data->device) }}
</div>
</td>

<td data-label="@lang('Browser')">
<div>
{{ $data->browser }}
</div>
</td>

<td data-label="@lang('Operating System')">
<div>
{{ $data->operating_system }}
</div>
</td>

<td data-label="@lang('Date')">
<div>
{{date('d M Y',strtotime($data->created_at))}}
</div>
</td>
</tr>
@endforeach
@endif
</tbody>
</table>
</div>
{{ $listings->links() }}

</div>

<!-- footer -->
{{-- <div class="row">
<div class="col-md-12">
<div class="py-3">
@php
echo $gs->copyright;
@endphp
</div>
</div>
</div> --}}

</div>

</div>
<!-- ======================= dashboard Detail End ======================== -->

@endsection

@push('js')

@endpush
