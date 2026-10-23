<?php

namespace App\Http\Controllers\User;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingConversation;
use App\Models\Generalsetting;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function hotel(){
        $data['hotels'] = Booking::whereType('hotel')->whereOwnerType('user')->whereListingOwnerId(auth()->id())->orderBy('id','desc')->get();
        return view('user.booking.hotel',$data);
    }

    public function restaurant(){
        $data['restaurants'] = Booking::whereType('restaurant')->whereOwnerType('user')->whereListingOwnerId(auth()->id())->orderBy('id','desc')->get();
        return view('user.booking.restaurant',$data);
    }

    public function beauty(){
        $data['beauties'] = Booking::whereType('beauty')->whereOwnerType('user')->whereListingOwnerId(auth()->id())->orderBy('id','desc')->get();
        return view('user.booking.beauty',$data);
    }

    public function status($id1,$id2)
    {
        $data = Booking::findOrFail($id1);
        $gs = Generalsetting::findOrFail(1);
        $user = User::findOrFail($data->user_id);
        $owner = User::findOrFail($data->listing_owner_id);

        $data->status = $id2;
        $data->update();

        if($id2 == 1){
            if($gs->is_smtp == 1)
            {
                $data = [
                    'to' => $user->email,
                    'type' => "directory approved",
                    'cname' => $user->name,
                    'oamount' => $data->price,
                    'aname' => $owner->name,
                    'aemail' => $owner->email,
                    'wtitle' => "",
                ];

                $mailer = new GeniusMailer();
                $mailer->sendAutoMail($data);
            }
            else
            {
                $to = $user->email;
                $subject = " Your listing is approved.";
                $msg = "Hello ".$user->name."!\nYour listing is approved by".$owner->name. ".\nThank you.";
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                mail($to,$subject,$msg,$headers);
            }
        }else{
            if($gs->is_smtp == 1)
            {
                $data = [
                    'to' => $user->email,
                    'type' => "directory rejected",
                    'cname' => $user->name,
                    'oamount' => $data->price,
                    'aname' => $owner->name,
                    'aemail' => $owner->email,
                    'wtitle' => "",
                ];

                $mailer = new GeniusMailer();
                $mailer->sendAutoMail($data);
            }
            else
            {
                $to = $user->email;
                $subject = " Your Listing is Rejected";
                $msg = "Hello ".$user->name."!\nYour listing is rejected by".$owner->name. ". Please check your dashboard for more details.\nThank you.";
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                mail($to,$subject,$msg,$headers);
            }
        }

        return back()->with('message','Status updated successfully');
    }

    public function conversation($id){
        $data['messages'] = BookingConversation::whereBookingId($id)->get();
        $data['listing'] = Booking::findOrFail($id)->listing;
        $data['conversation_id'] = $id;

        return view('user.booking.conversion',$data);
    }

    public function conversationStore(Request $request,$id){
        $listing = Booking::findOrFail($id)->listing;
        $data = new BookingConversation();

        if($listing->user_id != 0 && $listing->user_id == auth()->id()){
            $data->owner_id = auth()->id();
            $data->user_id = 0;
        }elseif($listing->user_id == NULL && $listing->admin_id == NULL){
            $data->owner_id = 0;
            $data->user_id = auth()->id();
        }else{
            $data->owner_id = 0;
            $data->user_id = auth()->id();
        }
        $data->booking_id = $id;
        $data->message = $request->message;
        if ($file = $request->file('photo'))
        {
           $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
           $file->move('assets/images',$name);
           $data->photo = $name;
        }

        $data->save();

        return back()->with('message','Data added successfully');
    }
}
