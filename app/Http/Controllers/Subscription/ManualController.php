<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManualController extends Controller
{
    public $subscriptionRepositorty;

    public function __construct(SubscriptionRepository $subscriptionRepositorty)
    {
        $this->subscriptionRepositorty = $subscriptionRepositorty;
    }


    public function store(Request $request){

        $settings = Generalsetting::findOrFail(1);
        $item_name = $settings->title." Subscription";

        $item_amount = $request->price;
        $item_number = Str::random(12);

        $addionalData = ['subscription_number'=>$item_number];
        $this->subscriptionRepositorty->order($request,'pending',$addionalData);

        return redirect()->route('user.dashboard')->with('message','Manual payment request sent!');
    }
}
