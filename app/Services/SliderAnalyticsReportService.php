<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\Slider;
use App\Models\SliderAnalytics;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SliderAnalyticsReportService
{
    /**
     * Ranges: monthly = current month, yearly = current year, all = full history.
     *
     * @return array{range: string, label: string, start: ?string, end: ?string, totals: array, chart: \Illuminate\Support\Collection, chartMax: int}
     */
    public function stats(Slider $slider, string $range = 'all'): array
    {
        $range = in_array($range, ['monthly', 'yearly', 'all']) ? $range : 'all';
        $now = now('Asia/Dhaka');

        $start = [
            'monthly' => $now->copy()->startOfMonth()->toDateString(),
            'yearly'  => $now->copy()->startOfYear()->toDateString(),
        ][$range] ?? null;

        $events = SliderAnalytics::where('slider_id', $slider->id)
            ->when($start, function ($query) use ($start) {
                $query->where('event_date', '>=', $start);
            })
            ->get();

        $impressions = $events->where('event_type', 'impression');
        $clicks = $events->where('event_type', 'click');

        $totals = [
            'impressions' => $impressions->count(),
            'clicks' => $clicks->count(),
            'unique_impressions' => $impressions->pluck('visitor_hash')->filter()->unique()->count(),
            'unique_clicks' => $clicks->pluck('visitor_hash')->filter()->unique()->count(),
            'ctr' => $impressions->count() > 0 ? round(($clicks->count() / $impressions->count()) * 100, 2) : 0,
        ];

        if ($range === 'monthly') {
            $chart = collect(range(0, $now->day - 1))->map(function ($offset) use ($now, $impressions, $clicks) {
                $day = $now->copy()->startOfMonth()->addDays($offset)->toDateString();

                return [
                    'label' => date('d M', strtotime($day)),
                    'impressions' => $impressions->where('event_date', $day)->count(),
                    'clicks' => $clicks->where('event_date', $day)->count(),
                ];
            });
            $label = $now->format('F Y');
        } elseif ($range === 'yearly') {
            $chart = collect(range(0, $now->month - 1))->map(function ($offset) use ($now, $impressions, $clicks) {
                $monthKey = $now->copy()->startOfYear()->addMonths($offset)->format('Y-m');

                return [
                    'label' => $now->copy()->startOfYear()->addMonths($offset)->format('M'),
                    'impressions' => $impressions->filter(fn ($e) => str_starts_with((string) $e->event_date, $monthKey))->count(),
                    'clicks' => $clicks->filter(fn ($e) => str_starts_with((string) $e->event_date, $monthKey))->count(),
                ];
            });
            $label = $now->format('Y');
        } else {
            $firstDate = $events->min('event_date');
            $monthsBack = $firstDate
                ? min(24, \Carbon\Carbon::parse($firstDate)->diffInMonths($now))
                : 0;

            $chart = collect(range($monthsBack, 0))->map(function ($offset) use ($now, $impressions, $clicks) {
                $monthStart = $now->copy()->subMonths($offset)->startOfMonth();
                $monthKey = $monthStart->format('Y-m');

                return [
                    'label' => $monthStart->format('M y'),
                    'impressions' => $impressions->filter(fn ($e) => str_starts_with((string) $e->event_date, $monthKey))->count(),
                    'clicks' => $clicks->filter(fn ($e) => str_starts_with((string) $e->event_date, $monthKey))->count(),
                ];
            });
            $label = 'All Time';
        }

        return [
            'range' => $range,
            'label' => $label,
            'totals' => $totals,
            'chart' => $chart,
            'chartMax' => max(1, $chart->max(fn ($item) => max($item['impressions'], $item['clicks']))),
        ];
    }

    public function pdf(Slider $slider, array $stats): \Barryvdh\DomPDF\PDF
    {
        return Pdf::loadView('admin.slider.report_pdf', [
            'slider' => $slider,
            'stats' => $stats,
            'generatedAt' => now('Asia/Dhaka'),
        ]);
    }

    /**
     * Email the PDF report to an arbitrary address. Logged in EmailLog
     * with mailer slider_analytics_report (user linked when the email
     * belongs to a registered account).
     */
    public function emailReport(Slider $slider, string $range, string $email): bool
    {
        $stats = $this->stats($slider, $range);
        $filename = 'slider-analytics-report-' . $slider->id . '-' . $stats['range'] . '.pdf';

        try {
            $pdf = $this->pdf($slider, $stats);
            $subject = 'Slider Analytics Report (' . $stats['label'] . ') - ' . $slider->name . ' - bdtalika.com';

            $body = view('emails.slider-analytics-report', [
                'slider' => $slider,
                'stats' => $stats,
            ])->render();

            Mail::send('emails.slider-analytics-report', [
                'slider' => $slider,
                'stats' => $stats,
            ], function ($message) use ($email, $pdf, $subject, $filename) {
                $message->to($email)->subject($subject)->attachData($pdf->output(), $filename, [
                    'mime' => 'application/pdf',
                ]);
            });

            EmailLog::create([
                'user_id' => optional(User::where('email', $email)->first())->id,
                'email' => $email,
                'subject' => $subject,
                'body' => $body,
                'mailer' => 'slider_analytics_report',
            ]);

            return true;
        } catch (Throwable $e) {
            Log::error('Slider analytics report email failed', [
                'slider_id' => $slider->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
