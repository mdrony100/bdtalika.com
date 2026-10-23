<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaystackController extends Controller
{
    public $subscriptionRepositorty;

    public function __construct(SubscriptionRepository $subscriptionRepositorty)
    {
        $this->subscriptionRepositorty = $subscriptionRepositorty;
    }

    public function store(Request $request){
        if($request->currency_code != "NGN")
        {
            return redirect()->back()->with('unsuccess','Please Select NGN Currency For Paystack.');
        }

        $gs = Generalsetting::findOrFail(1);
        $item_name = $gs->title." Subscription";
        $item_number = Str::random(12);
        $item_amount = $request->price;

        $addionalData = ['subscription_number'=>$item_number,'txnid'=>$request->ref_id];
        $this->subscriptionRepositorty->order($request,'complete',$addionalData);

        return redirect()->route('user.dashboard')->with('message','Plan Updated Successfully');
    }
}
