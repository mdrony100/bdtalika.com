<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlertPopup;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class AlertPopupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AlertPopup::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <a href="' . route('admin.alertpopup.edit', $row->id) . '" class="btn-sm btn btn-primary">Edit</a>
                        <a href="' . route('admin.alertpopup.delete', $row->id) . '" onclick="return confirm(\'Are you sure to delete this data?\');" class="btn-sm btn btn-danger text-white" style="cursor:pointer;">Delete</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('admin.alertpopup.index');
    }

    public function create()
    {
        return view('admin.alertpopup.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'        => 'required_if:type,1',
            'iframe_video' => 'required_if:type,2',
            'video_file'   => 'required_if:type,3',
            'link'         => 'required',
        ],[
            'image.required_if' => 'The image field is required',
            'iframe_video.required_if' => 'The video field is required',
            'video_file.required_if' => 'The video field is required',
        ]);

        $data = new AlertPopup;
        if ($file = $request->file('image')) {
            $name = Str::random(8) . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            $data->image = $name;
        }
        if ($file = $request->file('video_file')) {
            $name = Str::random(8) . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            $data->video_file = $name;
        }
        $data->type         = $request->type;
        $data->link         = $request->link;
        $data->iframe_video = $request->iframe_video;
        $data->status = $request->status;
        if ($request->status == 1) {
            AlertPopup::where('id', '!=', $data->id)->update(['status' => 0]);
        }
        $data->save();
        Toastr::success('Data Created Successfully.');
        return redirect()->route('admin.alertpopup.index');
    }

    public function edit($id)
    {
        $data = AlertPopup::findorFail($id);
        return view('admin.alertpopup.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'link'          => 'required',
        ]);


        $data = AlertPopup::findorFail($id);
        if ($file = $request->file('image')) {
            $name = Str::random(8) . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            $data->image = $name;
        }
        if ($file = $request->file('video_file')) {
            $name = Str::random(8) . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            $data->video_file = $name;
        }
        $data->type         = $request->type;
        $data->iframe_video = $request->iframe_video;
        $data->status = $request->status;
        $data->link = $request->link;
        if ($request->status == 1) {
            AlertPopup::where('id', '!=', $id)->update(['status' => 0]);
        }
        $data->update();
        Toastr::success('Data Updated Successfully.');
        return redirect()->route('admin.alertpopup.index');
    }

    public function delete($id)
    {
        $data = AlertPopup::findOrFail($id);
        if ($data->image) {
            @unlink('assets/images/' . $data->image);
        }
        if ($data->video_file) {
            @unlink('assets/images/' . $data->video_file);
        }
        $data->delete();
        Toastr::success('Data Deleted Successfully.');
        return redirect()->back();
    }
}
