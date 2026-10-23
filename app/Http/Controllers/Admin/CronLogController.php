<?php

namespace App\Http\Controllers\Admin;

use App\Models\CronLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class CronLogController extends Controller
{
    public function index(Request $request)
    {
        $jobs = CronLog::select('job')->distinct()->orderBy('job')->pluck('job');

        $logs = CronLog::query()
            ->when($request->filled('job'), function ($q) use ($request) {
                $q->where('job', $request->job);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $jobLabels = [
            'whatsapp_abandoned_payment_reminder' => 'WhatsApp Abandoned Payment Reminder',
        ];

        return view('admin.cron.index', compact('logs', 'jobs', 'jobLabels'));
    }

    public function clear(Request $request)
    {
        $job = $request->input('job');

        if ($job) {
            CronLog::where('job', $job)->delete();
            Toastr::success('Cron logs cleared successfully for ' . $job . '.');

            return redirect()->route('admin.cron.logs');
        }

        CronLog::truncate();
        Toastr::success('All cron logs cleared successfully.');

        return redirect()->route('admin.cron.logs');
    }

    public function destroy($id)
    {
        CronLog::findOrFail($id)->delete();
        Toastr::success('Cron log deleted successfully.');

        return redirect()->route('admin.cron.logs');
    }
}
