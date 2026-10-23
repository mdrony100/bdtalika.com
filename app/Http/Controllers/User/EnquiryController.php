<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ListingEnquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function customerEnquiry(){
        $data['enquires'] = ListingEnquiry::whereListingOwnerId(auth()->id())->orderBy('id','desc')->paginate(6);
        return view('user.enquiry.customer',$data);
    }

    public function myEnquiry(){
        $data['enquires'] = ListingEnquiry::whereUserId(auth()->id())->orderBy('id','desc')->paginate(6);
        return view('user.enquiry.my',$data);
    }
}
