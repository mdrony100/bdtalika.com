<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data['plans'] = Plan::whereStatus(1)->get();
        return view('user.packages.index',$data);
    }

    public function subscription($id){ 
        $data['data'] = Plan::findOrFail($id);
        $data['gateways'] = PaymentGateway::OrderBy('id','desc')->whereStatus(1)->get();

        return view('frontend.plan_details',$data);
    }
}
