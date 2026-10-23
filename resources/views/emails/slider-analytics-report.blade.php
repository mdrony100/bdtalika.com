<strong>Dear Business Owner</strong>, <br><br>
Please find attached the slider analytics report for <strong>{{ $slider->name ?? ('Slider #' . $slider->id) }}</strong> on <strong>bdtalika.com</strong> for the period <strong>{{ $stats['label'] }}</strong>.<br><br>

<strong>Summary:</strong><br>
- Impressions: {{ number_format($stats['totals']['impressions']) }}<br>
- Unique Visitors: {{ number_format($stats['totals']['unique_impressions']) }}<br>
- Clicks: {{ number_format($stats['totals']['clicks']) }}<br>
- CTR: {{ $stats['totals']['ctr'] }}%<br><br>

The full daily/monthly breakdown is in the attached PDF.<br><br>

Thank you,<br>
BDTalika Team<br>
https://bdtalika.com
