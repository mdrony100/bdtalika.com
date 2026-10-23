<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ListingEnquiry;
use Illuminate\Http\Request;
use Datatables;

class EnquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables()
    {
        $datas = ListingEnquiry::orderBy('id','desc');

        return Datatables::of($datas)
                            ->addColumn('checkbox',function(ListingEnquiry $data){
                                return $checkbox = $data->id ? '<input type="checkbox" class="form-check-input m-0 p-0 columnCheck" value="'.$data->id.'">':'';
                            })
                            ->editColumn('created_at',function(ListingEnquiry $data){
                                return $data->created_at->format('d-m-Y h:i');
                            })
                            ->editColumn('listing_id',function(ListingEnquiry $data){
                                if($data->listing != NULL){
                                    $name = $data->listing != NULL ? $data->listing->name : __('Listing Deleted');
                                    return '<div>
                                        <p>'.$name.'</p>
                                    </div>';
                                }else{
                                    return __('Directory Deleted');
                                }
                            })
                            ->editColumn('listing_owner_id',function(ListingEnquiry $data){
                                if($data->listing_owner_id != NULL){
                                    $admin = Admin::first();
                                    return '<div>
                                        <span>'.$admin->name.'</span>
                                        <p>'.$admin->email.'</p>
                                    </div>';
                                }else{
                                    $name = $data->user != NULL ? $data->user->name : __('Owner Deleted');
                                    $email = $data->user != NULL ? $data->user->email : __('Owner Deleted');
                                    return '<div>
                                            <span>'. $name.'</span>
                                            <p>'.$email.'</p>
                                        </div>';
                                }
                            })
                            ->editColumn('user_id',function(ListingEnquiry $data){
                                return '<div>
                                            <span>'.$data->user != NULL ? $data->user->name : __('User Deleted').'</span>
                                            <p>'.$data->email.'</p>
                                            <p>'.$data->phone.'</p>
                                        </div>';
                            })
                            ->addColumn('action', function(ListingEnquiry $data) {

                                return '<div class="btn-group mb-1">
                                  <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    '.'Actions' .'
                                  </button>
                                  <div class="dropdown-menu" x-placement="bottom-start">
                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="'.  route('admin.enquiry.delete',$data->id).'">'.__("Delete").'</a>
                                  </div>
                                </div>';

                              })
                            ->rawColumns(['checkbox','listing_id','listing_owner_id','user_id','action'])
                            ->toJson();
    }

    public function index(){
        return view('admin.enquiry.index');
    }

    public function bulkdelete(Request $request){
        $ids = $request->bulk_id;
        $bulk_ids = explode(",",$ids);
        foreach($bulk_ids as $key=>$id){
            if($id){
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

    public function delete($id){
        $data = ListingEnquiry::findOrFail($id);
        $data->delete();

        return true;
    }
}
