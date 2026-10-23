<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Slider;
use App\Models\SliderAnalytics;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\SliderAnalyticsReportService;
use App\Http\Requests\SliderRequest;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Slider::orderBy('order_number', 'asc')->get();

            $impressions = SliderAnalytics::where('event_type', 'impression')
                ->select('slider_id', DB::raw('count(*) as total'))
                ->groupBy('slider_id')
                ->pluck('total', 'slider_id');

            $clicks = SliderAnalytics::where('event_type', 'click')
                ->select('slider_id', DB::raw('count(*) as total'))
                ->groupBy('slider_id')
                ->pluck('total', 'slider_id');

            $todayImpressions = SliderAnalytics::where('event_type', 'impression')
                ->where('event_date', now()->toDateString())
                ->select('slider_id', DB::raw('count(*) as total'))
                ->groupBy('slider_id')
                ->pluck('total', 'slider_id');

            $todayClicks = SliderAnalytics::where('event_type', 'click')
                ->where('event_date', now()->toDateString())
                ->select('slider_id', DB::raw('count(*) as total'))
                ->groupBy('slider_id')
                ->pluck('total', 'slider_id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('impressions', function ($row) use ($impressions, $todayImpressions) {
                    $total = $impressions->get($row->id, 0);
                    $today = $todayImpressions->get($row->id, 0);
                    return number_format($total) . '<br><small class="text-muted">Today: ' . number_format($today) . '</small>';
                })
                ->addColumn('clicks', function ($row) use ($clicks, $todayClicks) {
                    $total = $clicks->get($row->id, 0);
                    $today = $todayClicks->get($row->id, 0);
                    return number_format($total) . '<br><small class="text-muted">Today: ' . number_format($today) . '</small>';
                })
                ->addColumn('ctr', function ($row) use ($impressions, $clicks) {
                    $impr = (int) $impressions->get($row->id, 0);
                    $clk = (int) $clicks->get($row->id, 0);
                    return $impr > 0 ? round(($clk / $impr) * 100, 2) . '%' : '-';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                    <a href="' . route('admin.slider.edit', $row->id) . '"
                          class="btn-sm btn btn-primary">Edit</a>
                        <a href="' . route('admin.slider.analytics', $row->id) . '"
                          class="btn-sm btn btn-info text-white">Analytics</a>
                        <button type="button" data-slider-id="' . $row->id . '" data-slider-name="' . e($row->name) . '"
                          class="btn-sm btn btn-warning open-report-modal" style="cursor:pointer;">Report</button>
                        <a data-href="'.  route('admin.slider.delete',$row->id).'" class="btn-sm btn btn-danger text-white" style="cursor:pointer;"  data-toggle="modal" data-target="#deleteModal">Delete</a>
                ';
                    return $actionBtn;
                })->rawColumns(['action', 'impressions', 'clicks'])->make(true);
        }
        return view('admin.slider.index');
    }

    public function analytics(Request $request, $id)
    {
        // Detailed per-slider analytics with This Month / This Year / All Time tabs
        $slider = Slider::findOrFail($id);

        $range = $request->input('range', 'all');
        $range = in_array($range, ['monthly', 'yearly', 'all']) ? $range : 'all';

        $stats = app(SliderAnalyticsReportService::class)->stats($slider, $range);

        $recentEvents = SliderAnalytics::where('slider_id', $slider->id)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->appends(['range' => $range]);

        return view('admin.slider.analytics', compact('slider', 'stats', 'range', 'recentEvents'));
    }

    public function sendReport(Request $request, $id)
    {
        // Manually email the slider analytics PDF report
        $request->validate([
            'email' => 'required|email',
            'range' => 'required|in:monthly,yearly,all',
        ]);

        $slider = Slider::findOrFail($id);

        $ok = app(SliderAnalyticsReportService::class)
            ->emailReport($slider, $request->input('range'), $request->input('email'));

        if ($ok) {
            Toastr::success('Analytics report PDF sent to ' . $request->input('email'));
        } else {
            Toastr::error('Could not send the report email. Please check mail settings and try again.');
        }

        return redirect()->back();
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(SliderRequest $request)
    {
        $data                   = new Slider;
        $data->link             = $request->link;
        $data->order_number     = $request->order_number ?? Slider::max('order_number') + 1;
        $data->status           = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name  = time() . '.' . $image->getClientOriginalExtension();
            $image->move('assets/images/', $name);
            $data->image = 'assets/images/' . $name;
        }

        $data->save();
        $msg = __('New Data Added Successfully.') . ' ' . '<a href="' . route('admin.slider.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $slider = Slider::find($id);
        return view('admin.slider.edit', compact('slider'));
    }

    public function update(SliderRequest $request, $id)
    {
        $data                   = Slider::find($id);
        $data->link             = $request->link;
        $data->order_number     = $request->order_number ?? Slider::max('order_number') + 1;
        $data->status           = $request->status;
        if ($request->hasFile('image')) {
            $imagePath  = public_path($data->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $image = $request->file('image');
            $name  = time() . '.' . $image->getClientOriginalExtension();
            $image->move('assets/images/', $name);
            $data->image = 'assets/images/' . $name;
        }

        $data->update();
        $msg = __('New Data Added Successfully.') . ' ' . '<a href="' . route('admin.slider.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }

    public function delete($id)
    {
        $slider = Slider::find($id);
        $imagePath  = public_path($slider->image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $slider->delete();
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
    }
}
