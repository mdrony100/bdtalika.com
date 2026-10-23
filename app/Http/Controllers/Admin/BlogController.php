<?php

namespace App\Http\Controllers\Admin;

use Purifier;
use Datatables;
use App\Models\Blog;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables(Request $request)
    {
        $datas = Blog::orderBy('id', 'desc')
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            });

        return Datatables::of($datas)
            ->editColumn('photo', function (Blog $data) {
                $photo = $data->photo ? url('assets/images/' . $data->photo) : url('assets/images/noimage.png');
                return '<img src="' . $photo . '" alt="Image">';
            })

            ->addColumn('status', function (Blog $data) {
                if ($data->status == 1) {
                    $badge = '<span class="badge badge-success px-2 py-1" style="font-size: 12px;">' . __("Published") . '</span>';
                    $toggle = '<a class="btn btn-secondary btn-sm btn-rounded" href="' . route('admin.blog.status', $data->id) . '" title="' . __('Move to Draft') . '">' . __("Draft") . '</a>';
                } else {
                    $badge = '<span class="badge badge-secondary px-2 py-1" style="font-size: 12px;">' . __("Draft") . '</span>';
                    $toggle = '<a class="btn btn-success btn-sm btn-rounded" href="' . route('admin.blog.status', $data->id) . '" title="' . __('Publish this post') . '">' . __("Publish") . '</a>';
                }
                return '<div class="d-flex align-items-center">' . $badge . '<span class="ml-1"></span>' . $toggle . '</div>';
            })

            ->addColumn('action', function (Blog $data) {

                return '<div class="btn-group mb-1">
                                <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  ' . 'Actions' . '
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start">
                                  <a href="' . route('admin.blog.edit', $data->id) . '"  class="dropdown-item">' . __("Edit") . '</a>
                                  <a onclick="return confirm(\'' . __('Are you sure you want to delete this?') . '\')" 
                                    class="dropdown-item" 
                                    href="' . route('admin.blog.delete', $data->id) . '">' . __("Delete") . '</a>
                                </div>
                              </div>';
            })
            ->rawColumns(['photo', 'status', 'action'])
            ->toJson();
    }

    public function statusToggle($id)
    {
        $data = Blog::findOrFail($id);
        $data->status = $data->status == 1 ? 0 : 1;
        $data->save();

        $msg = $data->status == 1 ? 'Post Published Successfully.' : 'Post Moved to Draft Successfully.';
        Toastr::success($msg);
        return redirect()->route('admin.blog.index');
    }

    public function index()
    {
        return view('admin.blog.index');
    }

    public function create()
    {
        $data['cats'] = BlogCategory::all();
        return view('admin.blog.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|mimes:jpeg,jpg,png,svg|max:2048',
            'title' => 'required|unique:blogs,title',
            'slug' => 'required|unique:blogs,slug',
            'category_id' => 'required',
        ]);

        $data  = new Blog();
        $input = $request->except('photo');

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images'), $name);
            $input['photo'] = $name;
        }

        $input['slug'] = Str::slug($request->slug);

        $common_rep = ["value", "{", "}", "[", "]", ":", "\""];
        $input['tags'] = str_replace($common_rep, '', $request->tags);
        $input['meta_tag'] = str_replace($common_rep, '', $request->meta_tag);

        if (!$request->filled('secheck')) {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
        }

        $input['views'] = 0;
        $input['status'] = $request->input('status', 1) == 1 ? 1 : 0;
        $data->fill($input)->save();

        Toastr::success('Post Created Successfully.');
        return redirect()->route('admin.blog.index');
    }



    // public function store(Request $request)
    // {
    //     $rules = [
    //         'photo' => 'required|mimes:jpeg,jpg,png,svg',
    //         'title' => 'required|unique:blogs,title',
    //         'category_id' => 'required'
    //     ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()]);
    //     }


    //     $data   = new Blog();
    //     $input  = $request->all();

    //     if ($file = $request->file('photo')) {
    //         $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
    //         $file->move('assets/images', $name);
    //         $input['photo'] = $name;
    //     }

    //     $input['slug'] = Str::slug($request->title);
    //     $common_rep   = ["value", "{", "}", "[", "]", ":", "\""];
    //     $tag = str_replace($common_rep, '', $request->tags);
    //     $metatag = str_replace($common_rep, '', $request->meta_tag);

    //     if (!empty($metatag)) {
    //         $input['meta_tag'] = $metatag;
    //     }

    //     if (!empty($tag)) {
    //         $input['tags'] = $tag;
    //     }

    //     if ($request->secheck == "") {
    //         $input['meta_tag'] = null;
    //         $input['meta_description'] = null;
    //     }
    //     $input['views'] = 0;
    //     $data->fill($input)->save();
    //     // $msg = 'New Data Added Successfully.' . '<a href="' . route("admin.blog.index") . '">View Post Lists</a>';
    //     Toastr::success('Post Created Successfully.');
    //     return redirect()->route('admin.blog.index');
    // }

    public function edit($id)
    {
        $cats = BlogCategory::all();
        $data = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('data', 'cats'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'photo' => 'mimes:jpeg,jpg,png,svg|max:2048',
            'title' => 'required|unique:blogs,title,'.$id,
            'category_id' => 'required',
        ]);

        $data = Blog::findOrFail($id); 
        $input = $request->except('photo');

        if ($file = $request->file('photo')) {
            $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            @unlink('assets/images/' . $data->photo);
            $input['photo'] = $name;
        }

        //$input['slug'] = Str::slug($request->title);

        $common_rep = ["value", "{", "}", "[", "]", ":", "\""];
        $input['tags'] = str_replace($common_rep, '', $request->tags);
        $input['meta_tag'] = str_replace($common_rep, '', $request->meta_tag);

        if (!$request->filled('secheck')) {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
        }
 
        $input['status'] = $request->input('status', 1) == 1 ? 1 : 0;
        $data->update($input);

        Toastr::success('Post Updated Successfully.');
        return redirect()->route('admin.blog.index');
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'photo' => 'mimes:jpeg,jpg,png,svg|max:2048',
    //         'title' => 'required|unique:blogs,title,'.$id,
    //         'category_id' => 'required',
    //     ]);

    //     $data = Blog::findOrFail($id);
    //     $input = $request->all();

    //     if ($file = $request->file('photo')) {
    //         $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
    //         $file->move('assets/images', $name);
    //         @unlink('assets/images/' . $data->photo);
    //         $input['photo'] = $name;
    //     }

    //     $common_rep   = ["value", "{", "}", "[", "]", ":", "\""];
    //     $tag = str_replace($common_rep, '', $request->tags);
    //     $metatag = str_replace($common_rep, '', $request->meta_tag);

    //     if (!empty($metatag)) {
    //         $input['meta_tag'] = $metatag;
    //     }
    //     if (!empty($tag)) {
    //         $input['tags'] = $tag;
    //     }
    //     if ($request->secheck == "") {
    //         $input['meta_tag'] = null;
    //         $input['meta_description'] = null;
    //     }
    //     $input['slug'] = Str::slug($request->title);
    //     $data->update($input);

    //     // $msg = 'Data Updated Successfully.' . '<a href="' . route("admin.blog.index") . '">View Post Lists</a>';
    //     // return response()->json($msg);
    //     Toastr::success('Post Updated Successfully.');
    //     return redirect()->route('admin.blog.index');
    // }

    public function destroy($id)
    {
        $data = Blog::findOrFail($id);
        @unlink('assets/images/' . $data->photo);
        $data->delete();

        // $msg = 'Data Deleted Successfully.';
        // return response()->json($msg);
        Toastr::success('Post Deleted Successfully.');
        return redirect()->back();
    }
}
