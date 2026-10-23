@extends('layouts.front')
@push('meta_info')
<title>National Holidays in Bangladesh, {{date('Y')}} Holidays in Calendar </title>
<meta property="og:title" content="{{date('Y')}} Public Holidays in Bangladesh">
<meta name="description" content="{{date('Y')}} Public Holidays in Bangladesh, Calendar of Bangladesh Public Holidays, {{date('Y')}} Calendar with Public Holidays in Bangladesh, National Public Holidays in Bangladesh {{date('Y')}}.">
<meta property="og:description" content="Guide to all {{date('Y')}} Public Holidays in Bangladesh including public holidays and regional holidays.">
@endpush
@push('css')
<style>
.msg2 h4 {color: #fff;background: #0dcaf0;line-height: 30px;font-size: 14px;border-radius: 4px;text-align: center;margin-bottom: 20px;padding: 2px 21px;display: inline-block;font-weight: 500;}
.desc table th {padding: 15px 10px;font-size: 16px;background: #fff;font-weight: 700;}
.desc table th,
.desc table td {text-align: left;border-top: solid 1px #E2E7E2;padding: 10px;background: #ffffff; font-size: 16px;}
.company .v, .vv {background: #5fd080;padding: 0px 5px;color: #fff;font-size: 12px;line-height: 22px;text-decoration: none;border: solid 1px #5fd080;display: inline-block;margin: 4px 0px;border-radius: 3px;}
</style>
@endpush
@section('content')
<div class="bg-light py-2">
<div class="container">
<div class="row">
<div class="colxl-12 colz-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="{{ route('front.index') }}">@lang('Home')</a></li>
<li class="breadcrumb-item"><a href="#">@lang('Public Holidays')</a></li>
</ol>
</nav>
</div>
</div>
</div>
</div>
<section class="pb-5 pt-4 pt-1 position-relative">
<div class="container">
<div class="mb-4">
<h1 class="fs-4"><strong>Public Holidays in Bangladesh</strong></h1>
<p class="fs-6">Guide to all 2024 Public Holidays in Bangladesh including public holidays and regional holidays.</p>
</div>
<div class="row gy-4">
<div class="col-md-6">
<div class="review msg2  rounded gray p-4">
<h4 class="{{ $isHolidayToday ? 'bg-success' : 'bg-danger' }}"> TODAY </h4>
<h6>
<b>{{ $today->format('l') }}</b> | {{ $today->format('d F Y') }}
<br>
<span class="text-{{ $isHolidayToday ? 'success' : 'danger' }} d-block mt-2"> {{ $isHolidayToday ? 'is' : 'is not' }} a holiday in Bangladesh </span>
</h6>
</div>
</div>
<div class="col-md-6">
<div class="review msg2  rounded gray p-4">
<h4 class="bg-success">UPCOMING HOLIDAY</h4>
<h6>
@if($nextHoliday)
    <b>{{ \Carbon\Carbon::parse($nextHoliday->date)->format('l') }}</b> | 
    {{ \Carbon\Carbon::parse($nextHoliday->date)->format('d F Y') }}
@else
    <b>No upcoming holidays</b>
@endif
<br>
<span class="text-success d-block mt-2">{{ $nextHoliday->name ?? "" }}</span>
</h6>
</div>
</div>
</div>
<div class="mt-5">
<h5><strong>Public Holidays Bangladesh 2024</strong></h5>
<p>This table contains a national calendar of all 2024 public holidays in Bangladesh. These dates may be
modified as official changes are announced, so please check back regularly for updates.</p>
<div class="desc table-responsive">
<table class="table table-bordered">
<tbody>
<tr>
<th>Date</th>
<th>Holiday</th>
</tr>
@foreach ($holidays as $holiday)
<tr>
<td class="nowrap">{{ \Carbon\Carbon::parse($holiday->date)->format('F d') }} <small class="gray">{{ \Carbon\Carbon::parse($holiday->date)->format('l') }}</small></td>
<td>
<b>{{ $holiday->name }}</b>
<div><span class="vv r_3px">{{ $holiday->type }}<span></span></span></div>
<div><small>{{ $holiday->text }}</small></div>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div>
</section>
@endsection

