<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Models\Location;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables(Request $request)
    {
        $datas = Location::orderBy('order_number', 'asc');

        return Datatables::of($datas)
            ->addColumn('checkbox', function (Location $data) {
                return $checkbox = $data->id ? '<input type="checkbox" class="form-check-input m-0 p-0 columnCheck" value="' . $data->id . '">' : '';
            })
            ->editColumn('created_at', function (Location $data) {
                return $data->created_at->toFormattedDateString();
            })
            ->editColumn('photo', function (Location $data) {
                $photo = $data->photo ? url('assets/images/' . $data->photo) : url('assets/images/default.png');
                return '<img src="' . $photo . '" alt="Image">';
            })
            ->editColumn('name', function (Location $data) {
                return $data = $data->parent_id != NULL ? $data->name . ' => ' . $data->parent->name : $data->name;
            })
            ->addColumn('status', function (Location $data) {
                $status      = $data->status == 1 ? __('Activated') : __('Deactivated');
                $status_sign = $data->status == 1 ? 'success'   : 'danger';

                return '<div class="btn-group mb-1">
                                        <button type="button" class="btn btn-' . $status_sign . ' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ' . $status . '
                                        </button>
                                        <div class="dropdown-menu" x-placement="bottom-start">
                                            <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="' . route('admin.locations.status', ['id1' => $data->id, 'id2' => 1]) . '">' . __("Activate") . '</a>
                                            <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="' . route('admin.locations.status', ['id1' => $data->id, 'id2' => 0]) . '">' . __("Deactivate") . '</a>
                                        </div>
                                    </div>';
            })
            ->addColumn('action', function (Location $data) {
                return '<div class="btn-group mb-1">
                                <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  ' . 'Actions' . '
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start">
                                  <a href="' . route('admin.locations.edit', $data->id) . '"  class="dropdown-item">' . __("Edit") . '</a>
                                  <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="' .  route('admin.locations.delete', $data->id) . '">' . __("Delete") . '</a>
                                </div>
                              </div>';
            })
            ->rawColumns(['checkbox', 'photo', 'status', 'action'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.locations.index');
    }

    public function create()
    {
        $data['locations'] = Location::whereParentId(NULL)->whereStatus(1)->get();
        return view('admin.locations.create', $data);
    }

    public function store(LocationRequest $request)
    {
        $data           = new Location();
        $input          = $request->all();
        $input['slug']  = Str::slug($request->name, '-');
        $input['order_number'] = $request->order_number ?? Location::max('order_number') + 1;
        if ($file = $request->file('photo')) {
            $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            $input['photo'] = $name;
        }
        $data->fill($input)->save();

        $msg = __('New Data Added Successfully.') . ' ' . '<a href="' . route('admin.locations.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data['data']       = Location::findOrFail($id);
        $data['locations']  = Location::whereParentId(NULL)->where('name', '!=', $data['data']->name)->get();

        return view('admin.locations.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:locations,name,'.$id,
            'photo' => 'mimes:jpeg,jpg,png,svg',
        ]);
    
        if ($validator->fails()) {
            $response = response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            throw new ValidationException($validator, $response);
        }
    
        $data = Location::findOrFail($id);
        $input = $request->all();
        $input['order_number'] = $request->order_number ?? Location::max('order_number') + 1;
        if ($request->name) {
            $input['slug'] = Str::slug($request->name, '-');
        }
    
        if ($file = $request->file('photo')) {
            $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            @unlink('assets/images/' . $data->photo);
            $input['photo'] = $name;
        }
    
        $data->update($input);
    
        $msg = __('Data Updated Successfully.') . ' ' . '<a href="' . route('admin.locations.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }
    

    public function status($id1, $id2)
    {
        $data = Location::findOrFail($id1);
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
                    $msg = 'Something went wrong.';
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
            $msg = 'Something went wrong.';
        }
        return response()->json($msg);
    }

    public function delete($id)
    {
        $data = Location::findOrFail($id);
        @unlink('assets/images/' . $data->photo);
        $data->delete();

        return true;
    }
}
