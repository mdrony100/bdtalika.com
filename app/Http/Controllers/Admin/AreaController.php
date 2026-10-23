<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Area;
use App\Models\City;
use App\Models\Country;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\AreaRequest;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables(Request $request)
    {
        $datas = Area::with('division')->with('city')->orderBy('order_number', 'asc');


        return Datatables::of($datas)
            ->addColumn('checkbox', function (Area $data) {
                return $checkbox = $data->id ? '<input type="checkbox" class="form-check-input m-0 p-0 columnCheck" value="' . $data->id . '">' : '';
            })
            ->editColumn('division_id', function (Area $data) {
                return $division = $data->division->name != NULL ? $data->division->name : 'Not Found';
            })
            ->editColumn('city_id', function (Area $data) {
                return $city = $data->city->name != NULL ? $data->city->name : 'Not Found';
            })
            ->addColumn('status', function (Area $data) {
                $status      = $data->status == 1 ? __('Activated') : __('Deactivated');
                $status_sign = $data->status == 1 ? 'success'   : 'danger';

                return '<div class="btn-group mb-1">
                                <button type="button" class="btn btn-' . $status_sign . ' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                ' . $status . '
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start">
                                    <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="' . route('admin.areas.status', ['id1' => $data->id, 'id2' => 1]) . '">' . __("Activate") . '</a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="' . route('admin.areas.status', ['id1' => $data->id, 'id2' => 0]) . '">' . __("Deactivate") . '</a>
                                </div>
                            </div>';
            })

            ->addColumn('action', function (Area $data) {
                return '<div class="btn-group mb-1">
                                <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  ' . 'Actions' . '
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start">
                                  <a href="' . route('admin.areas.edit', $data->id) . '"  class="dropdown-item">' . __("Edit") . '</a>
                                  <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="' .  route('admin.areas.delete', $data->id) . '">' . __("Delete") . '</a>
                                </div>
                              </div>';
            })
            ->rawColumns(['checkbox', 'status', 'action'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.areas.index');
    }

    public function create()
    {
        $data['divisions'] = Location::where('parent_id', NULL)->whereStatus(1)->get();
        return view('admin.areas.create', $data);
    }

    public function store(AreaRequest $request)
    {
        $data = new Area();
        $input = $request->all();
        $data->slug = Str::slug($input['title'], '-');
        $input['order_number'] = $request->order_number ?? Area::max('order_number') + 1;
        if ($file = $request->file('photo')) {
            $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            $input['photo'] = $name;
        }
        $data->fill($input)->save();
        $msg = __('New Data Added Successfully.') . ' ' . '<a href="' . route('admin.areas.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data['divisions'] = Location::where('parent_id', NULL)->whereStatus(1)->get();
        $data['data'] = Area::findOrFail($id);
        $data['cities'] = Location::where('parent_id', $data['data']->division_id)->whereStatus(1)->get();
        return view('admin.areas.edit', $data);
    }

    public function update(AreaRequest $request, $id)
    {
        $data = Area::findOrFail($id);
        $input = $request->all();
        $data->slug  = Str::slug($input['title'], '-');
        $input['order_number'] = $request->order_number ?? Area::max('order_number') + 1;
        if ($file = $request->file('photo')) {
            $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            @unlink('assets/images/' . $data->photo);
            $input['photo'] = $name;
        }
        $data->update($input);
        $msg = __('Data Updated Successfully.') . ' ' . '<a href="' . route('admin.areas.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }

    public function status($id1, $id2)
    {
        $data = Area::findOrFail($id1);
        $data->status = $id2;
        $data->update();

        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function bulkdelete(Request $request)
    {
        $ids = $request->bulk_id;
        $bulk_ids = explode(",", $ids);
        foreach ($bulk_ids as $key => $id) {
            if ($id) {
                try {
                    $this->delete($id);
                    $msg = 'Data Deleted Successfully.';
                } catch (\Throwable $th) {
                    $msg = $th->getMessage();
                }
            }
        }
        return response()->json($msg);
    }

    public function destroy($id)
    {
        try {
            $this->delete($id);
            $msg = 'Data Deleted Successfully.';
        } catch (\Throwable $th) {
            $msg = $th->getMessage();
        }
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }

    public function delete($id)
    {
        $data = Area::findOrFail($id);
        if ($data->photo) {
            @unlink('assets/images/' . $data->photo);
        }
        $data->delete();
        return true;
    }

    public function DivisionGetCity($id)
    {
        $city = Location::where('parent_id', $id)->get();
        return response()->json($city);
    }

    public function CityGetArea($id)
    {
        $city = Area::where('city_id', $id)->get();
        return response()->json($city);
    }
}
