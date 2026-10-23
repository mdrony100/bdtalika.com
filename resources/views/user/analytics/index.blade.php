@extends('layouts.front')

@push('css')
<style>
.analytics-shell {max-width:1180px;}
.analytics-hero {background:#111827;color:#fff;border-radius:8px;padding:24px;display:flex;justify-content:space-between;gap:20px;align-items:center;}
.analytics-hero h1 {font-size:24px;line-height:1.2;margin:0;color:#fff;font-weight:700;}
.analytics-hero p {margin:8px 0 0;color:#cbd5e1;}
.analytics-tabs {display:flex;gap:8px;flex-wrap:wrap;}
.analytics-tabs a {padding:9px 16px;border-radius:6px;background:#fff;color:#334155;font-weight:600;}
.analytics-tabs a.active {background:#1768E0;color:#fff;}
.metric-grid {display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-top:16px;}
.metric-card {background:#fff;border:1px solid #e7ebf2;border-radius:8px;padding:18px;box-shadow:0 6px 18px rgba(15,23,42,.04);}
.metric-card span {display:block;color:#64748b;font-size:13px;font-weight:600;margin-bottom:8px;}
.metric-card strong {display:block;color:#0f172a;font-size:28px;line-height:1;font-weight:800;}
.metric-card i {float:right;color:#1768E0;font-size:22px;margin-top:3px;}
.analytics-panel {background:#fff;border:1px solid #e7ebf2;border-radius:8px;margin-top:16px;box-shadow:0 6px 18px rgba(15,23,42,.04);}
.analytics-panel-head {padding:18px 20px;border-bottom:1px solid #eef1f5;display:flex;justify-content:space-between;align-items:center;}
.analytics-panel-head h2 {font-size:18px;margin:0;font-weight:700;color:#172033;}
.analytics-chart {display:grid;grid-template-columns:repeat(auto-fit,minmax(80px,1fr));gap:12px;padding:20px;align-items:end;min-height:210px;}
.chart-day {display:flex;flex-direction:column;gap:7px;justify-content:flex-end;height:170px;}
.chart-bars {display:flex;gap:4px;align-items:end;justify-content:center;height:130px;}
.chart-bar {width:12px;border-radius:4px 4px 0 0;min-height:4px;}
.chart-bar.views {background:#1768E0;}
.chart-bar.clicks {background:#13a56b;}
.chart-bar.messages {background:#f59e0b;}
.chart-bar.impressions {background:#7c3aed;}
.chart-label {font-size:12px;color:#64748b;text-align:center;}
.analytics-table {width:100%;border-collapse:collapse;}
.analytics-table th {font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:#64748b;background:#f8fafc;padding:13px 16px;}
.analytics-table td {padding:15px 16px;border-top:1px solid #eef1f5;color:#172033;}
.analytics-table td:not(:first-child),.analytics-table th:not(:first-child) {text-align:center;}
.directory-name {font-weight:700;}
.empty-analytics {padding:36px;text-align:center;color:#64748b;}
@media screen and (max-width:991px){.metric-grid{grid-template-columns:repeat(2,minmax(0,1fr));}.analytics-hero{display:block;}.analytics-tabs{margin-top:16px;}}
@media screen and (max-width:575px){.metric-grid{grid-template-columns:1fr;}.analytics-table{min-width:780px;}.analytics-table-wrap{overflow:auto;}}
</style>
@endpush

@section('content')
<div class="bg_light px-4 py-2">
<div class="dashboard-tlbar d-block">
<div class="row">
<div class="colxl-12 col-lg-12 col-md-12">
<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item text-muted"><a href="{{ route('front.index') }}" class="color_link">@lang('Home')</a></li>
<li class="breadcrumb-item"><a href="{{ route('user.listing.analytics') }}">@lang('Analytics')</a></li>
</ol>
</nav>
</div>
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
<div class="dashboard-widg-bar analytics-shell">
<div class="analytics-hero">
<div>
<h1>@lang('Business Analytics')</h1>
<p>@lang('Track how visitors interact with your directory listings.')</p>
</div>
<div class="analytics-tabs">
<a href="{{ route('user.listing.analytics', ['range' => 'daily']) }}" class="{{ $range == 'daily' ? 'active' : '' }}">@lang('Daily')</a>
<a href="{{ route('user.listing.analytics', ['range' => 'weekly']) }}" class="{{ $range == 'weekly' ? 'active' : '' }}">@lang('Weekly')</a>
<a href="{{ route('user.listing.analytics', ['range' => 'monthly']) }}" class="{{ $range == 'monthly' ? 'active' : '' }}">@lang('Monthly')</a>
<a href="{{ route('user.listing.analytics', ['range' => 'all']) }}" class="{{ $range == 'all' ? 'active' : '' }}">@lang('All Time')</a>
</div>
</div>

<div class="metric-grid">
<div class="metric-card"><i class="lni lni-bullhorn"></i><span>@lang('Impressions')</span><strong>{{ $totals['impressions'] }}</strong></div>
<div class="metric-card"><i class="lni lni-eye"></i><span>@lang('Total Views')</span><strong>{{ $totals['views'] }}</strong></div>
<div class="metric-card"><i class="lni lni-users"></i><span>@lang('Unique Visitors')</span><strong>{{ $totals['unique_visitors'] }}</strong></div>
<div class="metric-card"><i class="lni lni-phone"></i><span>@lang('Phone Clicks')</span><strong>{{ $totals['phone'] }}</strong></div>
<div class="metric-card"><i class="fab fa-whatsapp"></i><span>@lang('WhatsApp Clicks')</span><strong>{{ $totals['whatsapp'] }}</strong></div>
<div class="metric-card"><i class="lni lni-world"></i><span>@lang('Website Clicks')</span><strong>{{ $totals['website'] }}</strong></div>
<div class="metric-card"><i class="lni lni-envelope"></i><span>@lang('Customer Message')</span><strong>{{ $totals['messages'] }}</strong></div>
<div class="metric-card"><i class="lni lni-envelope"></i><span>@lang('Email Clicks')</span><strong>{{ $totals['email'] }}</strong></div>
</div>

<div class="analytics-panel">
<div class="analytics-panel-head">
<h2>@lang('Statistics')</h2>
<div class="small text-muted">@lang('Impressions') / @lang('Views') / @lang('Clicks') / @lang('Messages')</div>
</div>
<div class="analytics-chart">
@php $maxChart = max(1, $chart->max(fn($item) => max($item['impressions'], $item['views'], $item['clicks'], $item['messages']))); @endphp
@foreach ($chart as $item)
<div class="chart-day">
<div class="chart-bars">
<div class="chart-bar impressions" style="height: {{ max(4, ($item['impressions'] / $maxChart) * 120) }}px"></div>
<div class="chart-bar views" style="height: {{ max(4, ($item['views'] / $maxChart) * 120) }}px"></div>
<div class="chart-bar clicks" style="height: {{ max(4, ($item['clicks'] / $maxChart) * 120) }}px"></div>
<div class="chart-bar messages" style="height: {{ max(4, ($item['messages'] / $maxChart) * 120) }}px"></div>
</div>
<div class="chart-label">{{ $item['label'] }}</div>
</div>
@endforeach
</div>
</div>

<div class="analytics-panel">
<div class="analytics-panel-head">
<h2>@lang('Directory Performance')</h2>
</div>
<div class="analytics-table-wrap">
<table class="analytics-table">
<thead>
<tr>
<th>@lang('Directory Name')</th>
<th>@lang('Impressions')</th>
<th>@lang('Views')</th>
<th>@lang('Unique')</th>
<th>@lang('Phone')</th>
<th>@lang('WhatsApp')</th>
<th>@lang('Website')</th>
<th>@lang('Messages')</th>
<th>@lang('Email')</th>
</tr>
</thead>
<tbody>
@forelse ($rows as $row)
<tr>
<td class="directory-name">{{ $row['listing']->name }}</td>
<td>{{ $row['impressions'] }}</td>
<td>{{ $row['views'] }}</td>
<td>{{ $row['unique_visitors'] }}</td>
<td>{{ $row['phone'] }}</td>
<td>{{ $row['whatsapp'] }}</td>
<td>{{ $row['website'] }}</td>
<td>{{ $row['messages'] }}</td>
<td>{{ $row['email'] }}</td>
</tr>
@empty
<tr>
<td colspan="9">
<div class="empty-analytics">@lang('No Data Found')</div>
</td>
</tr>
@endforelse
</tbody>
</table>
</div>
@if ($rows->hasPages())
<div class="px-3 py-3 border-top">
{{ $rows->links() }}
</div>
@endif
</div>
</div>
</div>
</div>
</div>
@endsection
