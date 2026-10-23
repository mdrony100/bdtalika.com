<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Listing;
use App\Models\Category;
use App\Models\ListingType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables(Request $request)
    {
        $datas = Category::with('type')->orderBy('order_number', 'asc');
        return Datatables::of($datas)
            ->addColumn('checkbox', function (Category $data) {
                return $checkbox = $data->id ? '<input type="checkbox" class="form-check-input m-0 p-0 columnCheck" value="' . $data->id . '">' : '';
            })
            ->editColumn('photo', function (Category $data) {
                $photo = $data->photo ? url('assets/images/' . $data->photo) : url('assets/images/noimage.png');
                return '<img src="' . $photo . '" alt="Image">';
            })
            ->editColumn('title', function (Category $data) {
                return $data = $data->parent_id != NULL ? $data->title . ', ' . $data->parent->title : $data->title;
            })
            ->addColumn('status', function (Category $data) {
                $status      = $data->status == 1 ? __('Activated') : __('Deactivated');
                $status_sign = $data->status == 1 ? 'success'   : 'danger';

                return '<div class="btn-group mb-1">
                                        <button type="button" class="btn btn-' . $status_sign . ' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ' . $status . '
                                        </button>
                                        <div class="dropdown-menu" x-placement="bottom-start">
                                            <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="' . route('admin.categories.status', ['id1' => $data->id, 'id2' => 1]) . '">' . __("Activate") . '</a>
                                            <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="' . route('admin.categories.status', ['id1' => $data->id, 'id2' => 0]) . '">' . __("Deactivate") . '</a>
                                        </div>
                                    </div>';
            })

            ->addColumn('action', function (Category $data) {
                return '<div class="btn-group mb-1">
                                <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  ' . 'Actions' . '
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start">
                                  <a href="' . route('admin.categories.edit', $data->id) . '"  class="dropdown-item">' . __("Edit") . '</a>
                                 
                                </div>
                              </div>';
            })
            // <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="' .  route('admin.categories.delete', $data->id) . '">' . __("Delete") . '</a>
            ->rawColumns(['checkbox', 'photo', 'status', 'type', 'action'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.categories.index');
    }

    public function create()
    {
        $data['categories']     = Category::whereParentId(NULL)->whereStatus(1)->get();
        $data['listing_types']  = ListingType::where('status', 'active')->get();
        return view('admin.categories.create', $data);
    }

    public function store(CategoryRequest $request)
    {
        $data = new Category();

        $input = $request->all();
        $input['slug'] = Str::slug($request->title, '-');
        $input['order_number'] = $request->order_number ?? Category::max('order_number') + 1;
        if ($file = $request->file('photo')) {
            $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            $input['photo'] = $name;
        }

        $data->fill($input)->save();

        $msg = __('New Data Added Successfully.') . ' ' . '<a href="' . route('admin.categories.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data['data'] = Category::findOrFail($id);
        $data['categories'] = Category::where('title', '!=', $data['data']->title)->whereParentId(NULL)->whereStatus(1)->get();
         $data['listing_types']  = ListingType::where('status', 'active')->get();
        return view('admin.categories.edit', $data);
    }

    public function update(CategoryRequest $request, $id)
    {
        $data = Category::findOrFail($id);
        $input = $request->all();
        // $input['slug'] = Str::slug($request->title, '-');
        $input['order_number'] = $request->order_number ?? Category::max('order_number') + 1;
        if ($file = $request->file('photo')) {
            $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            @unlink('assets/images/' . $data->photo);
            $input['photo'] = $name;
        }

        if ($request->is_top) {
            $input['is_top'] = $request->is_top;
        } else {
            $input['is_top'] = 0;
        }

        if ($request->is_grid) {
            $input['is_grid'] = $request->is_grid;
        } else {
            $input['is_grid'] = 0;
        }

        $data->update($input);

        $msg = __('Data Updated Successfully.') . ' ' . '<a href="' . route('admin.categories.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }


    public function status($id1, $id2)
    {
        $data = Category::findOrFail($id1);
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
            $data = Category::findOrFail($id);
            $listing = Listing::where('category_id', $data->id)->first();
            if (isset($listing) && $listing != Null) {
                $msg = 'Category delete not permitted; listings present.';
            } else {
                @unlink('assets/images/' . $data->photo);
                $data->delete();
                $msg = 'Data Deleted Successfully.';
            }
        } catch (\Throwable $th) {
            $msg = 'Something went wrong.';
        }
        return response()->json($msg);
    }

    // public function delete($id)
    // {
    //     $data = Category::findOrFail($id);
    //     if ($data->listings != NULL) {
    //         foreach ($data->listings as $key => $listing) {
    //             @unlink('assets/images/' . $listing->photo);
    //             $listing->delete();
    //         }
    //     }
    //     @unlink('assets/images/' . $data->photo);
    //     $data->delete();
    //     return true;
    // }
}
