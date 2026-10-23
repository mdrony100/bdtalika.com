<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Datatables;
use App\Models\AdminLanguage;
use Illuminate\Support\Str;
use App;
use App\Http\Requests\AdminLanguageRequest;
use Illuminate\Support\Facades\Validator;

class AdminLanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables()
    {
         $datas = AdminLanguage::orderBy('id','desc');

         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('action', function(AdminLanguage $data) {

                                $delete = $data->id == 1 ? '':'<a href="javascript:;" data-href="' . route('admin.tlang.delete',$data->id) . '" data-toggle="modal" data-target="#deleteModal" class="dropdown-item">'.__("Delete").'</a>';
                                $default = $data->is_default == 1 ? '<a href="javascript:;" class="dropdown-item">'.__("Default").'</a>' : '<a class="status dropdown-item" href="javascript:;" data-href="' . route('admin.tlang.st',['id1'=>$data->id,'id2'=>1]) . '">'.__('Set Default').'</a>';

                                return '<div class="btn-group mb-1">
                              <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                '.'Actions' .'
                              </button>
                              <div class="dropdown-menu" x-placement="bottom-start">
                                <a href="' . route('admin.tlang.edit',$data->id) . '"  class="dropdown-item">'.__("Edit").'</a>'.$delete.$default.'

                              </div>
                            </div>';
                            })
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        return view('admin.adminlanguage.index');
    }

    public function create()
    {
        $data_results = file_get_contents(resource_path().'/lang/'.'dadmin.json');
        $lang = json_decode($data_results, true);

        return view('admin.adminlanguage.create',compact('lang'));
    }

    public function store(AdminLanguageRequest $request)
    {
        $new = null;
        $input = $request->all();
        $data = new AdminLanguage();
        $data->language = $input['language'];
        $name = time().Str::random(8);
        $data->name = $name;
        $data->file = $name.'.json';
        $data->rtl = $input['rtl'];
        $isExist = AdminLanguage::where('is_default',1)->exists();
        if(!$isExist){
            $data->is_default = 1;
        }
        $data->save();
        unset($input['_token']);
        unset($input['language']);

        $keys = $request->keys;
        $values = $request->values;

        foreach(array_combine($keys,$values) as $key => $value)
        {
            $n = str_replace("_"," ",$key);
            $new[$n] = $value;
        }
        $mydata = json_encode($new);
        file_put_contents(resource_path().'/lang/'.$data->file, $mydata);

        $msg = 'New Data Added Successfully.'.' '.'<a href="'.route('admin.tlang.index').'"> '.__('View Lists.').'</a>';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = AdminLanguage::findOrFail($id);
        $data_results = file_get_contents(resource_path().'/lang/'.$data->file);
        $lang = json_decode($data_results, true);
        return view('admin.adminlanguage.edit',compact('data','lang'));
    }

    public function update(AdminLanguageRequest $request, $id)
    {
        $new = null;
        $input = $request->all();
        $data = AdminLanguage::findOrFail($id);

        $data->update();
        unset($input['_token']);
        unset($input['language']);
        $keys = $request->keys;
        $values = $request->values;
        foreach(array_combine($keys,$values) as $key => $value)
        {
            $n = str_replace("_"," ",$key);
            $new[$n] = $value;
        }
        $mydata = json_encode($new);
        file_put_contents(resource_path().'/lang/'.$data->file, $mydata);

        $msg = 'Data Updated Successfully.'.' '.'<a href="'.route('admin.tlang.index').'"> '.__('View Lists.').'</a>';
        return response()->json($msg);
    }

    public function status($id1,$id2)
    {
        $data = AdminLanguage::findOrFail($id1);
        $data->is_default = $id2;
        $data->update();
        $data = AdminLanguage::where('id','!=',$id1)->update(['is_default' => 0]);

        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function destroy($id)
    {
        if($id == 1)
        {
            return "You don't have access to remove this language.";
        }
        $data = AdminLanguage::findOrFail($id);
        if($data->is_default == 1)
        {
            return "You can not remove default language.";
        }
        if (file_exists(public_path().'/project/resources/lang/'.$data->file)) {
            @unlink(resource_path().'/lang/'.$data->file);
        }
        $data->delete();

        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }
}
