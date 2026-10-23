<?php

namespace App\Http\Controllers\Admin;

use App\Models\ErrorLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ErrorLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $logs = ErrorLog::query()
            ->when($request->filled('status_code'), function ($q) use ($request) {
                $q->where('status_code', $request->status_code);
            })
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('admin.error.index', compact('logs'));
    }

    public function destroy($id)
    {
        ErrorLog::findOrFail($id)->delete();
        Toastr::success('Error log deleted successfully.');
        return redirect()->back();
    }

    public function clear()
    {
        ErrorLog::truncate();
        Toastr::success('All error logs cleared successfully.');
        return redirect()->route('admin.error.logs');
    }
}
