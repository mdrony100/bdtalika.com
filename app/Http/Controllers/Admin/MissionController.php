<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Pagesetting;
use Illuminate\Http\Request;
use Datatables;

class MissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables()
    {
         $datas = Counter::orderBy('id','desc')->get();

         return Datatables::of($datas)
                            ->editColumn('count', function(Counter $data){
                                if($data->is_money == 1){
                                  $count = '$ '.$data->count;
                                }else{
                                  $count = $data->count;
                                }
                                return $count;
                            })
                            ->addColumn('action', function(Counter $data) {

                                return '<div class="btn-group mb-1">
                                  <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    '.'Actions' .'
                                  </button>
                                  <div class="dropdown-menu" x-placement="bottom-start">
                                    <a href="' . route('admin.mission.edit',$data->id) . '"  class="dropdown-item">'.__("Edit").'</a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="'.  route('admin.mission.delete',$data->id).'">'.__("Delete").'</a>
                                  </div>
                                </div>';

                              })
                            ->rawColumns(['count', 'action'])
                            ->toJson();
    }

    public function index()
    {
        $data = Pagesetting::first();
        return view('admin.mission.index',compact('data'));
    }

    public function create()
    {
        return view('admin.mission.create');
    }

    public function store(Request $request)
    {
        $data = new Counter();
        $input = $request->all();

        if($request->is_money){
          $input['is_money'] = 1;
        }
        $data->fill($input)->save();

        $msg = 'New Data Added Successfully.'.'<a href="'.route("admin.mission.index").'">View Counter Lists</a>';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = Counter::findOrFail($id);
        return view('admin.mission.edit',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Counter::findOrFail($id);
        $input = $request->all();

        if($request->is_money){
          $input['is_money'] = 1;
        }else{
          $input['is_money'] = 0;
        }
        $data->update($input);

        $msg = 'Data Updated Successfully.'.'<a href="'.route("admin.mission.index").'">View Counter Lists</a>';
        return response()->json($msg);
    }


    public function destroy($id)
    {
        $data = Counter::findOrFail($id);
        $data->delete();

        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }
}
