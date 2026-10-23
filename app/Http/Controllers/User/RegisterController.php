<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Country;
use App\Models\BankPlan;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\ReferralBonus;
use App\Models\Generalsetting;
use Illuminate\Support\Carbon;
use App\Models\UserSubscription;
use App\Services\AdminNotificationService;
use App\Services\BangladeshPhoneService;
use PHPMailer\PHPMailer\PHPMailer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\RegisterCaptchaController;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegisterForm()
    {
        $data['countries'] = Country::where('status', 1)->get();
        return view('user.register', $data);
    }

    // public function register(Request $request)
    // {
    //     $value = session('captcha_string');
    //     if ($request->codes != $value) {
    //         return response()->json(['errors' => ['Please enter the correct Captcha Code.']]);
    //     }

    //     $rules = [
    //         'name'          => 'required',
    //         'username'      => 'required|alpha_dash|min:5|unique:users',
    //         'email'         => 'required|email|max:255|unique:users',
    //         'phone'         => ['required', 'regex:/^(01[3-9])[0-9]{8}$/'],
    //         'password'      => 'required|min:6|confirmed',
    //         'captchafield'  => 'required|numeric'  // Validate the captcha field as numeric
    //     ];
        
    //     $validator = Validator::make($request->all(), $rules);
        
    //     $cvalidate = RegisterCaptchaController::validate($request);
        
    //     if ($cvalidate !== true) {
    //         return response()->json(['errors' => ['captchafield' => 'Invalid captcha, please try again']]);
    //     }
        
        
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
    //     }

    //     $gs = Generalsetting::findOrFail(1);

    //     $user = new User;
    //     $input = $request->all();
    //     $input['user_password'] = $request['password'];
    //     $input['password'] = bcrypt($request['password']);
    //     $token = md5(time() . $request->name . $request->email);
    //     $input['verification_link'] = $token;
    //     $input['affilate_code'] = md5($request->name . $request->email);
    //     $input['status'] = 1;
    //     $user['plan_id'] = 5;
    //     $user['ad_limit'] = 999999999;
    //     $user['plan_end_date'] = date('Y-m-d H:i:s', strtotime('+10 years'));
    //     $user->fill($input)->save();

    //     // Send verification email if required
    //     if ($gs->is_verification_email == 1) {
    //         $verificationLink = "Simply click here to verify: " . htmlspecialchars(url('user/register/verify/' . $token));
    //         $to = $request->email;
    //         $subject = 'Verify your email address.';
    //         $headers = "MIME-Version: 1.0\r\n";
    //         $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    //         $msg = "Dear Customer, We noticed that you need to verify your email address. $verificationLink";

    //         if ($gs->is_smtp == 1) {
    //             $mail = new PHPMailer(true);
    //             try {
    //                 $mail->isSMTP();
    //                 $mail->Host = $gs->smtp_host;
    //                 $mail->SMTPAuth = true;
    //                 $mail->Username = $gs->smtp_user;
    //                 $mail->Password = $gs->smtp_pass;
    //                 $mail->SMTPSecure = $gs->smtp_encryption == 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    //                 $mail->Port = $gs->smtp_port;
    //                 $mail->CharSet = 'UTF-8';
    //                 $mail->setFrom($gs->from_email, $gs->from_name);
    //                 $mail->addAddress($user->email, $user->name);
    //                 $mail->addReplyTo($gs->from_email, $gs->from_name);
    //                 $mail->isHTML(true);
    //                 $mail->Subject = $subject;
    //                 $mail->Body = $msg;
    //                 $mail->send();
    //             } catch (Exception $e) {
    //                 // Handle exception
    //             }
    //         } else {
    //             $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
    //             mail($to, $subject, $msg, $headers);
    //         }
    //         return response()->json('We need to verify your email address. We have sent an email to ' . $to . ' to verify your email address. Please click link in that email to continue. If you do not find email, please check spam/junk folder.');
    //     } else {
    //         // Additional actions and welcome email if no verification is required
    //         if (Session::has('affilate')) {
    //             $referral = User::findOrFail(Session::get('affilate'));
    //             $user->referral_id = $referral->id;
    //             $user->update();
    //             session()->forget('affliate_visited');
    //         }

    //         if ($gs->is_affilate == 1 && Session::has('affilate')) {
    //             $mainUser = User::findOrFail(Session::get('affilate'));
    //             $mainUser->balance += $gs->affilate_user;
    //             $mainUser->update();

    //             $user->balance += $gs->affilate_new_user;
    //             $user->update();

    //             $bonus = new ReferralBonus();
    //             $bonus->from_user_id = $user->id;
    //             $bonus->to_user_id = $mainUser->id;
    //             $bonus->amount = $gs->affilate_user;
    //             $bonus->type = 'Register';
    //             $bonus->save();

    //             $mainUserTrans = new Transaction();
    //             $mainUserTrans->email = $mainUser->email;
    //             $mainUserTrans->amount = $gs->affilate_user;
    //             $mainUserTrans->type = "Referral Bonus";
    //             $mainUserTrans->profit = "plus";
    //             $mainUserTrans->txnid = Str::random(12);
    //             $mainUserTrans->user_id = $mainUser->id;
    //             $mainUserTrans->save();

    //             $newUserTrans = new Transaction();
    //             $newUserTrans->email = $user->email;
    //             $newUserTrans->amount = $gs->affilate_user;
    //             $newUserTrans->type = "Referral Bonus";
    //             $newUserTrans->profit = "plus";
    //             $newUserTrans->txnid = Str::random(12);
    //             $newUserTrans->user_id = $user->id;
    //             $newUserTrans->save();
    //         }

    //         $user->email_verified = 'Yes';
    //         $user->update();

    //         if ($gs->is_smtp == 1) {
    //             $data = [
    //                 'to' => $user->email,
    //                 'type' => "welcome",
    //                 'cname' => $user->name,
    //                 'oamount' => "",
    //                 'aname' => "",
    //                 'aemail' => "",
    //                 'wtitle' => "",
    //             ];

    //             $mailer = new GeniusMailer();
    //             $mailer->sendAutoMail($data);
    //         } else {
    //             $to = $user->email;
    //             $subject = "Welcome to our Bdtalika.com";
    //             $msg = "Hello " . $user->name . "!\nYour registration was successfully completed.\nThank you.";
    //             $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
    //             mail($to, $subject, $msg, $headers);
    //         }

    //         $notification = new Notification();
    //         $notification->user_id = $user->id;
    //         $notification->save();
    //         Auth::guard('web')->login($user);
    //         return response()->json(1);
    //     }
    // }


    public function register(Request $request)
            {
                $value = session('captcha_string');
                if ($request->codes != $value) {
                    return response()->json(['errors' => ['Please enter the correct Captcha Code.']]);
                }

                $rules = [
                    'name' => 'required',
                    'username' => 'required|alpha_dash|min:5|unique:users',
                    'email' => 'required|email|max:255|unique:users',
                    'phone' => ['required', function ($attribute, $value, $fail) {
                        if (!BangladeshPhoneService::normalize($value)) {
                            $fail('Please enter a valid Bangladesh mobile number.');
                        }
                    }],
                    'whatsapp_opt_in' => 'sometimes|accepted',
                    'password' => 'required|min:6|confirmed',
                    'captchafield' => 'required|numeric'
                ];

                $validator = Validator::make($request->all(), $rules);

                $cvalidate = RegisterCaptchaController::validate($request);
                if ($cvalidate !== true) {
                    return response()->json(['errors' => ['captchafield' => 'Invalid captcha, please try again']]);
                }

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
                }

                $gs = Generalsetting::findOrFail(1);

                $user = new User;
                $input = $request->all();
                $input['phone'] = BangladeshPhoneService::normalize($request->phone);
                $input['whatsapp_opt_in'] = $request->boolean('whatsapp_opt_in');
                $input['whatsapp_opt_in_at'] = $input['whatsapp_opt_in'] ? now() : null;
                $input['user_password'] = $request['password'];
                $input['password'] = bcrypt($request['password']);
                $token = md5(time() . $request->name . $request->email);
                $input['verification_link'] = $token;
                $input['affilate_code'] = md5($request->name . $request->email);
                $input['status'] = 1;
                $user['plan_id'] = 5;
                $user['ad_limit'] = 999999999;
                $user['plan_end_date'] = date('Y-m-d H:i:s', strtotime('+10 years'));
                $user->fill($input)->save();
                app(AdminNotificationService::class)->newUserRegistered($user);

                // Send verification email (only using native mail())
                // if ($gs->is_verification_email == 1) {
                //     $verificationLink = "Simply click here to verify: " . htmlspecialchars(url('user/register/verify/' . $token));
                //     $to = $request->email;
                //     $subject = 'Verify your email address.';
                //     $msg = "Dear Customer,<br><br>We noticed that you need to verify your email address.<br><br>$verificationLink<br><br>Thanks.";
                //     $headers = "MIME-Version: 1.0\r\n";
                //     $headers .= "Content-type:text/html;charset=UTF-8\r\n";
                //     $headers .= "From: " . $gs->from_name . " <" . $gs->from_email . ">";

                //     mail($to, $subject, $msg, $headers);

                //     return response()->json('We need to verify your email address. We have sent an email to ' . $to . '. Please click the link in that email to continue. If you do not find the email, please check spam/junk folder.');
                // }

                if ($gs->is_verification_email == 1) {
                    $verificationLink = "Simply click here to verify: " . htmlspecialchars(url('user/register/verify/' . $token));

                    $mailData = [
                        'to' => $request->email,
                        'title' => 'Account Verification',
                        'subject' => 'Verify your email address.',
                        'body' => "Dear Customer,<br><br>We noticed that you need to verify your email address.<br><br>$verificationLink<br><br>Thanks.",
                    ];

                    try {
                        $mailer = new GeniusMailer();
                        $mailer->sendVerificationMail($mailData);
                    } catch (\Throwable $e) {
                        Log::error('Verification email failed during registration', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    return response()->json('We need to verify your email address. We have sent an email to ' . $request->email . '. Please click the link in that email to continue. If you do not find the email, please check spam/junk folder.');
                }

                // Skip email verification if disabled
                if (Session::has('affilate')) {
                    $referral = User::findOrFail(Session::get('affilate'));
                    $user->referral_id = $referral->id;
                    $user->update();
                    session()->forget('affliate_visited');
                }

                if ($gs->is_affilate == 1 && Session::has('affilate')) {
                    $mainUser = User::findOrFail(Session::get('affilate'));
                    $mainUser->balance += $gs->affilate_user;
                    $mainUser->update();

                    $user->balance += $gs->affilate_new_user;
                    $user->update();

                    ReferralBonus::create([
                        'from_user_id' => $user->id,
                        'to_user_id' => $mainUser->id,
                        'amount' => $gs->affilate_user,
                        'type' => 'Register',
                    ]);

                    Transaction::create([
                        'email' => $mainUser->email,
                        'amount' => $gs->affilate_user,
                        'type' => 'Referral Bonus',
                        'profit' => 'plus',
                        'txnid' => Str::random(12),
                        'user_id' => $mainUser->id,
                    ]);

                    Transaction::create([
                        'email' => $user->email,
                        'amount' => $gs->affilate_user,
                        'type' => 'Referral Bonus',
                        'profit' => 'plus',
                        'txnid' => Str::random(12),
                        'user_id' => $user->id,
                    ]);
                }

                $user->email_verified = 'Yes';
                $user->update();

                // Welcome email using mail()
                // $to = $user->email;
                // $subject = "Welcome to Bdtalika.com";
                // $msg = "Hello " . $user->name . "!<br><br>Your registration was successful.<br><br>Thank you.";
                // $headers = "MIME-Version: 1.0\r\n";
                // $headers .= "Content-type:text/html;charset=UTF-8\r\n";
                // $headers .= "From: " . $gs->from_name . " <" . $gs->from_email . ">";

                // mail($to, $subject, $msg, $headers);

                try {
                    $data = [
                        'name' => $user->name,
                    ];

                    Mail::send('emails.welcome', $data, function ($message) use ($user, $gs) {
                        $message->from($gs->from_email, $gs->from_name);
                        $message->to($user->email, $user->name);
                        $message->subject("Welcome to Bdtalika.com");
                    });
                } catch (\Throwable $e) {
                    Log::error('Welcome email failed during registration', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'error' => $e->getMessage(),
                    ]);
                }

                Notification::create(['user_id' => $user->id]);
                Auth::guard('web')->login($user);

                return response()->json(1);
            }



    // public function token($token)
    // {
    //     $gs = Generalsetting::findOrFail(1);
    //     if ($gs->is_verification_email == 1) {
    //         $user = User::where('verification_link', '=', $token)->first();
    //         if (isset($user)) {
    //             $user->email_verified = 'Yes';
    //             $user->update();
    //             if (Session::has('affilate')) {
    //                 $referral = User::findOrFail(Session::get('affilate'));
    //                 $user->referral_id = $referral->id;
    //                 $user->update();
    //             }

    //             if ($gs->is_affilate == 1 && Session::has('affilate')) {
    //                 $mainUser = $referral;
    //                 $mainUser->balance += $gs->affilate_user;
    //                 $mainUser->update();

    //                 $user->balance += $gs->affilate_new_user;
    //                 $user->update();
    //             }


    //             $notification = new Notification;
    //             $notification->user_id = $user->id;
    //             $notification->save();

    //             if ($gs->is_smtp == 1) {
    //                 $data = [
    //                     'to' => $user->email,
    //                     'type' => "welcome",
    //                     'cname' => $user->name,
    //                     'oamount' => "",
    //                     'aname' => "",
    //                     'aemail' => "",
    //                     'wtitle' => "",
    //                 ];

    //                 $mailer = new GeniusMailer();
    //                 $mailer->sendAutoMail($data);
    //             } else {
    //                 $to = $user->email;
    //                 $subject = "Welcome to our website";
    //                 $msg = "Hello " . $user->name . "!\nYour registration successfully completed.\nThank you.";
    //                 $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
    //                 mail($to, $subject, $msg, $headers);
    //             }

    //             Auth::guard('web')->login($user);
    //             return redirect()->route('user.dashboard')->with('success', 'Email Verified Successfully');
    //         }
    //     } else {
    //         return redirect()->back();
    //     }
    // }

    public function token($token)
    {
        $gs = Generalsetting::findOrFail(1);
        if ($gs->is_verification_email == 1) {
            $user = User::where('verification_link', '=', $token)->first();
            if (isset($user)) {
                $user->email_verified = 'Yes';
                $user->update();
                if (Session::has('affilate')) {
                    $referral = User::findOrFail(Session::get('affilate'));
                    $user->referral_id = $referral->id;
                    $user->update();
                }

                if ($gs->is_affilate == 1 && Session::has('affilate')) {
                    $mainUser = $referral;
                    $mainUser->balance += $gs->affilate_user;
                    $mainUser->update();

                    $user->balance += $gs->affilate_new_user;
                    $user->update();
                }


                $notification = new Notification;
                $notification->user_id = $user->id;
                $notification->save();

                if ($gs->is_smtp == 1) {
                    $data = [
                        'to' => $user->email,
                        'type' => "welcome",
                        'cname' => $user->name,
                        'oamount' => "",
                        'aname' => "",
                        'aemail' => "",
                        'wtitle' => "",
                    ];

                    $mailer = new GeniusMailer();
                    $mailer->sendAutoMail($data);
                } else {
                    $to = $user->email;
                    $subject = "Welcome to our website";
                    $msg = "Hello " . $user->name . "!\nYour registration successfully completed.\nThank you.";
                    $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                    mail($to, $subject, $msg, $headers);
                }

                Auth::guard('web')->login($user);
                return redirect()->route('user.dashboard')->with('success', 'Email Verified Successfully');
            }
        } else {
            return redirect()->back();
        }
    }
}
