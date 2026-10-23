<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request){
        $listing = Listing::findOrFail($request->listing_id);

        if($listing->user_id == auth()->id()){
            return redirect()->route('front.listing.details',$listing->slug)->with("warning","You can't select own directory");
        }

        $data = new Booking();
        $data->user_id = auth()->id();
        $data->listing_id = $request->listing_id;
        $data->service_id = $request->service_id;
        $data->listing_owner_id = $request->listing_owner_id;
        $data->owner_type = $request->owner_type;
        $data->checkin_date = Carbon::parse($request->checkin);
        $data->checkout_date = Carbon::parse($request->checkout);
        $data->adults = $request->adults;
        $data->kids = $request->kids;
        $data->time = $request->time;
        $data->room_type = $request->room_type;
        $data->type = $request->type;
        $data->note = $request->note;
        $data->save();

        return redirect()->route('front.listing.details',$listing->slug)->with('message','successfully');
    }

    public function myBooking(){
        $data['bookings'] = Booking::whereUserId(auth()->id())->orderBy('id','desc')->paginate(5);
        return view('user.booking.mybooking',$data);
    }

}
