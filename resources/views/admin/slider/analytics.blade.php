@extends('layouts.admin')

@section('content')
<style>
.san-tabs {display:flex;gap:6px;}
.san-tabs a {padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;color:#495057;background:#f1f3f7;text-decoration:none;}
.san-tabs a.active {background:#21318c;color:#fff;}
.san-chart {display:flex;gap:8px;align-items:flex-end;min-height:190px;padding:18px;overflow-x:auto;}
.san-col {flex:1;min-width:30px;display:flex;flex-direction:column;gap:6px;justify-content:flex-end;}
.san-bars {display:flex;gap:3px;align-items:flex-end;justify-content:center;height:130px;}
.san-bar {width:14px;border-radius:3px 3px 0 0;min-height:3px;}
.san-bar.impressions {background:#7c3aed;}
.san-bar.clicks {background:#1768E0;}
.san-label {font-size:12px;color:#6c757d;text-align:center;}
</style>
    <div class="card">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-gray-800 pl-3">{{ __('Slider Analytics') }} - {{ $slider->name ?? ('Slider #' . $slider->id) }}</h5>
            <div class="d-flex align-items-center">
                <div class="san-tabs mr-3">
                    <a href="{{ route('admin.slider.analytics', $slider->id) }}?range=monthly" class="{{ $range == 'monthly' ? 'active' : '' }}">{{ __('This Month') }}</a>
                    <a href="{{ route('admin.slider.analytics', $slider->id) }}?range=yearly" class="{{ $range == 'yearly' ? 'active' : '' }}">{{ __('This Year') }}</a>
                    <a href="{{ route('admin.slider.analytics', $slider->id) }}?range=all" class="{{ $range == 'all' ? 'active' : '' }}">{{ __('All Time') }}</a>
                </div>
                <ol class="breadcrumb d-none d-lg-flex">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.slider.index') }}">{{ __('Slider') }}</a></li>
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
                            <h4 class="mb-0 font-weight-bold" style="color:#7c3aed;">{{ number_format($stats['totals']['impressions']) }}</h4>
                            <small class="text-muted">{{ __('Impressions') }}</small>
                        </div>
                        <div class="col-6 col-lg-2 border-right">
                            <h4 class="mb-0 font-weight-bold text-dark">{{ number_format($stats['totals']['unique_impressions']) }}</h4>
                            <small class="text-muted">{{ __('Unique Visitors') }}</small>
                        </div>
                        <div class="col-6 col-lg-2 border-right mt-3 mt-lg-0">
                            <h4 class="mb-0 font-weight-bold" style="color:#1768E0;">{{ number_format($stats['totals']['clicks']) }}</h4>
                            <small class="text-muted">{{ __('Clicks') }}</small>
                        </div>
                        <div class="col-6 col-lg-2 border-right mt-3 mt-lg-0">
                            <h4 class="mb-0 font-weight-bold" style="color:#1768E0;">{{ number_format($stats['totals']['unique_clicks']) }}</h4>
                            <small class="text-muted">{{ __('Unique Clickers') }}</small>
                        </div>
                        <div class="col-6 col-lg-2 border-right mt-3 mt-lg-0">
                            <h4 class="mb-0 font-weight-bold text-success">{{ $stats['totals']['ctr'] }}%</h4>
                            <small class="text-muted">{{ __('CTR') }}</small>
                        </div>
                        <div class="col-6 col-lg-2 mt-3 mt-lg-0">
                            <h4 class="mb-0 font-weight-bold text-info">{{ $stats['label'] }}</h4>
                            <small class="text-muted">{{ __('Report Period') }}</small>
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
                    <div class="san-chart">
                        @foreach ($stats['chart'] as $item)
                            <div class="san-col">
                                <div class="san-bars">
                                    <div class="san-bar impressions" title="{{ $item['impressions'] }} {{ __('Impressions') }}" style="height: {{ max(3, ($item['impressions'] / $stats['chartMax']) * 120) }}px"></div>
                                    <div class="san-bar clicks" title="{{ $item['clicks'] }} {{ __('Clicks') }}" style="height: {{ max(3, ($item['clicks'] / $stats['chartMax']) * 120) }}px"></div>
                                </div>
                                <div class="san-label">{{ $item['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div style="font-size:12px;color:#6c757d;padding:0 18px 12px;">
                        <span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#7c3aed;margin:0 4px 0 10px;"></span>{{ __('Impressions') }}
                        <span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#1768E0;margin:0 4px 0 10px;"></span>{{ __('Clicks') }}
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-3"><strong>{{ __('Email PDF Report') }}</strong>
                        <small class="text-muted d-block">{{ __('Send this report as a PDF attachment to any email address') }}</small>
                    </h6>
                    <form action="{{ route('admin.slider.send.report', $slider->id) }}" method="POST">
                        @csrf
                        <div class="form-row align-items-end">
                            <div class="col-md-5">
                                <label for="report_email">{{ __('Email Address') }}</label>
                                <input type="email" name="email" id="report_email" class="form-control" placeholder="customer@example.com" required>
                            </div>
                            <div class="col-md-3">
                                <label for="report_range">{{ __('Report Period') }}</label>
                                <select name="range" id="report_range" class="form-control">
                                    <option value="monthly">{{ __('This Month') }}</option>
                                    <option value="yearly">{{ __('This Year') }}</option>
                                    <option value="all" selected>{{ __('All Time') }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-file-pdf"></i> {{ __('Send PDF') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="p-3"><strong>{{ __('Recent Events') }}</strong></div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('No') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Device') }}</th>
                                    <th>{{ __('Browser') }}</th>
                                    <th>{{ __('Operating System') }}</th>
                                    <th>{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentEvents as $key => $event)
                                    <tr>
                                        <td>{{ $recentEvents->firstItem() + $key }}</td>
                                        <td><span class="badge badge-{{ $event->event_type == 'click' ? 'success' : 'info' }}">{{ ucfirst($event->event_type) }}</span></td>
                                        <td>{{ strtoupper($event->device ?? '-') }}</td>
                                        <td>{{ $event->browser ?? '-' }}</td>
                                        <td>{{ $event->operating_system ?? '-' }}</td>
                                        <td>{{ $event->event_date }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6"><h4 class="text-center m-0 py-2">{{ __('No Data Found') }}</h4></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">{{ $recentEvents->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
