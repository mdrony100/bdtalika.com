@extends('layouts.admin')

@section('content')
<style>
.alist-tabs {display:flex;gap:6px;}
.alist-tabs a {padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;color:#495057;background:#f1f3f7;text-decoration:none;}
.alist-tabs a.active {background:#21318c;color:#fff;}
.alist-chart {display:flex;gap:8px;align-items:flex-end;min-height:190px;padding:18px;overflow-x:auto;}
.alist-col {flex:1;min-width:34px;display:flex;flex-direction:column;gap:6px;justify-content:flex-end;}
.alist-bars {display:flex;gap:3px;align-items:flex-end;justify-content:center;height:130px;}
.alist-bar {width:12px;border-radius:3px 3px 0 0;min-height:3px;}
.alist-bar.impressions {background:#7c3aed;}
.alist-bar.views {background:#1768E0;}
.alist-bar.clicks {background:#13a56b;}
.alist-label {font-size:12px;color:#6c757d;text-align:center;}
.alist-legend {font-size:12px;color:#6c757d;padding:0 18px 12px;}
.alist-dot {display:inline-block;width:10px;height:10px;border-radius:2px;margin:0 4px 0 10px;}
</style>
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class=" mb-0 text-gray-800 pl-3">{{ __('Listing Analytics') }} - {{ $listing->name }}</h5>
            <div class="d-flex align-items-center">
                <div class="alist-tabs mr-3">
                    <a href="{{ route('admin.listing.byviews.analytics', $listing->id) }}?range=monthly" class="{{ $range == 'monthly' ? 'active' : '' }}">{{ __('This Month') }}</a>
                    <a href="{{ route('admin.listing.byviews.analytics', $listing->id) }}?range=yearly" class="{{ $range == 'yearly' ? 'active' : '' }}">{{ __('This Year') }}</a>
                    <a href="{{ route('admin.listing.byviews.analytics', $listing->id) }}?range=all" class="{{ $range == 'all' ? 'active' : '' }}">{{ __('All Time') }}</a>
                </div>
                <ol class="breadcrumb d-none d-lg-flex">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.listing.byviews') }}">{{ __('Listing By Views') }}</a></li>
                    <li class="breadcrumb-item"><a href="javascript:;">{{ __('Analytics') }}</a></li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 col-lg-2 border-right">
                            <h4 class="mb-0 font-weight-bold" style="color:#7c3aed;">{{ $totals['impressions'] }}</h4>
                            <small class="text-muted">{{ __('Impressions') }}</small>
                        </div>
                        <div class="col-6 col-lg-2 border-right">
                            <h4 class="mb-0 font-weight-bold text-dark">{{ $totals['views'] }}</h4>
                            <small class="text-muted">{{ __('Views') }}</small>
                        </div>
                        <div class="col-6 col-lg-2 border-right mt-3 mt-lg-0">
                            <h4 class="mb-0 font-weight-bold text-success">{{ $totals['phone'] }}</h4>
                            <small class="text-muted">{{ __('Phone') }}</small>
                        </div>
                        <div class="col-6 col-lg-2 border-right mt-3 mt-lg-0">
                            <h4 class="mb-0 font-weight-bold text-success">{{ $totals['whatsapp'] }}</h4>
                            <small class="text-muted">{{ __('WhatsApp') }}</small>
                        </div>
                        <div class="col-6 col-lg-2 border-right mt-3 mt-lg-0">
                            <h4 class="mb-0 font-weight-bold text-info">{{ $totals['website'] }}</h4>
                            <small class="text-muted">{{ __('Website') }}</small>
                        </div>
                        <div class="col-6 col-lg-2 mt-3 mt-lg-0">
                            <h4 class="mb-0 font-weight-bold text-warning">{{ $totals['email'] }}</h4>
                            <small class="text-muted">{{ __('Email') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body p-0">
                    <div class="p-3">
                        <strong>{{ __('Statistics') }}</strong>
                        @if ($range == 'monthly')
                            <small class="text-muted d-block">{{ __('Daily breakdown for') }} {{ now()->format('F Y') }}</small>
                        @elseif ($range == 'yearly')
                            <small class="text-muted d-block">{{ __('Monthly breakdown for') }} {{ now()->format('Y') }}</small>
                        @else
                            <small class="text-muted d-block">{{ __('Monthly breakdown - last 24 months') }}</small>
                        @endif
                    </div>
                    @php $maxChart = max(1, $chart->max(fn ($item) => max($item['impressions'], $item['views'], $item['clicks']))); @endphp
                    <div class="alist-chart">
                        @foreach ($chart as $item)
                            <div class="alist-col">
                                <div class="alist-bars">
                                    <div class="alist-bar impressions" title="{{ $item['impressions'] }} {{ __('Impressions') }}" style="height: {{ max(3, ($item['impressions'] / $maxChart) * 120) }}px"></div>
                                    <div class="alist-bar views" title="{{ $item['views'] }} {{ __('Views') }}" style="height: {{ max(3, ($item['views'] / $maxChart) * 120) }}px"></div>
                                    <div class="alist-bar clicks" title="{{ $item['clicks'] }} {{ __('Clicks') }}" style="height: {{ max(3, ($item['clicks'] / $maxChart) * 120) }}px"></div>
                                </div>
                                <div class="alist-label">{{ $item['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="alist-legend">
                        <span class="alist-dot" style="background:#7c3aed;"></span>{{ __('Impressions') }}
                        <span class="alist-dot" style="background:#1768E0;"></span>{{ __('Views') }}
                        <span class="alist-dot" style="background:#13a56b;"></span>{{ __('Clicks') }} ({{ __('Phone + WhatsApp + Website + Email') }})
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="p-3"><strong>{{ __('Recent Views') }}</strong></div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('No') }}</th>
                                    <th>{{ __('Device') }}</th>
                                    <th>{{ __('Browser') }}</th>
                                    <th>{{ __('Operating System') }}</th>
                                    <th>{{ __('Visited At') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentViews as $key => $data)
                                    <tr>
                                        <td>{{ $recentViews->firstItem() + $key }}</td>
                                        <td>{{ strtoupper($data->device ?? '-') }}</td>
                                        <td>{{ $data->browser ?? '-' }}</td>
                                        <td>{{ $data->operating_system ?? '-' }}</td>
                                        <td>{{ date('d M Y, h:i A', strtotime($data->created_at)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <h4 class="text-center m-0 py-2">{{ __('No Data Found') }}</h4>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $recentViews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
