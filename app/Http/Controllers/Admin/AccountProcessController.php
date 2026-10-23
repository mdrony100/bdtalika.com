<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountProcessRequest;
use App\Models\AccountProcess;
use App\Models\Pagesetting;
use App\Services\AccountProcessService;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountProcessController extends Controller
{
    public $service;

    public function __construct(AccountProcessService $service)
    {
        $this->middleware('auth:admin');
        $this->service = $service;
    }

    public function datatables()
    {
         $datas = AccountProcess::orderBy('id','desc');

         return Datatables::of($datas)
                            ->editColumn('details', function(AccountProcess $data) {
                                $details = mb_strlen(strip_tags($data->details),'utf-8') > 100 ? mb_substr(strip_tags($data->details),0,100,'utf-8').'...' : strip_tags($data->details);
                                return  $details;
                            })
                            ->addColumn('action', function(AccountProcess $data) {

                              return '<div class="btn-group mb-1">
                              <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                '.'Actions' .'
                              </button>
                              <div class="dropdown-menu" x-placement="bottom-start">
                                <a href="' . route('admin.account.process.edit',$data->id) . '"  class="dropdown-item">'.__("Edit").'</a>
                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="'.  route('admin.account.process.delete',$data->id).'">'.__("Delete").'</a>
                              </div>
                            </div>';
                            })
                            ->rawColumns(['action','details'])
                            ->toJson();
    }

    public function index()
    {
        $data = Pagesetting::first();
        return view('admin.account.index',compact('data'));
    }

    public function create()
    {
        return view('admin.account.create');
    }

    public function store(AccountProcessRequest $request)
    {
        $this->service->store($request->all());

        $msg = 'New Data Added Successfully.'.'<a href="'.route("admin.account.process.index").'">Account Process Lists</a>';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = AccountProcess::findOrFail($id);
        return view('admin.account.edit',compact('data'));
    }

    public function update(AccountProcessRequest $request, $id)
    {
        $this->service->update($request->all(),$id);

        $msg = 'Data Updated Successfully.'.'<a href="'.route("admin.account.process.index").'">Account Process Lists</a>';
        return response()->json($msg);
    }

    public function destroy($id)
    {
        $this->service->destroy($id);

        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }
}
