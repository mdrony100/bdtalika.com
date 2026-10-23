<?php

namespace App\Http\Controllers\Admin;

use App\Models\TopBanner;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TopBanner::orderBy('id', 'desc')->get();
        
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <a href="' . route('admin.banner.edit', $row->id) . '" class="btn-sm btn btn-primary">Edit</a>
                        <a href="' . route('admin.banner.delete', $row->id) . '" onclick="return confirm(\'Are you sure to delete this data?\');" class="btn-sm btn btn-danger text-white" style="cursor:pointer;">Delete</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('admin.banner.index');
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'link' => 'required',
            'status' => 'required', 
        ]);

        $data = new TopBanner;
        if ($file = $request->file('image')) {
            $name = Str::random(8) . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            $data->image = $name;
        }
        $data->link = $request->link;
        $data->status = $request->status;
        if ($request->status == 1) {
            TopBanner::where('id', '!=', $data->id)->update(['status' => 0]);
        }
        $data->save();
        Toastr::success('Data Created Successfully.');
        return redirect()->route('admin.banner.index');
    }

    public function edit($id)
    {
        $data = TopBanner::findorFail($id);
        return view('admin.banner.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'link'      => 'required',
            'status'    => 'required', 
        ]);

        $data = TopBanner::findorFail($id);
        if ($file = $request->file('image')) {
            $name = Str::random(8) . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            $data->image = $name;
        }
        $data->link = $request->link;
        $data->status = $request->status;
        if ($request->status == 1) {
            TopBanner::where('id', '!=', $id)->update(['status' => 0]);
        }
        $data->update();
        Toastr::success('Data Updated Successfully.');
        return redirect()->route('admin.banner.index');
    }

    public function delete($id)
    {
        $data = TopBanner::findOrFail($id);
        if ($data->photo) {
            @unlink('assets/images/' . $data->photo);
        }
        $data->delete();
        Toastr::success('Data Deleted Successfully.');
        return redirect()->back();
    }

}
