<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListingReview;
use Datatables;
use Illuminate\Http\Request;

class DiretoryReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables()
    {
        $datas = ListingReview::orderBy('id','desc');

        return Datatables::of($datas)
                            ->editColumn('created_at',function(ListingReview $data){
                                return $data->created_at->format('d-m-Y h:i');
                            })
                            ->editColumn('listing_id',function(ListingReview $data){
                                if($data->listing != NULL){
                                    return '<p>'.$data->listing->name.'</p>';
                                }else{
                                    return __('Directory Deleted');
                                }
                            })
                            ->editColumn('status', function(ListingReview $data) {
                                if($data->status == 1){
                                    $status = __('Approved');
                                    $status_sign = 'success';
                                }else{
                                    $status = __('Rejected');
                                    $status_sign = 'danger';
                                }

                                return '<div class="btn-group mb-1">
                                            <button type="button" class="btn btn-'.$status_sign.' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            '.$status .'
                                            </button>
                                            <div class="dropdown-menu" x-placement="bottom-start">
                                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.diretory.review.status',['id1' => $data->id, 'id2' => 1]).'">'.__("Approved").'</a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.diretory.review.status',['id1' => $data->id, 'id2' => 2]).'">'.__("Reject").'</a>
                                            </div>
                                        </div>';
                            })
                            ->rawColumns(['listing_id','status'])
                            ->toJson();
    }

    public function index(){
        $this->seen();
        return view('admin.listingreview.index');
    }

    public function status($id1,$id2)
    {
        $data = ListingReview::findOrFail($id1);
        if($data->status == 2){
            return response()->json('Review was rejected!');
        }
        $data->status = $id2;
        $data->update();

        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function seen(){
        $reviews = ListingReview::whereView(0)->get();
        foreach ($reviews as $key => $review) {
            $review->view = 1;
            $review->save();
        }
        return;
    }
}
