<?php

namespace App\Http\Controllers\Subscription;

use App\Repositories\SubscriptionRepository;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\PaymentGateway;
use App\Models\UserSubscription;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PaypalController extends Controller
{
    private $_api_context;
    public $subscriptionRepositorty;
    public $gateway;

    public function __construct(SubscriptionRepository $subscriptionRepositorty)
    {
        $data = PaymentGateway::whereKeyword('paypal')->first();
        $paydata = $data->convertAutoData();
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId($paydata['client_id']);
        $this->gateway->setSecret($paydata['client_secret']);
        $this->gateway->setTestMode(true);
        $this->subscriptionRepositorty = $subscriptionRepositorty;
    }

    public function store(Request $request){
        $settings = Generalsetting::findOrFail(1);

        $return_url = route('front.index');
        $cancel_url = route('subscription.paypal.cancel');
        $notify_url = route('subscription.paypal.notify');

        $item_name = $settings->title." Subscription";
        $item_amount = $request->price;
        $item_number = Str::random(12);

        $support = ['USD','EUR'];
        if(!in_array($request->currency_code,$support)){
            return back()->with('warning','Please Select USD Or EUR Currency For Paypal.');
        }

        $addionalData = ['subscription_number'=>$item_number];
        $this->subscriptionRepositorty->order($request,'pending',$addionalData);

        try {
            $response = $this->gateway->purchase(array(
                'amount' => $item_amount,
                'currency' => $request->currency_code,
                'returnUrl' => $notify_url,
                'cancelUrl' => $cancel_url,
            ))->send();

            if ($response->isRedirect()) {
               
                Session::put('paypal_data',$request->all());
                Session::put('order_number',$item_number);

                if ($response->redirect()) {
                    /** redirect to paypal **/
                    return redirect($response->redirect());
                }
            } else {
                return redirect()->back()->with('warning', $response->getMessage());

            }
        } catch (\Throwable$th) {

            return redirect()->back()->with('warning', $response->getMessage());
        }

    }

    public function notify(Request $request)
    {

        $responseData = $request->all();
        if (empty( $request['PayerID']) || empty( $request['token'])) {
            return back()->with('error', 'Payment Failed');
        }

        $transaction = $this->gateway->completePurchase(array(
            'payer_id' => $responseData['PayerID'],
            'transactionReference' => $responseData['paymentId'],
        ));
        $response = $transaction->send();

        $order_number = Session::get('order_number');

        if ($response->isSuccessful()) {
         
            $subscription = UserSubscription::where('subscription_number',$order_number)->where('status','pending')->first();
            $subscription->status = 1;
            $subscription->txnid = $response->getData()['transactions'][0]['related_resources'][0]['sale']['id'];
            $subscription->update();
            $this->subscriptionRepositorty->callAfterOrder($request,$subscription);
            Session::forget('paypal_data');
            Session::forget('order_number');
            return redirect()->route('user.dashboard')->with('message','Plan Updated Successfully');
        }
    }

    public function cancel(){
        return redirect()->route('user.dashboard')->with('error','Something went wrong!');
    }
}
