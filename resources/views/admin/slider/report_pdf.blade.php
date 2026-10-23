<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slider Analytics Report</title>
    <style>
        body {font-family: DejaVu Sans, sans-serif;color:#212529;font-size:13px;}
        h1 {font-size:20px;color:#21318c;margin:0 0 4px;}
        .muted {color:#6c757d;font-size:11px;}
        table {border-collapse:collapse;width:100%;margin:14px 0;}
        th, td {border:1px solid #dee2e6;padding:7px 10px;text-align:left;}
        th {background:#f1f3f7;font-size:11px;}
        td.n {text-align:right;}
        .totals td {font-weight:bold;}
        .footer {margin-top:26px;font-size:10px;color:#6c757d;border-top:1px solid #dee2e6;padding-top:8px;}
    </style>
</head>
<body>
    <h1>Slider Analytics Report</h1>
    <div class="muted">bdtalika.com - Bangladesh Business Directory</div>

    <table class="totals">
        <tr>
            <td>Slider</td>
            <td>{{ $slider->name ?? ('Slider #' . $slider->id) }}</td>
        </tr>
        <tr>
            <td>Report Period</td>
            <td>{{ $stats['label'] }}</td>
        </tr>
        <tr>
            <td>Link</td>
            <td>{{ $slider->link ?? '-' }}</td>
        </tr>
    </table>

    <h3 style="font-size:14px;margin:18px 0 0;">Summary</h3>
    <table>
        <tr>
            <th>Impressions</th>
            <th>Unique Visitors</th>
            <th>Clicks</th>
            <th>Unique Clickers</th>
            <th>CTR</th>
        </tr>
        <tr>
            <td class="n">{{ number_format($stats['totals']['impressions']) }}</td>
            <td class="n">{{ number_format($stats['totals']['unique_impressions']) }}</td>
            <td class="n">{{ number_format($stats['totals']['clicks']) }}</td>
            <td class="n">{{ number_format($stats['totals']['unique_clicks']) }}</td>
            <td class="n">{{ $stats['totals']['ctr'] }}%</td>
        </tr>
    </table>

    <h3 style="font-size:14px;margin:18px 0 0;">Breakdown</h3>
    <table>
        <tr>
            <th>Period</th>
            <th>Impressions</th>
            <th>Clicks</th>
            <th>CTR</th>
        </tr>
        @forelse ($stats['chart'] as $item)
            <tr>
                <td>{{ $item['label'] }}</td>
                <td class="n">{{ number_format($item['impressions']) }}</td>
                <td class="n">{{ number_format($item['clicks']) }}</td>
                <td class="n">{{ $item['impressions'] > 0 ? round($item['clicks'] / $item['impressions'] * 100, 2) . '%' : '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No data for this period.</td></tr>
        @endforelse
    </table>

    <div class="footer">
        Generated on {{ $generatedAt->format('d M Y, h:i A') }} (Bangladesh time) by BDTalika Slider Analytics.
    </div>
</body>
</html>
