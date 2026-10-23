<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\User;
use App\Models\Admin;
use App\Models\Follow;
use App\Models\Listing;
use App\Models\Withdraw;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Services\CronLogService;
use App\Services\UserLogService;
use App\Models\UserLog;
use App\Models\EmailLog;
use App\Models\Plan;
use App\Models\ListingAnalytics;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except([
            'sendBusinessAddMarketingCron',
        ]);
    }

    public function datatables(Request $request)
    {
        $datas = User::withCount('listings')->orderBy('id', 'desc');

        if ($request->listing_filter === 'has') {
            $datas->has('listings');
        } elseif ($request->listing_filter === 'none') {
            $datas->doesntHave('listings');
        }

        return Datatables::of($datas)
            ->addColumn('created', function (User $data) {
                return $data->created_at->format('d M Y, h:i A');
            })
            ->addColumn('business_count', function (User $data) {
                return $data->listings_count;
            })
            ->addColumn('whatsapp_consent', function (User $data) {
                $badge = $data->whatsapp_opt_in ? 'success' : 'danger';
                $text = $data->whatsapp_opt_in ? __('Yes') : __('No');

                return '<span class="badge badge-' . $badge . '">' . $text . '</span>';
            })
            ->addColumn('action', function (User $data) {
                return '<div class="btn-group mb-1">
                                    <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ' . 'Actions' . '
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-start">
                                    <a href="' . route('admin.user.login.as.user', $data->id) . '" class="dropdown-item" onclick="return confirm(\'Login as this user?\')">' . __("Login as User") . '</a>
                                    <a href="' . route('admin.user.logs', $data->id) . '" class="dropdown-item">' . __("User Logs") . '</a>
                                    <a href="' . route('admin.user.email.logs', $data->id) . '" class="dropdown-item">' . __("Email Logs") . '</a>
                                    <a href="' . route('admin-user-show', $data->id) . '"  class="dropdown-item">' . __("Details") . '</a>
                                    <a href="' . route('admin-user-edit', $data->id) . '" class="dropdown-item" >' . __("Edit") . '</a>
                                    <a href="javascript:;" class="dropdown-item send" data-email="' . $data->email . '" data-toggle="modal" data-target="#vendorform">' . __("Send") . '</a>
                                    <a href="' . route('admin-user-send-business-add-mail', $data->id) . '" onclick="return confirm(\'Send business add marketing mail?\')" class="dropdown-item">' . __("Send Business Add Mail") . '</a>
                                    <a href="' . route('admin-user-verify', $data->id) . '" onclick="return confirm(\'Are you sure to Verify Email?\')" class="dropdown-item">' . __("Verify Email") . '</a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="' .  route('admin-user-delete', $data->id) . '">' . __("Delete") . '</a>
                                    </div>
                                </div>';
            })

            ->addColumn('status', function (User $data) {
                $status      = $data->is_banned == 1 ? __('Block') : __('Unblock');
                $status_sign = $data->is_banned == 1 ? 'danger'   : 'success';

                return '<div class="btn-group mb-1">
                                    <button type="button" class="btn btn-' . $status_sign . ' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        ' . $status . '
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-start">
                                        <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="' . route('admin-user-ban', ['id1' => $data->id, 'id2' => 0]) . '">' . __("Unblock") . '</a>
                                        <a href="javascript:;" data-toggle="modal" data-target="#statusModal" class="dropdown-item" data-href="' . route('admin-user-ban', ['id1' => $data->id, 'id2' => 1]) . '">' . __("Block") . '</a>
                                    </div>
                                    </div>';
            })
            ->rawColumns(['action', 'status', 'whatsapp_consent'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.user.index');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = User::where('email', $request->to)->first();
        $this->sendAndLogEmail(
            $user,
            $request->to,
            $request->subject,
            $request->message,
            'admin_manual'
        );

        return response()->json('Email Sent Successfully.');
    }

    public function sendBusinessAddMarketingMail($id)
    {
        $user = User::findOrFail($id);

        if (!$user->email) {
            Toastr::error('Customer email not found.');
            return redirect()->back();
        }

        $this->sendBusinessAddMarketingToUser($user, 'business_add_marketing_manual');

        Toastr::success('Business add marketing mail sent successfully.');
        return redirect()->back();
    }

    public function sendMonthlyAnalyticsReportCron(Request $request, CronLogService $cronLog)
    {
        // Monthly analytics report email to owners of paid listings
        // (lifetime + premium/basic monthly packages). Reports cover the
        // previous calendar month. Safe to re-run: already-emailed owners
        // are skipped, and it only runs during the first 5 days of a month.
        //
        // Test mode: /cron/monthly-analytics-report?test_email=xxx@yyy.com
        // sends to that one owner immediately (bypasses the day window and
        // the monthly dedupe, logged with mailer
        // monthly_analytics_report_test so the real run is not affected).
        $startedAt = microtime(true);
        $now = now('Asia/Dhaka');
        $isTest = $request->filled('test_email') && filter_var($request->input('test_email'), FILTER_VALIDATE_EMAIL);
        $job = $isTest ? 'monthly-analytics-report-test' : 'monthly-analytics-report';

        if (!$isTest && $now->day > 5) {
            $cronLog->record(
                $job,
                'success',
                'Skipped: cron is allowed only within the first 5 days of a month.',
                0,
                microtime(true) - $startedAt,
                ['time' => $now->format('d M Y h:i A')]
            );

            return response()->json([
                'status' => true,
                'message' => 'Outside the send window (day 1-5 of a month). Nothing sent.',
                'time' => $now->format('d M Y h:i A'),
            ]);
        }

        $periodStart = $now->copy()->startOfMonth()->subMonth();
        $periodEnd   = $periodStart->copy()->endOfMonth();

        $monthStart = $now->copy()->startOfMonth()->timezone(config('app.timezone'));
        $monthEnd   = $now->copy()->endOfMonth()->timezone(config('app.timezone'));

        $paidPlanIds = Plan::whereIn('plan_type', ['lifetime', 'monthly'])->pluck('id');

        if ($isTest) {
            // Single-owner test send: paid listings if any, otherwise all
            // active listings of the owner so the test email has content.
            $user = User::where('email', $request->input('test_email'))->first();

            if (!$user) {
                $cronLog->record($job, 'failed', 'Test send failed: no user found with email ' . $request->input('test_email'), 0, microtime(true) - $startedAt);
                return response()->json(['status' => false, 'message' => 'No user found with that email.']);
            }

            $listings = $user->listings()->where('status', 1)->whereIn('plan_id', $paidPlanIds)->get();
            if ($listings->isEmpty()) {
                $listings = $user->listings()->where('status', 1)->get();
            }

            try {
                $this->sendMonthlyAnalyticsReportToUser($user, $listings, $periodStart, $periodEnd, 'monthly_analytics_report_test');
                $sent = 1;
                $failed = 0;
            } catch (\Throwable $e) {
                $sent = 0;
                $failed = 1;
            }

            $cronLog->record(
                $job,
                $failed > 0 ? 'failed' : 'success',
                "Monthly analytics report test send. Sent: {$sent}, Failed: {$failed}.",
                $sent,
                microtime(true) - $startedAt,
                ['email' => $user->email, 'listings' => $listings->count(), 'period' => $periodStart->format('M Y')]
            );

            return response()->json([
                'status' => $failed === 0,
                'message' => 'Monthly analytics report test send completed.',
                'email' => $user->email,
                'report_period' => $periodStart->format('F Y'),
                'listings' => $listings->count(),
                'sent' => $sent,
                'failed' => $failed,
            ]);
        }

        $users = User::where('is_banned', 0)
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->whereHas('listings', function ($query) use ($paidPlanIds) {
                $query->where('status', 1)->whereIn('plan_id', $paidPlanIds);
            })
            ->whereDoesntHave('emailLogs', function ($query) use ($monthStart, $monthEnd) {
                $query->where('mailer', 'monthly_analytics_report')
                    ->whereBetween('created_at', [$monthStart, $monthEnd]);
            })
            ->with(['listings' => function ($query) use ($paidPlanIds) {
                $query->where('status', 1)->whereIn('plan_id', $paidPlanIds);
            }])
            ->orderBy('id')
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                $this->sendMonthlyAnalyticsReportToUser($user, $user->listings, $periodStart, $periodEnd);
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
            }
        }

        $status = $failed > 0 ? 'failed' : 'success';
        $cronLog->record(
            $job,
            $status,
            "Monthly analytics report cron completed. Sent: {$sent}, Failed: {$failed}.",
            $sent,
            microtime(true) - $startedAt,
            [
                'period' => $periodStart->format('M Y'),
                'matched' => $users->count(),
                'sent' => $sent,
                'failed' => $failed,
            ]
        );

        return response()->json([
            'status' => $failed === 0,
            'message' => 'Monthly analytics report cron completed.',
            'report_period' => $periodStart->format('F Y'),
            'matched' => $users->count(),
            'sent' => $sent,
            'failed' => $failed,
        ]);
    }

    private function sendMonthlyAnalyticsReportToUser(User $user, $listings, $periodStart, $periodEnd, string $mailer = 'monthly_analytics_report'): void
    {
        $listingIds = $listings->pluck('id');

        $events = ListingAnalytics::whereIn('listing_id', $listingIds)
            ->where('event_date', '>=', $periodStart->format('Y-m-d'))
            ->where('event_date', '<=', $periodEnd->format('Y-m-d'))
            ->get()
            ->groupBy('listing_id');

        $mail = $this->monthlyAnalyticsReportMail($user, $listings, $events, $periodStart, $periodEnd);

        $this->sendAndLogEmail(
            $user,
            $user->email,
            $mail['subject'],
            $mail['body'],
            $mailer
        );
    }

    private function monthlyAnalyticsReportMail(User $user, $listings, $eventsByListing, $periodStart, $periodEnd): array
    {
        $name = e($user->name ?: 'Business Owner');
        $monthLabel = $periodStart->format('F Y');
        $dateLabel = $periodStart->format('d M Y') . ' - ' . $periodEnd->format('d M Y');

        $sections = '';
        foreach ($listings as $listing) {
            $events = $eventsByListing->get($listing->id, collect());

            $metrics = [
                'Impressions' => $events->where('event_type', 'impression')->count(),
                'Profile Views' => $events->where('event_type', 'view')->count(),
                'Phone Taps' => $events->where('event_type', 'phone')->count(),
                'WhatsApp Clicks' => $events->where('event_type', 'whatsapp')->count(),
                'Website Visits' => $events->where('event_type', 'website')->count(),
                'Email Clicks' => $events->where('event_type', 'email')->count(),
            ];

            $rows = '';
            foreach ($metrics as $label => $value) {
                $rows .= '<tr>'
                    . '<td style="padding:6px 12px;border-bottom:1px solid #eee;">' . $label . '</td>'
                    . '<td style="padding:6px 12px;border-bottom:1px solid #eee;text-align:right;font-weight:600;">' . number_format($value) . '</td>'
                    . '</tr>';
            }

            $sections .= '<p style="margin:18px 0 6px;font-size:16px;"><strong>' . e($listing->name) . '</strong></p>'
                . '<table style="border-collapse:collapse;font-size:14px;color:#333;min-width:280px;" cellpadding="0" cellspacing="0">'
                . $rows
                . '</table>';
        }

        $subject = 'Your BDTalika Business Report - ' . $monthLabel;

        $body = <<<HTML
<strong>Dear {$name}</strong>, <br><br>
Here is your business performance report for <strong>{$monthLabel}</strong> ({$dateLabel}) from <strong>bdtalika.com</strong>.<br><br>

{$sections}<br>

You can see full analytics (daily, weekly, monthly and all-time charts) any time from your dashboard:<br>
<a href="https://bdtalika.com/user/listing/analytics">BDTalika Business Analytics</a><br><br>

Thank you for being a paid member of BDTalika!<br>
BDTalika Team<br>
https://bdtalika.com
HTML;

        return compact('subject', 'body');
    }

    public function sendBusinessAddMarketingCron(CronLogService $cronLog)
    {
        $startedAt = microtime(true);
        $job = 'customer-business-add-marketing-mails';
        $now = now('Asia/Dhaka');

        if ($now->hour < 11 || $now->hour > 14) {
            $cronLog->record(
                $job,
                'success',
                'Skipped: cron is allowed only within the 11 AM - 2 PM Bangladesh time send window.',
                0,
                microtime(true) - $startedAt,
                ['time' => $now->format('d M Y h:i A'), 'hour' => $now->hour]
            );

            return response()->json([
                'status' => true,
                'message' => 'Outside send window (11 AM - 2 PM Bangladesh time). Nothing sent.',
                'time' => $now->format('d M Y h:i A'),
            ]);
        }

        $startOfDay = $now->copy()->startOfDay()->timezone(config('app.timezone'));
        $endOfDay = $now->copy()->endOfDay()->timezone(config('app.timezone'));

        $users = User::where('is_banned', 0)
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->doesntHave('listings')
            ->whereDoesntHave('emailLogs', function ($query) use ($startOfDay, $endOfDay) {
                $query->where('mailer', 'business_add_marketing')
                    ->whereBetween('created_at', [$startOfDay, $endOfDay]);
            })
            ->orderBy('id')
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($users as $user) {
            try {
                $this->sendBusinessAddMarketingToUser($user, 'business_add_marketing');
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
            }
        }

        $status = $failed > 0 ? 'failed' : 'success';
        $cronLog->record(
            $job,
            $status,
            "Business add marketing cron completed. Sent: {$sent}, Failed: {$failed}.",
            $sent,
            microtime(true) - $startedAt,
            ['matched' => $users->count(), 'sent' => $sent, 'failed' => $failed]
        );

        return response()->json([
            'status' => $failed === 0,
            'message' => 'Business add marketing cron completed.',
            'matched' => $users->count(),
            'sent' => $sent,
            'failed' => $failed,
        ]);
    }

    public function logs($id)
    {
        $user = User::findOrFail($id);
        $logs = UserLog::with(['user', 'admin'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(25);

        return view('admin.user.logs', compact('user', 'logs'));
    }

    public function emailLogs($id)
    {
        $user = User::findOrFail($id);
        $logs = EmailLog::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->latest()
            ->paginate(25);

        return view('admin.user.email_logs', compact('user', 'logs'));
    }

    public function loginAsUser($id)
    {
        $user = User::findOrFail($id);
        $adminId = Auth::guard('admin')->id();

        app(UserLogService::class)->log(
            'admin_login_as_user',
            'Admin logged in as user.',
            ['user_id' => $user->id, 'user_email' => $user->email],
            $user->id,
            $adminId
        );

        session([
            'admin_return_url' => url()->previous(),
            'admin_impersonating_user' => $user->id,
            'admin_impersonating_admin' => $adminId,
        ]);

        Auth::guard('web')->login($user);

        return redirect()->route('user.dashboard');
    }

    public function backToAdminPanel()
    {
        $userId = session('admin_impersonating_user');
        $adminId = session('admin_impersonating_admin');

        if ($userId || $adminId) {
            app(UserLogService::class)->log(
                'exit_user_mode',
                'Admin returned from user impersonation.',
                ['user_id' => $userId, 'admin_id' => $adminId],
                $userId ? (int) $userId : null,
                $adminId ? (int) $adminId : null
            );
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $returnUrl = session('admin_return_url', route('admin.dashboard'));

        Session::forget('admin_return_url');
        Session::forget('admin_impersonating_user');
        Session::forget('admin_impersonating_admin');

        return redirect($returnUrl);
    }

    public function image()
    {
        return view('admin.generalsetting.user_image');
    }

    public function show($id)
    {
        $data = User::findOrFail($id);
        $data['listings'] = Listing::whereUserId($id)->orderBy('id', 'desc')->limit(5)->get();
        $data['data'] = $data;
        return view('admin.user.show', $data);
    }

    public function ban($id1, $id2)
    {
        $user = User::findOrFail($id1);
        $user->is_banned = $id2;
        $user->update();

        app(UserLogService::class)->log(
            $id2 == 1 ? 'user_blocked' : 'user_unblocked',
            $id2 == 1 ? 'Admin blocked the user.' : 'Admin unblocked the user.',
            ['status' => $id2],
            $user->id,
            Auth::guard('admin')->id()
        );

        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);
        return view('admin.user.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {

        $rules = [
            'photo' => 'mimes:jpeg,jpg,png,svg',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $user = User::findOrFail($id);
        $data = $request->all();
        if ($file = $request->file('photo')) {
            $name = Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
            $file->move('assets/images', $name);
            if ($user->photo != null) {
                if (file_exists(public_path() . '/assets/images/' . $user->photo)) {
                    unlink(public_path() . '/assets/images/' . $user->photo);
                }
            }
            $data['photo'] = $name;
        }
        $user->update($data);

        app(UserLogService::class)->log(
            'admin_user_update',
            'Admin updated user profile information.',
            ['updated_fields' => array_keys($data)],
            $user->id,
            Auth::guard('admin')->id(),
            $request
        );

        $msg = 'Customer Information Updated Successfully.';
        return response()->json($msg);
    }



    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->listings->count() > 0) {
            $msg = 'Unable to delete. The user has a directory';
            return response()->json($msg);
        } else {

            $logData = [
                'transactions' => $user->transactions->count(),
                'bookings' => $user->booking->count(),
                'enquiries' => $user->enquries->count(),
                'messages' => $user->messages->count(),
                'followers' => $user->followers->count(),
            ];

            app(UserLogService::class)->log(
                'user_deleted',
                'Admin deleted a user account.',
                $logData,
                $user->id,
                Auth::guard('admin')->id()
            );

            if ($user->transactions->count() > 0) {
                foreach ($user->transactions as $transaction) {
                    $transaction->delete();
                }
            }

            foreach ($user->listings as $listing) {

                if ($listing->galleries != NULL) {
                    foreach ($listing->galleries as $gallery) {
                        @unlink('assets/images/' . $gallery->photo);
                        $gallery->delete();
                    }
                }

                if ($listing->menus != NULL) {
                    foreach ($listing->menus as $menu) {
                        @unlink('assets/images/' . $menu->menu_photo);
                        $menu->delete();
                    }
                }

                if ($listing->services != NULL) {
                    foreach ($listing->services as $service) {
                        @unlink('assets/images/' . $service->service_photo);
                        $service->delete();
                    }
                }

                if ($listing->rooms != NULL) {
                    foreach ($listing->rooms as $room) {
                        @unlink('assets/images/' . $room->room_photo);
                        $room->delete();
                    }
                }

                if ($listing->faqs != NULL) {
                    foreach ($listing->faqs as $faq) {
                        $faq->delete();
                    }
                }

                if ($listing->wishlists != NULL) {
                    foreach ($listing->wishlists as $wishlist) {
                        $wishlist->delete();
                    }
                }

                if ($listing->recentviews != NULL) {
                    foreach ($listing->recentviews as $recentview) {
                        $recentview->delete();
                    }
                }

                if ($listing->bookings != NULL) {
                    foreach ($listing->bookings as $booking) {
                        $booking->delete();
                    }
                }

                if ($listing->enquiries != NULL) {
                    foreach ($listing->enquiries as $enquiry) {
                        $enquiry->delete();
                    }
                }

                if ($listing->reviews != NULL) {
                    foreach ($listing->reviews as $review) {
                        $review->delete();
                    }
                }

                @unlink('assets/images/' . $listing->photo);
                $listing->delete();
            }

            if ($user->booking->count() > 0) {
                foreach ($user->booking as $booking) {
                    $booking->delete();
                }
            }

            if ($user->enquries->count() > 0) {
                foreach ($user->enquries as $enqury) {
                    $enqury->delete();
                }
            }

            if ($user->messages->count() > 0) {
                foreach ($user->messages as $message) {
                    $message->delete();
                }
            }

            if ($user->followers->count() > 0) {
                foreach ($user->followers as $follower) {
                    $follower->delete();
                }
            }

            @unlink('assets/images/' . $listing->photo);

            $user->delete();

            $msg = 'Data Deleted Successfully.';
            return response()->json($msg);
        }
    }


    public function verifyUser($id){
        $user = User::findOrFail($id); 
        if($user){
            $user->email_verified = 'Yes';
            $user->update(); 

            app(UserLogService::class)->log(
                'email_verified',
                'Admin verified the user email.',
                ['email' => $user->email],
                $user->id,
                Auth::guard('admin')->id()
            );

            // send mail 
            $data = [
                'title'   => "Account Verification",
                'to'      => $user->email,
                'subject' => "Email Verification",
                'body'    => "Hi, Your account has been verified. Please login to enlist your business.",
            ];
            $mailer = new GeniusMailer();
            $mailer->sendListingMail($data);
            Toastr::success('Customer email verify Successfully.');
            return redirect()->back();
        }
    }

    private function sendBusinessAddMarketingToUser(User $user, string $mailer): void
    {
        $mail = $this->businessAddMarketingMail($user);

        $this->sendAndLogEmail(
            $user,
            $user->email,
            $mail['subject'],
            $mail['body'],
            $mailer
        );
    }

    private function businessAddMarketingMail(User $user): array
    {
        $name = e($user->name ?: 'Business Owner');
        $subject = 'Add Your Business on BDTalika and Get More Customers';
        $body = <<<HTML
<strong>Dear {$name}</strong>, <br><br>
You have registered on <strong>bdtalika.com</strong>, but you have not added your business yet.<br><br>

Add your business today so customers can find your phone number, address, Google Map location, services, and business details on BDTalika.<br><br>

Add business: https://bdtalika.com/user/listing<br>
WhatsApp help: https://wa.me/8801738411242<br><br>

Thank you,<br>
BDTalika Team<br>
https://bdtalika.com
HTML;

        return compact('subject', 'body');
    }

    private function sendAndLogEmail(?User $user, string $to, string $subject, string $body, string $mailer): void
    {
        $config = Generalsetting::findOrFail(1);

        if ($config->is_smtp == 1) {
            (new GeniusMailer())->sendListingMail([
                'title' => 'BDTalika',
                'to' => $to,
                'subject' => $subject,
                'body' => $body,
            ]);
        } else {
            $headers = "From: " . $config->from_name . " <" . $config->from_email . ">";
            mail($to, $subject, strip_tags(str_replace(['<br>', '<br><br>', '</p>'], "\n", $body)), $headers);
        }

        EmailLog::create([
            'user_id' => optional($user)->id,
            'email' => $to,
            'subject' => $subject,
            'body' => $body,
            'mailer' => $mailer,
        ]);
    }



}

