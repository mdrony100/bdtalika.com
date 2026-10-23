<?php
namespace App\Repositories;

use App\Classes\GeniusMailer;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class SubscriptionRepository{
    public $gs;

    public function __construct()
    {
        $this->gs = Generalsetting::findOrFail(1);
    }

    public function order($request,$status,$addionalData){
        $subscription = new UserSubscription();

        if($request['currency_id']){
            $currencyValue = Currency::where('id',$request['currency_id'])->first();
        }

        if($request['currency_id']){
            $subscription->price = $request['price']/$currencyValue->value;
        }

        if(isset($addionalData['subscription_number'])){
            $subscription->subscription_number = $addionalData['subscription_number'];
        }

        $subscription->user_id = $request['user_id'];
        $subscription->plan_id = $request['plan_id'];
        $subscription->currency_id = $request['currency_id'];
        $subscription->method = $request['method'];
        $subscription->days = $request['days'];


        if(isset($status) && $status == 'complete'){
            $subscription->status = 1;
        }else{
            $subscription->status = 0;
        }

        if(isset($addionalData['txnid'])){
            $subscription->txnid = $addionalData['txnid'];
        }

        $subscription->save();

        if($status == 'complete'){
            $this->callAfterOrder($request,$subscription);
        }
    }

    public function OrderFromSession($request,$status,$addionalData){
        $input = Session::get('input_data');

        $subscription = new UserSubscription();

        if($input['currency_id']){
            $currencyValue = Currency::where('id',$input['currency_id'])->first();
        }

        if($input['currency_id']){
            $subscription->price = $input['price']/$currencyValue->value;
        }else{
            $subscription->price = $input['price'];
        }

        if(isset($addionalData['subscription_number'])){
            $subscription->subscription_number = $addionalData['subscription_number'];
        }

        $subscription->user_id = $input['user_id'];
        $subscription->plan_id = $input['plan_id'];
        $subscription->currency_id = $input['currency_id'];
        $subscription->method = $input['method'];
        $subscription->days = $input['days'];

        if(isset($addionalData['txnid'])){
            $subscription->txnid = $addionalData['txnid'];
        }
        $subscription->status = 1;
        $subscription->save();

        if($status == 'complete'){
            $this->callAfterOrder($request,$subscription);
        }
    }

    public function callAfterOrder($request,$subscription){
        $this->UserPlanUpdate($subscription);
        $this->createTransaction($subscription);
        $this->sendMail($subscription);
    }

    public function UserPlanUpdate($subscription){
        $user = User::findorFail($subscription->user_id);
        $days = $subscription->days;
        if($user){
            $user->plan_id = $subscription->plan_id;
            if($user->plan_end_date == NULL){
                $user->plan_end_date = Carbon::now()->addHours($days);
            }else{
                $user->plan_end_date = Carbon::parse($user->plan_end_date)->addDays($days);
            }
            $user->status = 2;
            $user->ad_limit += $subscription->plan->post_limit;
            $user->update();
        }
    }

    public function createTransaction($subscription){
        $user = User::findOrFail($subscription->user_id);
        $trans = new Transaction();
        $trans->email = $user->email;
        $trans->amount = $subscription->price;
        $trans->type = "Subscription";
        $trans->profit = "minus";
        $trans->txnid = $subscription->subscription_number;
        $trans->user_id = $user->id;
        $trans->save();
    }

    public function sendMail($subscription){
        if($this->gs->is_smtp == 1)
        {
            $data = [
                'to' => $subscription->user->email,
                'type' => "subscription",
                'cname' => $subscription->user->name,
                'oamount' => $subscription->price,
                'aname' => "",
                'aemail' => "",
                'wtitle' => "",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendAutoMail($data);
        }
        else
        {
           $to = $subscription->user->email;
           $subject = " You Purchase Plan Successfully.";
           $msg = "Hello ".$subscription->user->nam."!\nYou Purchase Plan Successfully.\nThank you.";
           $headers = "From: ".$this->gs->from_name."<".$this->gs->from_email.">";
           mail($to,$subject,$msg,$headers);
        }
    }

}
