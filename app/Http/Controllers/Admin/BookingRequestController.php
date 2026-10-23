<?php

namespace App\Http\Controllers\Admin;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingConversation;
use App\Models\Generalsetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Str;

class BookingRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables(Request $request){
        $datas = Booking::whereType($request->type)->orderBy('id','desc');

        return Datatables::of($datas)
                            ->addColumn('checkbox',function(Booking $data){
                                return $checkbox = $data->id ? '<input type="checkbox" class="form-check-input m-0 p-0 columnCheck" value="'.$data->id.'">':'';
                            })
                            ->editColumn('checkin_date', function(Booking $data) {
                                $checkingDate = Carbon::parse($data->checkin_date)->format('d M Y');
                                $checkoutDate = Carbon::parse($data->checkout_date) != NULL ? Carbon::parse($data->checkin_date)->format('d M Y') : '';

                                if($data->checkout_date != NULL){
                                    return $checkingDate.'--'.$checkoutDate;
                                }else{
                                    return $checkingDate;
                                }
                            })
                            ->editColumn('listing_id', function(Booking $data) {
                                return $data->listing != NULL ? $data->listing->name : __('Directory Deleted');
                            })
                            ->editColumn('user_id', function(Booking $data) {
                                if($data->user != NULL && $data->type == 'hotel'){
                                    return '<div>
                                                <p><strong>Name : </strong>'.$data->user->name.'</p>
                                                <p><strong>Email : </strong>'.$data->user->email.'</p>
                                                <p><strong>Phone : </strong>'.$data->user->phone.'</p>
                                                <p><strong>Room Type : </strong>'.$data->room_type.'</p>
                                                <p><strong>Adult Guest : </strong>'.$data->adults.'</p>
                                                <p><strong>Child Guest : </strong>'.$data->kids.'</p>
                                        </div>';
                                }elseif($data->user != NULL && $data->type == 'restaurant'){
                                    return '<div>
                                                <p><strong>Name : </strong>'.$data->user->name.'</p>
                                                <p><strong>Email : </strong>'.$data->user->email.'</p>
                                                <p><strong>Phone : </strong>'.$data->user->phone.'</p>
                                                <p><strong>Time : </strong>'.$data->time.'</p>
                                                <p><strong>Adult Guest : </strong>'.$data->adults.'</p>
                                                <p><strong>Child Guest : </strong>'.$data->kids.'</p>
                                        </div>';
                                }else{
                                    return '<div>
                                                <p><strong>Name : </strong>'.$data->user->name.'</p>
                                                <p><strong>Email : </strong>'.$data->user->email.'</p>
                                                <p><strong>Phone : </strong>'.$data->user->phone.'</p>
                                                <p><strong>Service Name : </strong>'.$data->service->service_name.'</p>
                                                <p><strong>Note : </strong>'.$data->note.'</p>
                                        </div>';
                                }
                            })
                            ->editColumn('status', function(Booking $data) {
                                if($data->status == 0){
                                    $status = __('Pending');
                                    $status_sign = 'warning';
                                }elseif($data->status == 1){
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
                                            <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.booking.status',['id1' => $data->id, 'id2' => 0]).'">'.__("Pending").'</a>
                                            <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.booking.status',['id1' => $data->id, 'id2' => 1]).'">'.__("Approved").'</a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="'. route('admin.booking.status',['id1' => $data->id, 'id2' => 2]).'">'.__("Reject").'</a>
                                            </div>
                                        </div>';
                            })

                            ->addColumn('action', function(Booking $data) {

                              return '<div class="btn-group mb-1">
                                <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  '.'Actions' .'
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start">
                                  <a href="'.route('admin.booking.message',$data->id).'"  class="dropdown-item">'.__("Message").'</a>
                                  <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="'.  route('admin.booking.delete',$data->id).'">'.__("Delete").'</a>
                                </div>
                              </div>';

                            })
                            ->rawColumns(['checkbox','checkin_date','listing_id','user_id','status','action'])
                            ->toJson();
    }

    public function hotel(){
        return view('admin.booking.hotel');
    }

    public function restaurant(){
        return view('admin.booking.restaurant');
    }

    public function beauty(){
        return view('admin.booking.beauty');
    }

    public function status($id1,$id2)
    {
        $data = Booking::findOrFail($id1);
        $gs = Generalsetting::first();

        if($data->status == 2){
            return response()->json('Booking was rejected!');
        }

        if($id2 == 0){
            return response()->json('Data updated successfully');
        }elseif($id1 == 1){
            $data->status = $id2;
            $data->update();

            if($gs->is_smtp == 1)
            {
                $data = [
                    'to' => $data->user->email,
                    'type' => "booking approved",
                    'cname' => $data->user->name,
                    'oamount' => "",
                    'aname' => "",
                    'aemail' => "",
                    'wtitle' => "",
                ];

                $mailer = new GeniusMailer();
                $mailer->sendAutoMail($data);
            }
            else
            {
                $to = $data->user->email;
                $subject = "Your Booking request has been accepted";
                $msg = "Hello ".$data->user->name."!\nYour Booking request has been accepted.\nThank you.";
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                mail($to,$subject,$msg,$headers);
            }
        }else{
            $data->status = $id2;
            $data->update();

            if($gs->is_smtp == 1)
            {
                $data = [
                    'to' => $data->user->email,
                    'type' => "booking rejected",
                    'cname' => $data->user->name,
                    'oamount' => "",
                    'aname' => "",
                    'aemail' => "",
                    'wtitle' => "",
                ];

                $mailer = new GeniusMailer();
                $mailer->sendAutoMail($data);
            }
            else
            {
                $to = $data->user->email;
                $subject = "Your Booking request has been rejected";
                $msg = "Hello ".$data->user->name."!\nYour Booking request has been rejected.\nThank you.";
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                mail($to,$subject,$msg,$headers);
            }
        }


        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function message($id){
        $data['messages'] = BookingConversation::whereBookingId($id)->get();
        $data['listing'] = Booking::findOrFail($id)->listing;
        $data['conversation_id'] = $id;

        return view('admin.booking.conversion',$data);
    }

    public function messageSubmit(Request $request,$id){
        $listing = Booking::findOrFail($id)->listing;
        $data = new BookingConversation();
        $data->user_id = 0;
        $data->admin_id = 1;
        $data->owner_id = 0;

        $data->booking_id = $id;
        $data->message = $request->message;
        if ($file = $request->file('photo'))
        {
           $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
           $file->move('assets/images',$name);
           $data->photo = $name;
        }

        $data->save();

        return redirect()->route('admin.booking.message',$id)->with('message','Data added successfully');
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
            Booking::findOrFail($id)->delete();
            $msg = 'Data Deleted Successfully.';
        } catch (\Throwable $th) {
            $msg = 'Something went wrong.';
        }
        return response()->json($msg);
    }

}
