<?php

namespace App\Http\Controllers\User;

use Validator;
use App\Models\User;
use App\Models\Invest;
use App\Models\Booking;
use App\Models\Deposit;
use App\Models\Listing;
use App\Models\Follower;
use App\Models\Property;
use App\Models\Withdraw;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\ListingReview;
use App\Models\ReferralBonus;
use App\Models\Generalsetting;
use App\Models\ListingEnquiry;
use App\Models\ListingAnalytics;
use App\Models\UserSubscription;
use App\Models\RecentViewsListing;
use App\Services\BangladeshPhoneService;
use Illuminate\Support\Facades\DB;
use App\Classes\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUserConversation;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['user'] = Auth::user();
        $data['active_listings'] = Listing::whereuserId(auth()->id())->whereStatus(1)->count();
        $data['view_listings'] = RecentViewsListing::whereListingOwnerId(auth()->id())->count();
        $data['total_reviews'] = ListingReview::whereListingOwnerId(auth()->id())->count();
        $data['total_bookings'] = Booking::whereListingOwnerId(auth()->id())->count();
        $data['subscriptions'] = UserSubscription::whereUserId(auth()->id())->orderBy('id', 'desc')->limit(5)->get();
        $data['followers'] = Follower::whereUserId(auth()->id())->orderBy('id', 'desc')->limit(5)->get();
        $data['listings'] = Listing::with(['plan', 'reviews'])->whereUserId(auth()->id())->orderBy('id', 'desc')->limit(5)->get();
        $data['customer_messages'] = ListingEnquiry::with('listing')->whereListingOwnerId(auth()->id())->orderBy('id', 'desc')->limit(5)->get();

        return view('user.dashboard', $data);
    }

    public function transaction(Request $request)
    {
        $user = Auth::user()->id;
        $transactions = Transaction::where('user_id', $user)
            ->orderByDesc('created_at')
            ->get(); 
        return view('user.transactions', compact('user', 'transactions'));
    }

    public function profile()
    {
        $user = Auth::user();
        $requiresWhatsappProfile = $user->social_login === 'yes'
            && !BangladeshPhoneService::isValid($user->phone);

        return view('user.profile', compact('user', 'requiresWhatsappProfile'));
    }

    public function profileupdate(Request $request)
    {
        $user = Auth::user();
        $requiresWhatsappProfile = $user->social_login === 'yes'
            && !BangladeshPhoneService::isValid($user->phone);

        $request->validate([
            'name' => $requiresWhatsappProfile ? 'required|max:255' : 'nullable|max:255',
            'phone' => [
                $requiresWhatsappProfile ? 'required' : 'nullable',
                function ($attribute, $value, $fail) use ($requiresWhatsappProfile) {
                    if (($requiresWhatsappProfile || filled($value)) && !BangladeshPhoneService::normalize($value)) {
                        $fail('Please enter a valid Bangladesh mobile number.');
                    }
                },
            ],
            'whatsapp_opt_in' => 'sometimes|accepted',
            'about' => 'max:500',
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'unique:users,email,' . $user->id
        ]);

        $input = $request->all();
        unset($input['email']);
        $input['hide_email'] = $request->input('hide_email', 0);
        $input['hide_phone'] = $request->input('hide_phone', 0);
        $input['about']      = $request->input('about');
        if ($request->filled('phone')) {
            $input['phone'] = BangladeshPhoneService::normalize($request->phone);
        }
        if (!$user->whatsapp_opt_in && $request->boolean('whatsapp_opt_in')) {
            $input['whatsapp_opt_in'] = true;
            $input['whatsapp_opt_in_at'] = now();
        }
       
        
        $data = $user;

        if ($request->has('about')) { 
            $input['about'] = $request->about;
        }
        if ($file = $request->file('breadcumb')) {
            $breadcumbname = time() . $file->getClientOriginalName();
            $file->move('assets/images/', $breadcumbname);
            @unlink('assets/images/' . $data->breadcumb);
            $input['breadcumb'] = $breadcumbname;
        }

        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('assets/images/', $name);
            @unlink('assets/images/' . $data->photo);
            $input['photo'] = $name;
            $input['is_provider'] = 0;
        }
        
        $data->update($input);
      
        $gs = Generalsetting::first();
        if ($gs->is_smtp == 1) {
            $mailData = [
                'to'        => $data->email,
                'type'      => "profile update",
                'cname'     => $data->name,
                'oamount'   => "",
                'aname'     => "",
                'aemail'    => "",
                'wtitle'    => "",
            ];
            $mailer = new GeniusMailer();
            $mailer->sendAutoMail($mailData);
        } else {
            $to = $data->email;
            $subject = "Your profile has been updated";
            $msg = "Hello " . $data->name . "!\nYour profile has been updated successfully.\nThank you.";
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }

        if ($requiresWhatsappProfile && BangladeshPhoneService::isValid($data->fresh()->phone)) {
            return redirect()->intended(route('user.dashboard'))->with('success', 'Successfully updated your profile');
        }

        return back()->with('success', 'Successfully updated your profile');
    }

    public function changePasswordForm()
    {
        return view('user.changepassword');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'cpass'     => 'required',
            'newpass'   => 'required',
            'renewpass' => 'required',
        ], [
            'cpass.required'     => 'Current Password field is required',
            'newpass.required'   => 'New Password field is required',
            'renewpass.required' => 'Confirm Password field is required',
        ]);

        $user = Auth::user();
        $gs = Generalsetting::first();

        if ($request->cpass) {
            if (Hash::check($request->cpass, $user->password)) {
                if ($request->newpass == $request->renewpass) {
                    $input['password'] = Hash::make($request->newpass);
                } else {
                    return redirect()->back()->with('unsuccess', 'Confirm password does not match.');
                }
            } else {
                return redirect()->back()->with('unsuccess', 'Current password Does not match.');
            }
            $user->update($input);
        }

        if ($gs->is_smtp == 1) {
            $data = [
                'to' => $user->email,
                'type' => "password changed",
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
            $subject = "Your password has been changed";
            $msg = "Hello " . $user->name . "!\nYour password has been changed successfully.\nThank you.";
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }

        return back()->with('success', 'Password Successfully Changed.');
    }

    

    public function analytics()
    {
        $range = request('range', 'all');
        $range = in_array($range, ['all', 'daily', 'weekly', 'monthly']) ? $range : 'all';
        $startDate = [
            'daily' => now()->startOfDay(),
            'weekly' => now()->subDays(6)->startOfDay(),
            'monthly' => now()->subDays(29)->startOfDay(),
        ][$range] ?? null; // all = no start date, full history

        $listings = Listing::whereUserId(auth()->id())->orderBy('id', 'desc')->get();
        $listingIds = $listings->pluck('id');

        $events = ListingAnalytics::whereIn('listing_id', $listingIds)
            ->when($startDate, function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->get();

        $messages = ListingEnquiry::whereIn('listing_id', $listingIds)
            ->when($startDate, function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->get();

        $totals = [
            'impressions' => $events->where('event_type', 'impression')->count(),
            'views' => $events->where('event_type', 'view')->count(),
            'unique_visitors' => $events->where('event_type', 'view')->pluck('visitor_hash')->filter()->unique()->count(),
            'phone' => $events->where('event_type', 'phone')->count(),
            'whatsapp' => $events->where('event_type', 'whatsapp')->count(),
            'website' => $events->where('event_type', 'website')->count(),
            'messages' => $messages->count(),
            'email' => $events->where('event_type', 'email')->count(),
        ];

        $rows = $listings->map(function ($listing) use ($events, $messages) {
            $listingEvents = $events->where('listing_id', $listing->id);
            return [
                'listing' => $listing,
                'impressions' => $listingEvents->where('event_type', 'impression')->count(),
                'views' => $listingEvents->where('event_type', 'view')->count(),
                'unique_visitors' => $listingEvents->where('event_type', 'view')->pluck('visitor_hash')->filter()->unique()->count(),
                'phone' => $listingEvents->where('event_type', 'phone')->count(),
                'whatsapp' => $listingEvents->where('event_type', 'whatsapp')->count(),
                'website' => $listingEvents->where('event_type', 'website')->count(),
                'messages' => $messages->where('listing_id', $listing->id)->count(),
                'email' => $listingEvents->where('event_type', 'email')->count(),
            ];
        });
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $rows = new LengthAwarePaginator(
            $rows->forPage($currentPage, $perPage)->values(),
            $rows->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        if ($range === 'all') {
            // All Time: aggregate into monthly bars, from first recorded data (cap 24 months)
            $firstDate = collect([$events->min('event_date'), $messages->min('created_at')])->filter()->min();
            $monthsBack = $firstDate
                ? min(24, \Carbon\Carbon::parse($firstDate)->diffInMonths(now()))
                : 0;

            $chart = collect(range($monthsBack, 0))->map(function ($offset) use ($events, $messages) {
                $monthStart = now()->subMonths($offset)->startOfMonth();
                $monthKey   = $monthStart->format('Y-m');
                return [
                    'label' => $monthStart->format('M y'),
                    'impressions' => $events->where('event_type', 'impression')
                        ->filter(function ($e) use ($monthKey) {
                            return str_starts_with((string) $e->event_date, $monthKey);
                        })->count(),
                    'views' => $events->where('event_type', 'view')
                        ->filter(function ($e) use ($monthKey) {
                            return str_starts_with((string) $e->event_date, $monthKey);
                        })->count(),
                    'clicks' => $events->whereIn('event_type', ['phone', 'whatsapp', 'website', 'email'])
                        ->filter(function ($e) use ($monthKey) {
                            return str_starts_with((string) $e->event_date, $monthKey);
                        })->count(),
                    'messages' => $messages->filter(function ($message) use ($monthKey) {
                        return optional($message->created_at)->format('Y-m') === $monthKey;
                    })->count(),
                ];
            });
        } else {
            $days = $range === 'monthly' ? 30 : ($range === 'weekly' ? 7 : 1);
            $chart = collect(range($days - 1, 0))->map(function ($offset) use ($events, $messages) {
                $date = now()->subDays($offset)->toDateString();
                return [
                    'label' => now()->subDays($offset)->format('M d'),
                    'impressions' => $events->where('event_type', 'impression')->where('event_date', $date)->count(),
                    'views' => $events->where('event_type', 'view')->where('event_date', $date)->count(),
                    'clicks' => $events->whereIn('event_type', ['phone', 'whatsapp', 'website', 'email'])->where('event_date', $date)->count(),
                    'messages' => $messages->filter(function ($message) use ($date) {
                        return optional($message->created_at)->toDateString() === $date;
                    })->count(),
                ];
            });
        }

        return view('user.analytics.index', compact('rows', 'totals', 'chart', 'range'));
    }

    public function analyticsDetails($id)
    {
        $listings = RecentViewsListing::whereListingId($id)->paginate(20);

        return view('user.analytics.details', compact('listings'));
    }

    public function showTwoFactorForm()
    {
        $gnl = Generalsetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->name . '@' . $gnl->title, $secret);
        $prevcode = $user->tsc;
        $prevqr = $ga->getQRCodeGoogleUrl($user->name . '@' . $gnl->title, $prevcode);

        return view('user.twofactor.index', compact('secret', 'qrCodeUrl', 'prevcode', 'prevqr'));
    }

    public function createTwoFactor(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);

        $ga = new GoogleAuthenticator();
        $secret = $request->key;
        $oneCode = $ga->getCode($secret);

        if ($oneCode == $request->code) {
            $user->go = $request->key;
            $user->twofa = 1;
            $user->save();

            return redirect()->back()->with('success', 'Two factor authentication activated');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function disableTwoFactor(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $ga = new GoogleAuthenticator();

        $secret = $user->go;
        $oneCode = $ga->getCode($secret);
        $userCode = $request->code;

        if ($oneCode == $userCode) {
            $user->go = null;
            $user->twofa = 0;
            $user->save();
            return redirect()->back()->with('success', 'Two factor authentication disabled');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function username($email)
    {
        if ($data = User::where('email', $email)->first()) {
            return $data->name;
        } else {
            return false;
        }
    }

    public function affilate_code()
    {
        $user = Auth::guard('web')->user();
        return view('user.affilate_code', compact('user'));
    }
}
