<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use DOMDocument;
use App\Models\Area;
use App\Models\User;
use App\Models\Amenity;
use App\Models\Country;
use App\Models\Gallery;
use App\Models\Listing;
use App\Models\Category;
use App\Models\WhatsAppReminderLog;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\Location;
use App\Models\Attribute;
use App\Models\ListingFaq;
use App\Models\ListingMenu;
use App\Models\ListingRoom;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\ListingBeauty;
use App\Models\ListingAnalytics;
use App\Models\ListingSeoOfferSetting;
use App\Models\EmailLog;
use App\Models\UserLog;
use App\Models\ListingEnquiry;
use App\Models\ListingReview;
use App\Models\Generalsetting;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ListingRequest;
use App\Services\AIDescriptionService;
use App\Services\BangladeshPhoneService;
use App\Services\CronLogService;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ListingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except([
            'sendPackageReminderToDraftListings',
            'sendMonthlyAnalyticsNotificationsToPaidListings',
            'updateMissingWhatsappListingsWithAi',
        ]);
    }

    // public function datatables(Request $request)
    // {

    //     $user = Auth::user();
    //     $query = Listing::with('plan');
    //     if ($request->filled('status') && $request->status != 'all') { 
    //         $query = $query->whereStatus($request->status);
    //     }

    //     if ($request->filled('plan')) {
    //         $query = $query->whereHas('plan', function ($q) use ($request) {
    //             $q->where('plan_type', $request->plan);
    //         });
    //     }

    //     if ($request->status == 'all') {
    //         if ($user->id != 1) {
    //             $query = $query->where('user_id', $user->id)->orderBy('id', 'desc');
    //         } else {
    //             $query = $query->orderBy('id', 'desc');
    //         }
    //     } else {
    //         if ($user->id != 1) {
    //             $query = $query->where('user_id', $user->id)->whereStatus($request->status)->orderBy('id', 'desc');
    //         } else {
    //             $query = $query->whereStatus($request->status)->orderBy('id', 'desc');
    //         }
    //     }

    //     // if ($request->status == 'all') {
    //     //     $query = Listing::where('user_id', $user->id)->orderBy('id', 'desc');
    //     // } else {
    //     //     $query = Listing::where('user_id', $user->id)->whereStatus($request->status)->orderBy('id', 'desc');
    //     // } 
    //     return Datatables::of($query)
    //         ->editColumn('photo', function (Listing $data) {
    //             $photo = $data->photo ? url('assets/images/' . $data->photo) : url('assets/images/default.png');
    //             return '<img src="' . $photo . '" alt="Image">';
    //         })
    //         ->editColumn('user_id', function (Listing $data) {
    //             if ($data->user != NULL) {
    //                 $url = route('admin-user-show', $data->user->id);
    //                 return '<a href="' . $url . '">' . $data->user->name . '</a>';
    //             } else {
    //                 return 'Bdtalika';
    //             }
    //         })

    //         ->editColumn('category_id', function (Listing $data) {
    //             return $data->category != NULL ? $data->category->title : 'Category Deleted';
    //         })
    //         ->editColumn('status', function (Listing $data) {
    //             $status = $data->status == 1 ? __('Approved') : 
    //             ($data->status == 2 ? __('Reject') : 
    //             ($data->status == 3 ? __('Requires Review') : __('Draft')));
    //             $status_sign = $data->status == 1 ? 'success' : 
    //            ($data->status == 2 ? 'danger' : 
    //            ($data->status == 3 ? 'info' : 'warning'));
    //             $approvedLink = '';
    //             $pendingLink = '';
    //             $rejectLink = '';
    //             if ($data->status != 1) {
    //                 $approvedLink = '<a class="dropdown-item" onclick="return confirm(\'Are you sure you want to Approve?\')" href="' . route('admin.listing.status', ['id1' => $data->id, 'id2' => 1]) . '">' . __("Approved") . '</a>';
    //             }
    //             if ($data->status != 0) {
    //                 $pendingLink = '<a class="dropdown-item" onclick="return confirm(\'Are you sure you want to Draft?\')" href="' . route('admin.listing.status', ['id1' => $data->id, 'id2' => 0]) . '">' . __("Draft") . '</a>';
    //             }
    //             if ($data->status != 2) {
    //                 $rejectLink = '<a href="' . route('admin.listing.reject', $data->id) . '" data-toggle="modal" data-target="#rejectStatusModal"
    //                 class="rejectListing dropdown-item">Reject</a>';
    //             }
    //             return '<div class="btn-group mb-1">
    //                 <button type="button" class="btn btn-' . $status_sign . ' btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    //                 ' . $status . '
    //                 </button>
    //                 <div class="dropdown-menu" x-placement="bottom-start">
    //                     ' . $approvedLink . '
    //                     ' . $pendingLink . '
    //                     ' . $rejectLink . '
    //                 </div>
    //             </div>';
    //         })

    //         ->editColumn('reject_reason', function (Listing $data) {
    //             if ($data->reject_reason) {
    //                 return '<a href="javascript:;" class="btn btn-sm btn-primary py-2" title="Reject Reason" data-toggle="modal" data-target="#rejectReasonModal" data-reason="' . $data->reject_reason . '">
    //                             <i class="fas fa-eye"></i>
    //                         </a>';
    //             } else {
    //                 return '-';
    //             }
    //         })

    //         ->addColumn('action', function (Listing $data) {
    //             return '<div class="btn-group mb-1">
    //                                     <button type="button" class="btn btn-primary btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    //                                     ' . 'Actions' . '
    //                                     </button>
    //                                     <div class="dropdown-menu" x-placement="bottom-start">
    //                                     <a href="' . route('admin.listing.view', [$data->id]) . '"  class="dropdown-item">' . __("View") . '</a>
    //                                         <a href="' . route('admin.listing.edit.type', [$data->id]) . '"  class="dropdown-item">' . __("Edit") . '</a>
    //                                         <a href="' . route('admin.listing.review', [$data->id]) . '"  class="dropdown-item">' . __("Get Reviews") . '</a>
    //                                         <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="dropdown-item" data-href="' .  route('admin.listing.delete', $data->id) . '">' . __("Delete") . '</a>
    //                                     </div>
    //                                 </div>';
    //         })

    //         ->rawColumns(['photo', 'status', 'user_id', 'action', 'reject_reason'])
    //         ->toJson();
    // }

    // public function mail(){
    //           $data = [
    //                 'to'        => 'bdtalika@gmail.com',
    //                 'type'      => 'test',
    //                 'cname'     => 'rony',
    //                 'oamount'   => '2',
    //                 'aname'     => 'bdtalika',
    //                 'aemail'    => 'info@bdtalika.com',
    //             ];
    //             $mailer         = new GeniusMailer();
    //             $res = $mailer->sendAutoMail($data);
    //             dd($res);
    // }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Listing::with('plan', 'user', 'category');

        // Filter by status
        if ($request->filled('status') && $request->status != 'all') {
            $query = $query->whereStatus($request->status);
        }

        // Filter by exact package so private/custom plans are available to admin.
        if ($request->filled('plan')) {
            $query = $query->where('plan_id', $request->plan);
        }

        // Order and filter based on the user
        if ($user->id != 1) {
            $query = $query->where('user_id', $user->id)->orderBy('id', 'desc');
        } else {
            $query = $query->orderBy('id', 'desc');
        }
        $listings = $query->get();
        $plans = Plan::orderBy('sorting', 'desc')->orderBy('id', 'desc')->get();
        return view('admin.listing.index', compact('listings', 'plans'));
    }

    public function paid(Request $request)
    {
        $user = Auth::user();
        $query = Listing::with([
            'plan',
            'user',
            'category',
            'latestCompletedBkashPayment',
            'latestTransaction',
        ])
            ->whereNotNull('plan_id')
            ->whereHas('plan', function ($query) {
                $query->where('plan_type', '!=', 'free');
            });

        if ($request->filled('plan')) {
            $query = $query->where('plan_id', $request->plan);
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query = $query->whereStatus($request->status);
        }

        if ($user->id != 1) {
            $query = $query->where('user_id', $user->id);
        }

        $listings = $query
            ->orderByRaw(
                "COALESCE(
                    (SELECT MAX(bp.paid_at) FROM bkash_payments bp WHERE bp.listing_id = listings.id AND bp.payment_status = ?),
                    (SELECT MAX(t.created_at) FROM transactions t WHERE t.ad_id = listings.id AND t.status = ?),
                    listings.plan_start_date
                ) DESC",
                ['completed', 1]
            )
            ->orderBy('id', 'desc')
            ->get();

        $plans = Plan::where('plan_type', '!=', 'free')
            ->orderBy('sorting', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.listing.paid', compact('listings', 'plans'));
    }

    public function upcomingRenew(Request $request)
    {
        $user = Auth::user();
        $window = (int) $request->input('days', 30);
        $showExpired = $request->input('range', 'upcoming') === 'expired';

        $query = Listing::with([
            'plan',
            'user',
            'latestCompletedBkashPayment',
            'latestTransaction',
        ])
            ->whereNotNull('plan_end_date')
            ->whereHas('plan', function ($q) {
                $q->whereNotIn('plan_type', ['free', 'lifetime']);
            });

        if ($showExpired) {
            $query->where('plan_end_date', '<', now()->startOfDay());
        } else {
            $query->whereBetween('plan_end_date', [now()->startOfDay(), now()->addDays($window)->endOfDay()]);
        }

        if ($user->id != 1) {
            $query = $query->where('user_id', $user->id);
        }

        $listings = $query->orderBy('plan_end_date', 'asc')->get();

        return view('admin.listing.upcoming_renew', compact('listings', 'window', 'showExpired'));
    }

    public function sendMonthlyAnalyticsNotificationsToPaidListings()
    {
        $period = now()->subMonthNoOverflow();
        $periodStart = $period->copy()->startOfMonth();
        $periodEnd = $period->copy()->endOfMonth();
        $sent = 0;
        $skipped = 0;
        $failed = 0;

        $listings = Listing::with(['plan', 'user'])
            ->whereStatus(1)
            ->whereNotNull('plan_id')
            ->whereHas('plan', function ($query) {
                $query->where('plan_type', '!=', 'free');
            })
            ->get();

        foreach ($listings as $listing) {
            $alreadySent = DB::table('listing_analytics_notifications')
                ->where('listing_id', $listing->id)
                ->whereDate('period_start', $periodStart->toDateString())
                ->whereDate('period_end', $periodEnd->toDateString())
                ->exists();

            if ($alreadySent) {
                $skipped++;
                continue;
            }

            $to = optional($listing->user)->email ?: $listing->email;
            if (!$to) {
                $skipped++;
                continue;
            }

            $events = ListingAnalytics::where('listing_id', $listing->id)
                ->whereBetween('event_date', [$periodStart->toDateString(), $periodEnd->toDateString()])
                ->get();

            $messageCount = ListingEnquiry::where('listing_id', $listing->id)
                ->whereBetween('created_at', [$periodStart, $periodEnd])
                ->count();

            $body = '<p>Hello ' . e(optional($listing->user)->name ?? 'Business Owner') . ',</p>'
                . '<p>Your monthly analytics report for <strong>' . e($listing->name) . '</strong> is ready.</p>'
                . '<table>'
                . '<tr><th>Metric</th><th>Total</th></tr>'
                . '<tr><td>Total views</td><td>' . $events->where('event_type', 'view')->count() . '</td></tr>'
                . '<tr><td>Unique visitors</td><td>' . $events->where('event_type', 'view')->pluck('visitor_hash')->filter()->unique()->count() . '</td></tr>'
                . '<tr><td>Phone clicks</td><td>' . $events->where('event_type', 'phone')->count() . '</td></tr>'
                . '<tr><td>WhatsApp clicks</td><td>' . $events->where('event_type', 'whatsapp')->count() . '</td></tr>'
                . '<tr><td>Website clicks</td><td>' . $events->where('event_type', 'website')->count() . '</td></tr>'
                . '<tr><td>Email clicks</td><td>' . $events->where('event_type', 'email')->count() . '</td></tr>'
                . '<tr><td>Customer messages</td><td>' . $messageCount . '</td></tr>'
                . '</table>'
                . '<div class="btn-wrap"><a class="anchor-btn" href="' . route('user.listing.analytics', ['range' => 'monthly']) . '">View Analytics Dashboard</a></div>';

            try {
                (new GeniusMailer())->sendCustomMail([
                    'to' => $to,
                    'subject' => 'Your monthly BDTalika analytics report',
                    'body' => $body,
                ]);

                DB::table('listing_analytics_notifications')->insert([
                    'listing_id' => $listing->id,
                    'user_id' => $listing->user_id,
                    'period_start' => $periodStart->toDateString(),
                    'period_end' => $periodEnd->toDateString(),
                    'sent_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $sent++;
            } catch (\Exception $e) {
                \Log::error('Monthly analytics notification failed', [
                    'listing_id' => $listing->id,
                    'error' => $e->getMessage(),
                ]);
                $failed++;
            }
        }

        return response()->json([
            'period' => $periodStart->format('F Y'),
            'paid_listings' => $listings->count(),
            'sent' => $sent,
            'skipped' => $skipped,
            'failed' => $failed,
        ]);
    }

//open ai
    public function generateDescription(Request $request, AIDescriptionService $ai)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:150',
        'description' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors()->first()
        ], 422);
    }

    $data = [
        'type' => $request->type,
        'category' => $request->category,
        'division' => $request->division,
        'city' => $request->city,
        'title' => trim((string) $request->title),
        'description' => trim((string) $request->input('description', '')),
    ];

    try {
        $result = $ai->generate($data);
        return response()->json($result);

    } catch (\Exception $e) {
        \Log::error('AI Generation Failed', [
            'error' => $e->getMessage(),
            'input' => $data
        ]);

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function updateMissingWhatsappListingsWithAi(AIDescriptionService $ai, CronLogService $cronLog)
    {
        @set_time_limit(0);

        $startedAt = microtime(true);
        $job = 'ai-listing-content-update';
        $updated = [];
        $failed = [];
        $batchLimit = 5;
        $privatePlan = Plan::where('status', 1)
            ->where('is_private', 1)
            ->orderBy('sorting', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        if (!$privatePlan) {
            $duration = microtime(true) - $startedAt;
            $message = 'AI listing content cron failed: no active private plan found.';

            $cronLog->record($job, 'failed', $message, 0, $duration, [
                'updated' => [],
                'failed' => [
                    [
                        'error' => 'No active private plan found. Create or activate a private plan first.',
                    ],
                ],
                'batch_limit' => $batchLimit,
                'admin_cron_logs_url' => route('admin.cron.logs', ['job' => $job]),
            ]);

            return response()->json([
                'job' => $job,
                'status' => 'failed',
                'message' => $message,
                'processed' => 0,
                'failed' => 1,
                'batch_limit' => $batchLimit,
                'cron_logs_url' => route('admin.cron.logs', ['job' => $job]),
            ], 500);
        }

        $listings = Listing::with(['listingType', 'category', 'division', 'city', 'area'])
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('whatsapp_number')
                    ->orWhere('whatsapp_number', '');
            })
            ->whereNotNull('phone_number')
            ->where('phone_number', '<>', '')
            ->orderBy('id')
            ->limit($batchLimit)
            ->get();

        foreach ($listings as $listing) {
            try {
                $aiResult = $ai->generate([
                    'type' => optional($listing->listingType)->name,
                    'category' => optional($listing->category)->title,
                    'division' => optional($listing->division)->name,
                    'city' => optional($listing->city)->name,
                    'title' => mb_substr(trim((string) $listing->name), 0, 150),
                    'description' => $listing->description ?? '',
                ]);

                $oldName = $listing->name;
                $oldMetaTitle = $listing->meta_title;
                $oldMetaKeywords = $listing->meta_keywords;
                $oldMetaDescription = $listing->meta_description;
                $oldPlanId = $listing->plan_id;

                $listing->update([
                    'name' => $this->limitPlainText($aiResult['title'] ?: $listing->name, 70),
                    'description' => $aiResult['description'] ?: $listing->description,
                    'meta_title' => $this->limitPlainText($aiResult['meta_title'] ?: $listing->meta_title, 70),
                    'meta_keywords' => $aiResult['meta_keywords'] ?: $listing->meta_keywords,
                    'meta_description' => $this->limitPlainText($aiResult['meta_description'] ?: $listing->meta_description, 160),
                    'whatsapp_number' => $this->formatBangladeshWhatsappNumber($listing->phone_number),
                    'plan_id' => $privatePlan->id,
                ]);

                $freshListing = $listing->fresh();

                $updated[] = [
                    'id' => $listing->id,
                    'old_title' => $oldName,
                    'new_title' => $freshListing->name,
                    'old_meta_title' => $oldMetaTitle,
                    'new_meta_title' => $freshListing->meta_title,
                    'old_meta_keywords' => $oldMetaKeywords,
                    'new_meta_keywords' => $freshListing->meta_keywords,
                    'old_meta_description' => $oldMetaDescription,
                    'new_meta_description' => $freshListing->meta_description,
                    'old_plan_id' => $oldPlanId,
                    'new_plan_id' => $freshListing->plan_id,
                    'private_plan_title' => $privatePlan->title,
                    'phone_number' => $listing->phone_number,
                    'whatsapp_number' => $freshListing->whatsapp_number,
                    'public_url' => route('front.listing.details', $freshListing->slug),
                    'edit_url' => route('admin.listing.edit', $listing->id),
                ];
            } catch (\Throwable $e) {
                $failed[] = [
                    'id' => $listing->id,
                    'title' => $listing->name,
                    'error' => $e->getMessage(),
                    'public_url' => route('front.listing.details', $listing->slug),
                    'edit_url' => route('admin.listing.edit', $listing->id),
                ];
            }
        }

        $status = empty($failed) ? 'success' : (empty($updated) ? 'failed' : 'success');
        $message = 'AI listing content cron completed. Updated: ' . count($updated) . ', Failed: ' . count($failed) . '.';
        $duration = microtime(true) - $startedAt;

        $cronLog->record($job, $status, $message, count($updated), $duration, [
            'updated' => $updated,
            'failed' => $failed,
            'batch_limit' => $batchLimit,
            'private_plan_id' => $privatePlan->id,
            'private_plan_title' => $privatePlan->title,
            'admin_cron_logs_url' => route('admin.cron.logs', ['job' => $job]),
        ]);

        return response()->json([
            'job' => $job,
            'status' => $status,
            'message' => $message,
            'processed' => count($updated),
            'failed' => count($failed),
            'batch_limit' => $batchLimit,
            'private_plan_id' => $privatePlan->id,
            'private_plan_title' => $privatePlan->title,
            'updated' => $updated,
            'failures' => $failed,
            'cron_logs_url' => route('admin.cron.logs', ['job' => $job]),
        ]);
    }

    private function formatBangladeshWhatsappNumber($phone)
    {
        return BangladeshPhoneService::normalize($phone) ?: '';
    }

    private function limitPlainText($value, $limit)
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $value)));

        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $limit));
    }

    public function pending(Request $request)
    {
        $user = Auth::user();
        $query = Listing::with('plan', 'user', 'category')->whereStatus('0');

        // Filter by plan type
        if ($request->filled('plan')) {
            $query = $query->whereHas('plan', function ($q) use ($request) {
                $q->where('plan_type', $request->plan);
            });
        }

        // Order and filter based on the user
        if ($user->id != 1) {
            $query = $query->where('user_id', $user->id)->orderBy('id', 'desc');
        } else {
            $query = $query->orderBy('id', 'desc');
        }
        $listings = $query->get();
        return view('admin.listing.pending', compact('listings'));
    }
    public function requiresreview(Request $request)
    {
        $user = Auth::user();
        $query = Listing::with('plan', 'user', 'category')->whereStatus('3');

        // Filter by plan type
        if ($request->filled('plan')) {
            $query = $query->whereHas('plan', function ($q) use ($request) {
                $q->where('plan_type', $request->plan);
            });
        }

        // Order and filter based on the user
        if ($user->id != 1) {
            $query = $query->where('user_id', $user->id)->orderBy('id', 'desc');
        } else {
            $query = $query->orderBy('id', 'desc');
        }
        $listings = $query->get();
        return view('admin.listing.requiresreview', compact('listings'));
    }

    public function approved(Request $request)
    {
        $user = Auth::user();
        $query = Listing::with('plan', 'user', 'category')->whereStatus('1');

        // Filter by plan type
        if ($request->filled('plan')) {
            $query = $query->whereHas('plan', function ($q) use ($request) {
                $q->where('plan_type', $request->plan);
            });
        }

        // Order and filter based on the user
        if ($user->id != 1) {
            $query = $query->where('user_id', $user->id)->orderBy('id', 'desc');
        } else {
            $query = $query->orderBy('id', 'desc');
        }
        $listings = $query->get();
        return view('admin.listing.approve', compact('listings'));
    }

    public function listingByViews(Request $request)
    {
        $user = Auth::user();

        $query = Listing::with('plan', 'user', 'category')
            ->whereStatus('1')
            ->withCount([
                'analytics as total_views' => function ($q) {
                    $q->where('event_type', 'view');
                },
            ]);

        // Filter by subscription plan
        if ($request->filled('plan')) {
            $query = $query->where('plan_id', $request->plan);
        }

        // Filter based on the logged in admin (super admin sees all)
        if ($user->id != 1) {
            $query = $query->where('user_id', $user->id);
        }

        $listings = $query->orderByDesc('total_views')->orderBy('id', 'desc')
            ->paginate(50)
            ->appends($request->query());
        $plans = Plan::orderBy('sorting', 'desc')->orderBy('id', 'desc')->get();
        $seoOffer = ListingSeoOfferSetting::firstOrCreate([], ListingSeoOfferSetting::defaults());

        return view('admin.listing.by_views', compact('listings', 'plans', 'seoOffer'));
    }

    public function listingByViewsAnalytics(Request $request, $id)
    {
        // Per-listing analytics for the admin with monthly / yearly / all-time reports
        $listing = Listing::findOrFail($id);

        $range = $request->input('range', 'all');
        $range = in_array($range, ['monthly', 'yearly', 'all']) ? $range : 'all';

        $startDate = [
            'monthly' => now()->startOfMonth(),
            'yearly'  => now()->startOfYear(),
        ][$range] ?? null; // all = full history

        $events = ListingAnalytics::where('listing_id', $id)
            ->when($startDate, function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->get();

        $totals = [
            'impressions' => $events->where('event_type', 'impression')->count(),
            'views'       => $events->where('event_type', 'view')->count(),
            'phone'       => $events->where('event_type', 'phone')->count(),
            'whatsapp'    => $events->where('event_type', 'whatsapp')->count(),
            'website'     => $events->where('event_type', 'website')->count(),
            'email'       => $events->where('event_type', 'email')->count(),
        ];

        if ($range === 'monthly') {
            // Daily bars for the current month so far
            $chart = collect(range(0, now()->day - 1))->map(function ($offset) use ($events) {
                $day = now()->startOfMonth()->addDays($offset);
                $dayKey = $day->format('Y-m-d');

                return [
                    'label'       => $day->format('d'),
                    'impressions' => $events->where('event_type', 'impression')->where('event_date', $dayKey)->count(),
                    'views'       => $events->where('event_type', 'view')->where('event_date', $dayKey)->count(),
                    'clicks'      => $events->whereIn('event_type', ['phone', 'whatsapp', 'website', 'email'])->where('event_date', $dayKey)->count(),
                ];
            });
        } elseif ($range === 'yearly') {
            // One bar per month for the current year so far
            $chart = collect(range(0, now()->month - 1))->map(function ($offset) use ($events) {
                $monthStart = now()->startOfYear()->addMonths($offset);
                $monthKey = $monthStart->format('Y-m');

                return [
                    'label'       => $monthStart->format('M'),
                    'impressions' => $events->where('event_type', 'impression')->filter(fn ($e) => str_starts_with((string) $e->event_date, $monthKey))->count(),
                    'views'       => $events->where('event_type', 'view')->filter(fn ($e) => str_starts_with((string) $e->event_date, $monthKey))->count(),
                    'clicks'      => $events->whereIn('event_type', ['phone', 'whatsapp', 'website', 'email'])->filter(fn ($e) => str_starts_with((string) $e->event_date, $monthKey))->count(),
                ];
            });
        } else {
            // All Time: monthly bars from first recorded data (cap 24 months)
            $firstDate = $events->min('event_date');
            $monthsBack = $firstDate
                ? min(24, \Carbon\Carbon::parse($firstDate)->diffInMonths(now()))
                : 0;

            $chart = collect(range($monthsBack, 0))->map(function ($offset) use ($events) {
                $monthStart = now()->subMonths($offset)->startOfMonth();
                $monthKey = $monthStart->format('Y-m');

                return [
                    'label'       => $monthStart->format('M y'),
                    'impressions' => $events->where('event_type', 'impression')->filter(fn ($e) => str_starts_with((string) $e->event_date, $monthKey))->count(),
                    'views'       => $events->where('event_type', 'view')->filter(fn ($e) => str_starts_with((string) $e->event_date, $monthKey))->count(),
                    'clicks'      => $events->whereIn('event_type', ['phone', 'whatsapp', 'website', 'email'])->filter(fn ($e) => str_starts_with((string) $e->event_date, $monthKey))->count(),
                ];
            });
        }

        $recentViews = ListingAnalytics::where('listing_id', $id)
            ->where('event_type', 'view')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.listing.by_views_analytics', compact('listing', 'totals', 'chart', 'range', 'recentViews'));
    }

    public function seoOfferSettings()
    {
        $setting = ListingSeoOfferSetting::firstOrCreate([], ListingSeoOfferSetting::defaults());
        return view('admin.listing.seo_offer_settings', compact('setting'));
    }

    public function seoOfferSettingsUpdate(Request $request)
    {
        $request->validate([
            'message_template' => 'required|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'yearly_save' => 'nullable|numeric|min:0',
            'contact_number' => 'required|string|max:30',
            'status' => 'required|in:0,1',
        ]);

        $setting = ListingSeoOfferSetting::firstOrCreate([], ListingSeoOfferSetting::defaults());

        $setting->message_template = $request->message_template;
        $setting->monthly_price = $request->monthly_price;
        $setting->yearly_price = $request->yearly_price;
        $setting->yearly_save = $request->yearly_save !== null && (float) $request->yearly_save > 0
            ? $request->yearly_save
            : max(0, (float) $request->yearly_price - 12 * (float) $request->monthly_price);
        $setting->contact_number = $request->contact_number;
        $setting->status = $request->status;
        $setting->save();

        Session::flash('success', 'SEO offer settings updated successfully.');
        return redirect()->route('admin.listing.seo.offer.settings');
    }

    public function reject(Request $request)
    {
        $user = Auth::user();
        $query = Listing::with('plan', 'user', 'category')->whereStatus('2');

        // Filter by plan type
        if ($request->filled('plan')) {
            $query = $query->whereHas('plan', function ($q) use ($request) {
                $q->where('plan_type', $request->plan);
            });
        }

        // Order and filter based on the user
        if ($user->id != 1) {
            $query = $query->where('user_id', $user->id)->orderBy('id', 'desc');
        } else {
            $query = $query->orderBy('id', 'desc');
        }
        $listings = $query->get();
        return view('admin.listing.reject', compact('listings'));
    }

    public function type()
    {
        session()->forget('type_id');
        session()->forget('category_id');
        $listing_types  = DB::table('listing_types')->where('status', 'active')->latest()->get();
        return view('admin.listing.type', compact('listing_types'));
    }


    public function BusinessTypeCategory($id)
    {
        $categories = Category::whereStatus(1)->where('type_id', $id)->orderBy('order_number', 'asc')->get();
        return response()->json($categories);
    }

    public function createType(Request $request)
    {
        session()->put('type_id', $request->type_id);
        session()->put('category_id', $request->category_id);
        return redirect()->route('admin.listing.create');
    }

    public function curl_get_contents($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data) {
            return $data;
        } else {
            return $data = null;
        }
    }


    public function create(Request $request)
    {
        $data['categories'] = Category::whereStatus(1)->orderBy('id', 'desc')->get();
        $data['divisions']  = Location::whereParentId(NULL)->whereStatus(1)->orderBy('id', 'desc')->get();
        $data['amenities']  = Amenity::whereStatus(1)->orderBy('id', 'desc')->get();
        $data['countries']  = Country::whereStatus(1)->orderBy('id', 'desc')->get();

        $data['type']        = session()->get('type_id');
        $data['category_id'] = session()->get('category_id');
        if ($data['category_id']) {
            $data['category']     = Category::where('id', $data['category_id'])->pluck('slug')->first();
        }

        // Get content from another website
        if ($request->url) {
            try {
                $parse      = parse_url($request->url);
                $domain     = $parse['host'];
                $website    = $this->curl_get_contents($request->url);
                libxml_use_internal_errors(true);
                $dom        = new DOMDocument();
                $dom->loadHTML($website);
                if ($domain == 'www.bangladeshyp.com') {
                    $name = "";
                    foreach ($dom->getElementsByTagName("h1")->item(0)->childNodes as $child) {
                        $name .= $dom->saveHTML($child);
                    }
                    $name_parts     = explode('-', $name);
                    $data['name']   = $name_parts[0];
                    $description    = "";
                    $location       = "";
                    $phone          = "";
                    $weblinks       = "";
                    $descElements   = $dom->getElementsByTagName("div");
                    foreach ($descElements as $element) {
                        $class = $element->getAttribute('class');
                        if (strpos($class, 'desc') !== false) {
                            $description .= strip_tags($element->textContent);
                        }
                        if (strpos($class, 'location') !== false) {
                            $location .= $element->textContent;
                        }
                        if (strpos($class, 'phone') !== false) {
                            $phone .= $element->textContent;
                        }
                        if (strpos($class, 'weblinks') !== false) {
                            $weblinks .= $element->textContent;
                        }
                    }
                    $data['details']    = $description;
                    $data['location']   = $location;
                    $data['phone']      = $phone;
                    $data['weblinks']   = $weblinks;
                }
            } catch (\Exception $e) {
                Toastr::error('Something Wrong. Please try again');
                return redirect()->route('admin.listing.create');
            }
        }
        return view('admin.listing.create', $data);
    }

    public function listingSlugCheck(Request $request)
    {
        $slug      = $request->input('slug');
        $checkSlug = Listing::where('slug', $slug)->first();
        if ($checkSlug) {
            return response()->json(['notunique' => true]);
        } else {
            return response()->json(['unique' => true]);
        }
    }

    public function listingUpdateSlugCheck(Request $request, $id)
    {
        $slug = $request->input('slug');
        $checkSlug = Listing::where('id', '!=', $id)->where('slug', $slug)->get();
        if ($checkSlug->isEmpty()) {
            return response()->json(['unique' => true]);
        } else {
            return response()->json(['notunique' => true]);
        }
    }


    public function store(ListingRequest $request)
    {
        $data           = new Listing();
        $input          = $request->all();


        $data->user_id  = Auth::user()->id ?? null;
        if (isset($input['photo']) && $input['photo']) {
            $status = ExtensionValidation($input['photo']);
            if (!$status) {
                return ['errors' => [0 => 'file format not supported']];
            }
            $input['photo'] = processImage($input['photo']);
        }
        $input['slug'] = Str::slug($request->name);
        $amenities = [];
        $amenity_icons = [];

        if ($request->amenities) {
            foreach ($request->amenities as $key => $amenity) {
                array_push($amenities, $amenity[0]);
                array_push($amenity_icons, $key);
            }
        }

        $schedule['sat_open']  = $request->saturday_opening;
        $schedule['sat_close'] = $request->saturday_closing;
        $schedule['sun_open']  = $request->sunday_opening;
        $schedule['sun_close'] = $request->sunday_closing;
        $schedule['mon_open']  = $request->monday_opening;
        $schedule['mon_close'] = $request->monday_closing;
        $schedule['tue_open']  = $request->tuesday_opening;
        $schedule['tue_close'] = $request->tuesday_closing;
        $schedule['wed_open']  = $request->wednesday_opening;
        $schedule['wed_close'] = $request->wednesday_closing;
        $schedule['thu_open']  = $request->thursday_opening;
        $schedule['thu_close'] = $request->thursday_closing;
        $schedule['fri_open']  = $request->friday_opening;
        $schedule['fri_close'] = $request->friday_closing;

        $input['amenities'] = json_encode($amenities, true);
        $input['amenity_icons'] = json_encode($amenity_icons, true);
        $input['schedules'] = json_encode($schedule, true);
        $data->fill($input)->save();

        // if ($request->hasFile('gallery')) {
        //     $images = $request->file('gallery');
        //     foreach ($images as $img) {
        //         $status = ExtensionValidation($img);
        //         if (!$status) {
        //             return ['errors' => [0 => 'file format not supported']];
        //         }
        //         $name = time() . '.webp';
        //         $imagePath = 'assets/images/' . $name;
        //         $canvasWidth = 850;
        //         $canvasHeight = 650;
        //         $imageInstance = Image::make($img);
        //         $imageInstance->fit($canvasWidth, $canvasHeight, function ($constraint) {
        //             $constraint->upsize();
        //         });
        //         Image::canvas($canvasWidth, $canvasHeight, '#EBEEF7')->insert($imageInstance, 'center')->save($imagePath);
        //         $watermark = 'assets/images/watermark.png';
        //         $watermark_image = Image::make($watermark);
        //         $watermarkSize = round(10 * $imageInstance->width() / 50);
        //         $watermark_image->resize($watermarkSize, null, function ($constraint) {
        //             $constraint->aspectRatio();
        //         });
        //         $watermarkedImage = Image::make($imagePath)->insert($watermark_image, 'bottom-right', 10, 10)->save($imagePath);
        //         Gallery::insert([
        //             'listing_id' => $data->id,
        //             'photo'      => $name
        //         ]);
        //     }
        // }

        if ($request->hasFile('gallery')) {
            $images = $request->file('gallery');
            foreach ($images as $img) {
                $status = ExtensionValidation($img);
                if (!$status) {
                    return ['errors' => [0 => 'file format not supported']];
                }
                $make_name = processImage($img);
                Gallery::insert([
                    'listing_id' => $data->id,
                    'photo'      => $make_name
                ]);
            }
        }


        if ($request->menu_title !== NULL) {
            foreach ($request->menu_title as $key => $title) {
                if ($title !== null) {
                    $item = new ListingMenu();
                    $item->listing_id = $data->id;
                    $item->menu_title = $title;
                    if (isset($request->menu_price[$key])) {
                        $item->menu_price = $request->menu_price[$key];
                    }

                    if (isset($request->menu_photo[$key])) {
                        $item->menu_photo = processImage($request->menu_photo[$key]);
                    }
                    $item->save();
                }
            }
        }

        if ($request->room_name !== NULL) {
            foreach ($request->room_name as $key => $name) {
                if ($name !== null) {
                    $room = new ListingRoom();
                    $room->listing_id = $data->id;
                    $room->room_name = $name;
                    if (isset($request->room_price[$key])) {
                        $room->room_price = $request->room_price[$key];
                        $room->currency_symbol  = $request->currency_symbol[$key];
                    }

                    if (isset($request->room_photo[$key])) {
                        $room->room_photo = processImage($request->room_photo[$key]);
                    }

                    if (isset($request->room_description[$key])) {
                        $room->room_description = $request->room_description[$key];
                    }

                    if ($request->room_amenities[$key]) {
                        $tag = sanitizeTag($request->room_amenities[$key]);

                        if (!empty($tag)) {
                            $room->room_amenities = $tag;
                        }
                    }
                    $room->save();
                }
            }
        }

        if ($request->service_name !== NULL) {
            foreach ($request->service_name as $key => $name) {
                if ($name !== NULL) {
                    $beauty = new ListingBeauty();
                    $beauty->listing_id = $data->id;
                    $beauty->service_name = $name;
                    if (isset($request->service_photo[$key])) {
                        $beauty->service_photo = processImage($request->service_photo[$key]);
                    }

                    if (isset($request->service_price[$key])) {
                        $beauty->service_price =  $request->service_price[$key];
                    }

                    if (isset($request->service_duration[$key])) {
                        $beauty->service_duration = $request->service_duration[$key];
                    }

                    if (isset($request->service_from[$key])) {
                        $beauty->service_from = $request->service_from[$key];
                    }

                    if (isset($request->service_to[$key])) {
                        $beauty->service_to = $request->service_to[$key];
                    }
                    $beauty->save();
                }
            }
        }

        if ($request->faq_name !== NULL) {
            foreach ($request->faq_name as $key => $title) {
                if ($title !== null) {
                    $faq = new ListingFaq();
                    $faq->listing_id = $data->id;
                    $faq->faq_name = $title;
                    if (isset($request->faq_details[$key])) {
                        $faq->faq_details = $request->faq_details[$key];
                    }
                    $faq->save();
                }
            }
        }


        session()->forget('type_id');
        session()->forget('category_id');
        $msg = __('New Data Added Successfully.') . ' ' . '<a href="' . route('admin.listing.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }


    public function editType($id)
    {
        $data['listing_types']  = DB::table('listing_types')->where('status', 'active')->latest()->get();
        $data['data']           = Listing::findOrFail($id);
        $data['categories']     = Category::whereStatus(1)->where('type_id', $data['data']->type)->orderBy('order_number', 'asc')->get();
        return view('admin.listing.edit_type', $data);
    }

    public function updteType(Request $request, $id)
    {
        session()->forget('type_id');
        session()->forget('category_id');
        session()->put('type_id', $request->type_id);
        session()->put('category_id', $request->category_id);

        return redirect()->route('admin.listing.edit', $id);
    }

    public function edit($id)
    {
        $data['plans'] = Plan::where('status', 1)->get();
        $data['data'] = Listing::findOrFail($id);
        $data['hasGatewayPayment'] = $data['data']->latestCompletedBkashPayment()->exists();
        $data['listingAmenities'] = $data['data']->amenities != NULL ? json_decode($data['data']->amenities, true) : [];
        $data['listingSchedules'] = $data['data']->schedules != NULL ? json_decode($data['data']->schedules, true) : [];

        $data['type']           = session()->get('type_id');
        $data['category_id']    = session()->get('category_id') ?: $data['data']->category_id;

        if ($data['category_id']) {
            $data['category']     = Category::where('id', $data['category_id'])->pluck('slug')->first();

            if ($data['category'] == 'restaurants') {
                $data['menus'] = ListingMenu::whereListingId($data['data']->id)->get();
            }

            if ($data['category'] == 'hotels') {
                $data['rooms'] = ListingRoom::whereListingId($data['data']->id)->get();
            }

            if (
                $data['category'] == 'beauty-spa' ||
                $data['category'] == 'universities' ||
                $data['category'] == 'lawyers' ||
                $data['category'] == 'ac-repair-services'
            ) {
                $data['beauties'] = ListingBeauty::whereListingId($data['data']->id)->get();
            }
        }

        $data['faqs'] = ListingFaq::whereListingId($data['data']->id)->get();
        $data['categories'] = Category::whereStatus(1)->orderBy('id', 'desc')->get();
        $data['divisions'] = Location::whereParentId(NULL)->whereStatus(1)->orderBy('id', 'desc')->get();
        if ($data['data']->division_id !== null) {
            $data['cities'] = Location::where('parent_id', $data['data']->division_id)->get();
        }
        $data['amenities'] = Amenity::whereStatus(1)->orderBy('id', 'desc')->get();
        $data['countries'] = Country::whereStatus(1)->orderBy('id', 'desc')->get();
        $data['category'] = $data['category'] ?? null;
        return view('admin.listing.edit', $data);
    }

    public function view($id)
    {
        $data['data']      = Listing::findOrFail($id);
        $data['amenities'] = $data['data']->amenities != NULL ? json_decode($data['data']->amenities, true) : [];
        $data['schedules'] = $data['data']->schedules != NULL ? json_decode($data['data']->schedules, true) : [];
        $data['galleries'] = Gallery::where('listing_id', $id)->get();
        $data['category']  = Category::where('id', $data['data']->category_id)->first();
        return view('admin.listing.view', $data);
    }




    public function update(ListingRequest $request, $id)
    {
        $data = Listing::findOrFail($id);
        $oldStatus = $data->status;
        $input = $request->all();
        unset($input['offline_payment_period']);
        unset($input['offline_payment_amount']);
        $input['is_feature'] = $request->has('is_feature') ? 1 : 0;
        $input['status'] = $request->has('status') ? 1 : 0;

        if($request->plan_id){
            $input['plan_id'] = $request->plan_id;
        }

        $selectedPlan = !empty($input['plan_id']) ? Plan::find($input['plan_id']) : $data->plan;
        $validOfflinePeriods = ['monthly', 'yearly', 'lifetime'];
        $isOfflinePaidListing = (int) $request->status === 1
            && $selectedPlan
            && $selectedPlan->plan_type !== 'free'
            && !$data->latestCompletedBkashPayment()->exists();
        $isApprovingOfflinePaidListing = $oldStatus != 1 && $isOfflinePaidListing;
        $shouldUpdateOfflinePaymentPeriod = $isOfflinePaidListing
            && in_array($request->offline_payment_period, $validOfflinePeriods, true);

        if ($isApprovingOfflinePaidListing && !in_array($request->offline_payment_period, $validOfflinePeriods, true)) {
            return ['errors' => [0 => 'Please select Monthly, Yearly or Lifetime for this offline payment approval.']];
        }

        $oldPlanStartDate = $data->plan_start_date;
        $offlinePaymentAmount = $request->filled('offline_payment_amount')
            ? (float) $request->offline_payment_amount
            : ($selectedPlan ? (float) $selectedPlan->price : 0.0);

        if ($shouldUpdateOfflinePaymentPeriod) {
            $startDate = Carbon::now();
            $input['plan_start_date'] = $startDate;

            if ($request->offline_payment_period === 'lifetime') {
                // Lifetime never expires (same as gateway lifetime purchases).
                $input['plan_end_date'] = null;
            } else {
                $durationDays = $request->offline_payment_period === 'yearly' ? 365 : 30;
                $input['plan_end_date'] = $startDate->copy()->addDays($durationDays - 1);
            }
        }
        

        if (isset($input['photo']) && $input['photo']) {
            $status = ExtensionValidation($input['photo']);
            if (!$status) {
                return ['errors' => [0 => 'file format not supported']];
            }
            $imagePath = 'assets/images/' . $data['photo'];
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $input['photo'] = processImage($input['photo']);
        }

        if ($request->hasFile('gallery')) {
            $images = $request->file('gallery');
            foreach ($images as $img) {
                $status = ExtensionValidation($img);
                if (!$status) {
                    return ['errors' => [0 => 'file format not supported']];
                }
                $make_name = processImage($img);
                Gallery::insert([
                    'listing_id' => $data->id,
                    'photo'      => $make_name
                ]);
            }
        }



        //$input['slug'] = Str::slug($request->name);

        $amenities = [];
        $amenity_icons = [];

        if ($request->amenities) {
            foreach ($request->amenities as $key => $amenity) {
                array_push($amenities, $amenity[0]);
                array_push($amenity_icons, $key);
            }
        }

        $schedule['sat_open']  = $request->saturday_opening;
        $schedule['sat_close'] = $request->saturday_closing;
        $schedule['sun_open']  = $request->sunday_opening;
        $schedule['sun_close'] = $request->sunday_closing;
        $schedule['mon_open']  = $request->monday_opening;
        $schedule['mon_close'] = $request->monday_closing;
        $schedule['tue_open']  = $request->tuesday_opening;
        $schedule['tue_close'] = $request->tuesday_closing;
        $schedule['wed_open']  = $request->wednesday_opening;
        $schedule['wed_close'] = $request->wednesday_closing;
        $schedule['thu_open']  = $request->thursday_opening;
        $schedule['thu_close'] = $request->thursday_closing;
        $schedule['fri_open']  = $request->friday_opening;
        $schedule['fri_close'] = $request->friday_closing;

        $input['amenities'] = json_encode($amenities, true);
        $input['amenity_icons'] = json_encode($amenity_icons, true);
        $input['schedules'] = json_encode($schedule, true);
        $data->update($input);

        // When an offline (non-gateway) paid plan is approved or renewed,
        // record it on the Transactions page so admin earnings stay accurate.
        if ($shouldUpdateOfflinePaymentPeriod) {
            $newStartDateOnly = $input['plan_start_date']->format('Y-m-d');
            $oldStartDateOnly = $oldPlanStartDate ? Carbon::parse($oldPlanStartDate)->format('Y-m-d') : null;

            if ($oldStartDateOnly !== $newStartDateOnly && $offlinePaymentAmount > 0) {
                $user = User::find($data->user_id);
                $durationDays = $request->offline_payment_period === 'yearly' ? 365 : 30;

                $trans = new Transaction();
                $trans->user_id = $data->user_id;
                $trans->plan_id = $selectedPlan->id;
                $trans->ad_id = $data->id;
                $trans->email = $user->email ?? '';
                $trans->phone = $data->phone_number ?: ($user->phone ?? '');
                $trans->amount = $offlinePaymentAmount;
                $trans->type = $selectedPlan->plan_type;
                $trans->plan_duration = $request->offline_payment_period === 'lifetime'
                    ? 'lifetime'
                    : $durationDays . ' days';
                $trans->plan_name = $selectedPlan->title;
                $trans->txnid = 'OFFLINE-' . $data->id . '-' . now()->format('YmdHis');
                $trans->status = 1;
                $trans->save();
            }
        }

        // Notify the listing owner when the status has just changed to approved.
        if ($oldStatus != 1 && $data->status == 1) {
            $gs    = Generalsetting::findOrFail(1);
            $user  = User::find($data->user_id);
            $admin = auth()->guard('admin')->user();
            if (isset($user) && $user != NULL) {
                $listingLink = route('front.listing.details', $data->slug);
                if ($gs->is_smtp == 1) {
                    $mailData = [
                        'to'           => $user->email,
                        'type'         => 'approved',
                        'cname'        => $user->name,
                        'oamount'      => $data->price,
                        'aname'        => $admin->name,
                        'aemail'       => $admin->email,
                        'listing_link' => $listingLink,
                    ];
                    $mailer = new GeniusMailer();
                    $mailer->sendAutoMail($mailData);
                } else {
                    $to = $user->email;
                    $subject = "Your Listing is approved.";
                    $msg = "Hello " . $user->name . "!\n\n";
                    $msg .= "Your Listing is approved by bdtalika Team.\n";
                    $msg .= "\nView Your Public Listing:\n";
                    $msg .= $listingLink . "\n";
                    $msg .= "\nThank you.";
                    $headers = "From: " . $gs->from_name . " <" . $gs->from_email . ">";
                    mail($to, $subject, $msg, $headers);
                }
            }
        }

        if ($request->menu_title) {
            foreach ($request->menu_title as $key => $title) {
                if (isset($request->menu_id[$key])) {
                    $item = ListingMenu::findOrFail($request->menu_id[$key]);
                    $item->listing_id = $data->id;
                    $item->menu_title = $title;
                    if (isset($request->menu_price[$key])) {
                        $item->menu_price = $request->menu_price[$key];
                    }

                    if (isset($request->menu_photo[$key])) {
                        $item->menu_photo = handleUpdateImage($request->menu_photo[$key], $item->menu_photo);
                    }
                    $item->update();
                } else {
                    if ($title !== null) {
                        $item = new ListingMenu();
                        $item->listing_id = $data->id;
                        $item->menu_title = $title;
                        if (isset($request->menu_price[$key])) {
                            $item->menu_price = $request->menu_price[$key];
                        }

                        if (isset($request->menu_photo[$key])) {
                            $item->menu_photo = processImage($request->menu_photo[$key]);
                        }
                        $item->save();
                    }
                }
            }
        }

        if ($request->room_name) {
            foreach ($request->room_name as $key => $name) {
                if (isset($request->room_id[$key])) {
                    $room = ListingRoom::findOrFail($request->room_id[$key]);
                    $room->listing_id = $data->id;
                    $room->room_name = $name;
                    if (isset($request->room_price[$key])) {
                        $room->room_price = $request->room_price[$key];
                    }

                    if (isset($request->currency_symbol[$key]) && $request->currency_symbol[$key] !== "") {
                        $room->currency_symbol = $request->currency_symbol[$key];
                    }

                    if (isset($request->room_photo[$key])) {
                        $room->room_photo = handleUpdateImage($request->room_photo[$key], $room->room_photo);
                    }

                    if (isset($request->room_description[$key])) {
                        $room->room_description = $request->room_description[$key];
                    }

                    if ($request->room_amenities[$key]) {
                        $tag = sanitizeTag($request->room_amenities[$key]);

                        if (!empty($tag)) {
                            $room->room_amenities = $tag;
                        }
                    }
                    $room->save();
                } else {
                    $room = new ListingRoom();
                    $room->listing_id = $data->id;
                    $room->room_name = $name;
                    if (isset($request->room_price[$key])) {
                        $room->room_price = $request->room_price[$key];
                    }
                    if (isset($request->currency_symbol[$key]) && $request->currency_symbol[$key] !== "") {
                        $room->currency_symbol = $request->currency_symbol[$key];
                    }

                    if (isset($request->room_photo[$key])) {
                        $room->room_photo = processImage($request->room_photo[$key]);
                    }

                    if (isset($request->room_description[$key])) {
                        $room->room_description = $request->room_description[$key];
                    }

                    if ($request->room_amenities[$key]) {
                        $tag = sanitizeTag($request->room_amenities[$key]);

                        if (!empty($tag)) {
                            $room->room_amenities = $tag;
                        }
                    }
                    $room->save();
                }
            }
        }

        if ($request->service_name) {
            foreach ($request->service_name as $key => $name) {
                if ($name !== null) {
                    if (isset($request->service_id[$key])) {
                        $beauty = ListingBeauty::findOrFail($request->service_id[$key]);
                        $beauty->listing_id = $data->id;
                        $beauty->service_name = $name;
                        if (isset($request->service_photo[$key])) {
                            $beauty->service_photo = processImage($request->service_photo[$key]);
                        }

                        if (isset($request->service_price[$key])) {
                            $beauty->service_price =  $request->service_price[$key];
                        }

                        if (isset($request->service_duration[$key])) {
                            $beauty->service_duration = $request->service_duration[$key];
                        }

                        if (isset($request->service_from[$key])) {
                            $beauty->service_from = $request->service_from[$key];
                        }

                        if (isset($request->service_to[$key])) {
                            $beauty->service_to = $request->service_to[$key];
                        }
                        $beauty->save();
                    } else {
                        $beauty = new ListingBeauty();
                        $beauty->listing_id = $data->id;
                        $beauty->service_name = $name;
                        if (isset($request->service_photo[$key])) {
                            $beauty->service_photo = processImage($request->service_photo[$key]);
                        }
                        if (isset($request->service_price[$key])) {
                            $beauty->service_price =  $request->service_price[$key];
                        }
                        if (isset($request->service_duration[$key])) {
                            $beauty->service_duration = $request->service_duration[$key];
                        }
                        if (isset($request->service_from[$key])) {
                            $beauty->service_from = $request->service_from[$key];
                        }
                        if (isset($request->service_to[$key])) {
                            $beauty->service_to = $request->service_to[$key];
                        }
                        $beauty->save();
                    }
                }
            }
        }

        if ($request->faq_name) {
            $existingFaqIds = is_array($request->faq_id) ? array_filter($request->faq_id) : [];
            ListingFaq::where('listing_id', $data->id)->whereNotIn('id', $existingFaqIds)->delete();
            foreach ($request->faq_name as $key => $title) {
                if ($title !== null) {
                    $faqId = isset($request->faq_id[$key]) ? $request->faq_id[$key] : null;
                    $faqDetails = isset($request->faq_details[$key]) ? $request->faq_details[$key] : null;
                    ListingFaq::updateOrCreate(
                        ['id' => $faqId],
                        [
                            'listing_id' => $data->id,
                            'faq_name' => $title,
                            'faq_details' => $faqDetails,
                        ]
                    );
                }
            }
        }

        session()->forget('type_id');
        session()->forget('category_id');
        $msg = __('Data Updated Successfully.') . ' ' . '<a href="' . route('admin.listing.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }

    public function status(Request $request, $id1, $id2)
    {
        $data = Listing::findOrFail($id1);
        $data->status = $id2;
        $data->update();
        $gs     = Generalsetting::findOrFail(1);
        $user   = User::find($data->user_id);
        $admin  = auth()->guard('admin')->user();
        if (isset($user) && $user != NULL) {
            $emailType = ($data->status == 0) ? "draft" : "approved";
            $listingLink = route('front.listing.details', $data->slug);
            if ($gs->is_smtp == 1) {
                $data = [
                    'to'        => $user->email,
                    'type'      => $emailType,
                    'cname'     => $user->name,
                    'oamount'   => $data->price,
                    'aname'     => $admin->name,
                    'aemail'    => $admin->email,
                    'listing_link' => $listingLink,
                ];
                $mailer         = new GeniusMailer();
                $mailer->sendAutoMail($data);
            } else {
                $to = $user->email;
                $subject = "Your Listing is " . $emailType . ".";
                $msg = "Hello " . $user->name . "!\n\n";
                $msg .= "Your Listing is " . $emailType . " by bdtalika Team.\n";
                if($data->status == 1){
                    $msg .= "\nView Your Public Listing:\n";
                    $msg .= $listingLink . "\n";
                }
                $msg .= "\nThank you.";
                $headers = "From: " . $gs->from_name . " <" . $gs->from_email . ">";
                mail($to, $subject, $msg, $headers);
            }
        }
        Session::flash('message', "Status Updated Successfully");
        return redirect()->back();
    }


    public function listingReject($id)
    {
        $listing = Listing::where('id', $id)->first();
        return view('partials.admin.listing_status', compact('listing'));
    }

    public function listingRejectSubmit(Request $request, $id)
    {
        
        $data = Listing::findOrFail($id);
        $data->reject_reason = $request->reject_reason;
        $data->status = 2;
        $data->update();
        $gs     = Generalsetting::findOrFail(1);
        $user   = User::find($data->user_id);
        $admin  = auth()->guard('admin')->user();
        if (isset($user) && $user != NULL) {
            if ($gs->is_smtp == 1) {
                
                $data = [
                    'to'        => $user->email,
                    'type'      => "Directory Rejected",
                    'cname'     => $user->name,
                    'oamount'   => $data->price,
                    'aname'     => $admin->name,
                    'aemail'    => $admin->email,
                    'wtitle'    => "",
                ];
                $mailer         = new GeniusMailer();
                $mailer->sendAutoMail($data);
            } else {
                $to = $user->email;
                $subject = "Your Listing is Rejected";
                // $msg = "Hello " . $user->name . "!\nYour directory is approve by" . $admin->name . " successfully.\nThank you.";
                $msg = "Hello " . $user->name . "!\nYour Listing is Rejected by bdtalika Team. Please check your dashboard for more details." . " \nThank you.";
                $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                mail($to, $subject, $msg, $headers);
            }
        }
        Toastr::success('Directories Rejected Successfully');
        return redirect()->back();
    }

    public function sendPackageReminderToDraftListings()
    {
        $start = microtime(true);
        $cronLog = app(CronLogService::class);

        try {
            $gs = Generalsetting::findOrFail(1);
            $now = Carbon::now(); // App timezone is Asia/Dhaka (Bangladesh time).
            $startOfToday = $now->copy()->startOfDay();
            $sent = 0;
            $skipped = 0;

            // The cPanel cron hits this endpoint many times a day, but reminders
            // should only go out within the 11 AM - 2 PM (Bangladesh time) window.
            // Outside that window we exit early without sending anything.
            if ($now->hour < 11 || $now->hour > 14) {
                $cronLog->record(
                    'listing-package-reminders',
                    'success',
                    'Package reminder cron skipped: outside 11 AM - 2 PM (BD) send window.',
                    0,
                    microtime(true) - $start,
                    ['sent' => 0, 'skipped' => 0, 'matched' => 0, 'hour' => $now->hour]
                );

                return response()->json([
                    'status' => true,
                    'message' => 'Outside send window (11 AM - 2 PM Bangladesh time). Nothing sent.',
                    'sent' => 0,
                    'skipped' => 0,
                ]);
            }

            // Send at most once per calendar day (Bangladesh time): skip listings
            // that already received a reminder today.
            $listings = Listing::with('user')
                ->where('status', 0)
                ->where(function ($query) use ($startOfToday) {
                    $query->whereNull('package_reminder_sent_at')
                        ->orWhere('package_reminder_sent_at', '<', $startOfToday);
                })
                ->get();

            // Prices are always pulled live from the plans table (once per run).
            $plans = $this->getPublicPaidPlans();
            $monthlyPrices = $plans->where('plan_type', 'monthly')
                ->pluck('price')
                ->map(fn ($p) => (float) $p)
                ->sort()
                ->values();
            $cheapestMonthly = $monthlyPrices->isNotEmpty()
                ? $this->formatPlanPrice($monthlyPrices[0])
                : null;
            $subject = $cheapestMonthly !== null
                ? 'Pay Only ' . $cheapestMonthly . ' Taka Monthly to Activate Your Business - Pay by Bkash.'
                : 'Activate Your Business - Pay by Bkash.';

            foreach ($listings as $listing) {
            $user = $listing->user;

            if (!$user || !$user->email) {
                $skipped++;
                continue;
            }
            $bkashNumber = '+88 01738411242';
            $whatsappUrl = 'https://wa.me/8801738411242';
            $pricingUrl = 'https://bdtalika.com/user/listing';

            $htmlMessage  = '<p>Dear <strong>' . e($user->name) . '</strong>,</p>';
            $htmlMessage .= '<p>Your business <strong>' . e($listing->name) . '</strong> is currently in Draft. Activate a package so customers can see it on BDTalika.</p>';
            $htmlMessage .= '<p><strong>Packages:</strong><br>';
            foreach ($this->planHtmlLines($plans) as $line) {
                $htmlMessage .= $line;
            }
            $htmlMessage .= ($plans->where('plan_type', 'monthly')->isNotEmpty())
                ? '<small>Monthly packages are billed annually.</small></p>'
                : '</p>';
            $htmlMessage .= '<p><strong>bKash:</strong> ' . $bkashNumber . '</p>';
            $htmlMessage .= '<p><a href="' . $pricingUrl . '">View Packages</a></p>';
            $htmlMessage .= '<p><a href="' . $whatsappUrl . '" style="display:inline-block;background:#25D366;color:#ffffff;padding:10px 16px;border-radius:4px;text-decoration:none;font-weight:bold;">Contact on WhatsApp</a></p>';
            $htmlMessage .= '<p>Thank you,<br>BDTalika Team<br><a href="https://bdtalika.com">bdtalika.com</a></p>';

            $message = strip_tags(str_replace(['<br>', '<br><br>', '</p>'], "\n", $htmlMessage));

            if ($gs->is_smtp == 1) {
                $mailer = new GeniusMailer();
                $mailer->sendListingMail([
                    'title' => $listing->name,
                    'to' => $user->email,
                    'subject' => $subject,
                    'body' => $htmlMessage,
                ]);
            } else {
                $headers = "From: " . $gs->from_name . " <" . $gs->from_email . ">";
                mail($user->email, $subject, $message, $headers);
            }

            $listing->package_reminder_sent_at = $now;
            $listing->save();
            $sent++;
        }

            $cronLog->record(
                'listing-package-reminders',
                'success',
                "Package reminder cron completed. Sent: {$sent}, Skipped: {$skipped}.",
                $sent,
                microtime(true) - $start,
                ['sent' => $sent, 'skipped' => $skipped, 'matched' => $listings->count()]
            );

            return response()->json([
                'status' => true,
                'message' => 'Package reminder cron completed.',
                'sent' => $sent,
                'skipped' => $skipped,
            ]);
        } catch (\Throwable $e) {
            $cronLog->record(
                'listing-package-reminders',
                'failed',
                'Package reminder cron failed: ' . $e->getMessage(),
                $sent ?? 0,
                microtime(true) - $start
            );

            return response()->json([
                'status' => false,
                'message' => 'Package reminder cron failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function menuDestroy($id)
    {
        $data = ListingMenu::findOrFail($id);
        @unlink('assets/images/' . $data->photo);
        $data->delete();

        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }

    public function faqDestroy($id)
    {
        ListingFaq::findOrFail($id)->delete();
        Session::flash('message', "Data Deleted Successfully");
        return redirect()->back();
    }

    public function serviceDestroy($id)
    {
        ListingBeauty::findOrFail($id)->delete();
        Session::flash('message', "Data Deleted Successfully");
        return redirect()->back();
    }

    public function roomDestroy($id)
    {
        $data = ListingRoom::findOrFail($id);
        @unlink('assets/images/' . $data->photo);
        $data->delete();

        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }

    public function bulkdelete(Request $request)
    {
        $ids = $request->bulk_id;
        $bulk_ids = explode(",", $ids);
        foreach ($bulk_ids as $key => $id) {
            if ($id) {
                try {
                    $this->delete($id);
                    $msg = 'Data Deleted Successfully.';
                } catch (\Throwable $th) {
                    $msg = 'Something went wrong.';
                }
            }
        }
        return response()->json($msg);
    }

    public function destroy($id)
    {
        try {
            $this->delete($id);
            $msg = 'Data Deleted Successfully.';
        } catch (\Throwable $th) {
            $msg = 'Something went wrong.';
        }
        return response()->json($msg);
    }

    public function delete($id)
    {
        $data = Listing::findOrFail($id);
        if ($data->galleries != NULL) {
            foreach ($data->galleries as $gallery) {
                @unlink('assets/images/' . $gallery->photo);
                $gallery->delete();
            }
        }

        if ($data->menus != NULL) {
            foreach ($data->menus as $menu) {
                @unlink('assets/images/' . $menu->menu_photo);
                $menu->delete();
            }
        }

        if ($data->services != NULL) {
            foreach ($data->services as $service) {
                @unlink('assets/images/' . $service->service_photo);
                $service->delete();
            }
        }

        if ($data->rooms != NULL) {
            foreach ($data->rooms as $room) {
                @unlink('assets/images/' . $room->room_photo);
                $room->delete();
            }
        }

        if ($data->faqs != NULL) {
            foreach ($data->faqs as $faq) {
                $faq->delete();
            }
        }

        if ($data->wishlists != NULL) {
            foreach ($data->wishlists as $wishlist) {
                $wishlist->delete();
            }
        }

        if ($data->recentviews != NULL) {
            foreach ($data->recentviews as $recentview) {
                $recentview->delete();
            }
        }

        if ($data->bookings != NULL) {
            foreach ($data->bookings as $booking) {
                $booking->delete();
            }
        }

        if ($data->enquiries != NULL) {
            foreach ($data->enquiries as $enquiry) {
                $enquiry->delete();
            }
        }

        if ($data->reviews != NULL) {
            foreach ($data->reviews as $review) {
                $review->delete();
            }
        }

        @unlink('assets/images/' . $data->photo);
        $data->delete();

        return true;
    }

    public function claimListing()
    {
        $data = DB::table('business_claims')->join('listings', 'listings.id', '=', 'business_claims.listing_id')->select('business_claims.*', 'listings.name')->orderBy('business_claims.id', 'desc')->get();
        return view('admin.listing.claim_listing', compact('data'));
    }

   public function claimListingSolve($id){
        try {
            $claim = DB::table('business_claims')->where('id', $id)->first();

            if (! $claim) {
                Toastr::error('Claim not found.');
                return redirect()->back();
            }

            // The claimer is identified by email (claims are submitted from the
            // public site without a user account link), so find their account.
            $claimer = User::where('email', $claim->email)->first();

            if (! $claimer) {
                Toastr::error('No registered user found with the claimer email (' . $claim->email . '). Ownership was NOT transferred. Please ask the claimer to register on the website first.');
                return redirect()->back();
            }

            $listing = Listing::find($claim->listing_id);

            if (! $listing) {
                Toastr::error('The listing attached to this claim no longer exists.');
                return redirect()->back();
            }

            // Transfer listing ownership to the claimer
            $listing->user_id = $claimer->id;
            $listing->save();

            // Mark the claim as resolved
            DB::table('business_claims')
                ->where('id', $id)
                ->update([
                    'status' => 2
                ]);

            // Log the ownership change
            UserLog::create([
                'admin_id' => Auth::guard('admin')->user()->id,
                'user_id' => $claimer->id,
                'action' => 'claim_ownership_transfer',
                'description' => 'Listing "' . $listing->name . '" (ID ' . $listing->id . ') ownership transferred to user "' . $claimer->name . '" (ID ' . $claimer->id . ') via claim #' . $id,
                'ip_address' => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 255),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
            ]);

            // Notify the new owner
            try {
                $config = Generalsetting::findOrFail(1);
                $subject = 'You are now the owner of "' . $listing->name . '" on BDTalika';
                $body = '<p>Dear ' . e($claimer->name) . ',</p>'
                    . '<p>Your ownership claim for <strong>' . e($listing->name) . '</strong> has been approved. You are now the owner of this listing on BDTalika.</p>'
                    . '<p>You can manage your listing (photos, phone number, address, opening hours, packages) from your dashboard: <a href="' . route('user.dashboard') . '">BDTalika User Dashboard</a></p>'
                    . '<p>Thank you,<br>BDTalika Team</p>';

                if ($config->is_smtp == 1) {
                    (new GeniusMailer())->sendListingMail([
                        'title' => 'BDTalika',
                        'to' => $claimer->email,
                        'subject' => $subject,
                        'body' => $body,
                    ]);
                } else {
                    $headers = "From: " . $config->from_name . " <" . $config->from_email . ">";
                    mail($claimer->email, $subject, strip_tags(str_replace(['<br>', '<br><br>', '</p>'], "\n", $body)), $headers);
                }

                EmailLog::create([
                    'user_id' => $claimer->id,
                    'email' => $claimer->email,
                    'subject' => $subject,
                    'body' => $body,
                    'mailer' => 'claim-ownership-transfer',
                ]);
            } catch (\Exception $e) {
                // Ownership is transferred even if the email fails
            }

            Toastr::success('Ownership transferred to ' . $claimer->name . ' (' . $claimer->email . '). They now own this listing and have been notified by email.');
        } catch (\Exception $e) {
            Toastr::error('Something went wrong. Please try again.');
        }

        return redirect()->back();
    }


    public function claimListingView($id)
    {
        $data = DB::table('business_claims')->where('id', $id)->first();
        return response()->json(['data' => $data]);
    }

    public function reportListing()
    {
        $data = DB::table('report_business')->join('listings', 'listings.id', '=', 'report_business.listing_id')->select('report_business.*', 'listings.name')->orderBy('report_business.id', 'desc')->get();
        return view('admin.listing.report_listing', compact('data'));
    }
    public function reportListingView($id)
    {
        $data = DB::table('report_business')->where('id', $id)->first();
        return response()->json(['data' => $data]);
    }

    public function reportListingDelete($id){
        try {
            DB::table('report_business')
                ->where('id', $id)
                ->delete();

            Toastr::success('Report listing deleted successfully.');
        } catch (\Exception $e) {
            Toastr::error('Something went wrong. Please try again.');
        }

        return redirect()->back();
    }


    public function Review($id)
    {
        $data['data']       = Listing::findOrFail($id);
        $data['listing_id'] = ListingReview::where('listing_id', $id)->select('listing_id')->first();
        $data['reviews']    = ListingReview::where('status', 1)->where('listing_id', $id)->get();
        return view('admin.listing.get_review', $data);
    }


    public function getReview(Request $request)
    {

        $request->validate([
            'listing_name' => 'required',
            'address' => 'required',
        ]);

        function getBusinessDetails($apiKey, $placeId)
        {
            $url = "https://maps.googleapis.com/maps/api/place/details/json?key=$apiKey&placeid=$placeId";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($response, true);
            return $data;
        }

        function getPlaceId($apiKey, $businessName, $location)
        {
            $businessName = urlencode($businessName);
            $location     = urlencode($location);
            $url          = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=$businessName%20$location&inputtype=textquery&fields=place_id&key=$apiKey";
            $ch         = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response   = curl_exec($ch);
            curl_close($ch);
            $data       = json_decode($response, true);
            if ($data['status'] == 'OK' && !empty($data['candidates'])) {
                return $data['candidates'][0]['place_id'];
            } else {
                return null;
            }
        }

        $businessName   = $request->listing_name;
        $listing_id     = $request->listing_id;
        $location       = $request->address;
        // $location       = '20, 21 Kawran Bazar Rd, Dhaka 1215';
        $apiKey         = 'AIzaSyB37zf1K04p-YbGtUFmYi9rqfJWmMwrS_U';
        $placeId        = getPlaceId($apiKey, $businessName, $location);
        if ($placeId) {
            $details = getBusinessDetails($apiKey, $placeId);
            if ($details['status'] == 'OK') {
                $result             = $details['result'];
                $average_rating     = $result['rating'] ?? '';
                $total_review       = $result['user_ratings_total'] ?? '';
                $reviews            = isset($result['reviews']) ? $result['reviews'] : [];
                if ($total_review != null) {
                    Listing::where('id', $listing_id)->update([
                        'average_rating' => $average_rating,
                        'total_review'   => $total_review
                    ]);
                }
                if (!empty($reviews)) {
                    foreach ($reviews as $reviewData) {
                        $review                 = new ListingReview;
                        $review['name']         = $reviewData['author_name'];
                        $review['rate']         = $reviewData['rating'];
                        $review['message']      = $reviewData['text'];
                        $review['listing_id']   = $listing_id;
                        $review['user_photo']   = isset($reviewData['profile_photo_url']) ? $reviewData['profile_photo_url'] : null;
                        $time = $reviewData['time'];
                        $review['reviewed_at'] = date('d M, Y', $time);
                        $review->save();
                    }
                    Toastr::success('Review Save Successfully');
                    return redirect()->back();
                } else {
                    Toastr::error('Review not available for this business');
                    return redirect()->back();
                }
            } else {
                Toastr::error('Error fetching business details:' . $details['status']);
                return redirect()->back();
            }
        } else {
            Toastr::error('Error finding Place ID for the given business.');
            return redirect()->back();
        }
    }

    public function updateReview(Request $request, $id)
    {
        try {
            $messages       = $request->message;
            $reviewIds      = $request->review_id;
            foreach ($messages as $index => $message) {
                $reviewId       = $reviewIds[$index];
                ListingReview::where('id', $reviewId)->update([
                    'message' => $message
                ]);
            }
            Toastr::success('Reviews Updated Successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Something went wrong. Please try again.');
            return redirect()->back();
        }
    }

    /**
     * Active, public, paid plans - prices here are always current (DB driven).
     */
    private function getPublicPaidPlans()
    {
        return Plan::where('status', 1)
            ->where('is_private', 0)
            ->where('plan_type', '!=', 'free')
            ->orderBy('price', 'asc')
            ->get();
    }

    private function formatPlanPrice($price)
    {
        $formatted = number_format((float) $price, 2, '.', '');
        $formatted = rtrim(rtrim($formatted, '0'), '.');
        return $formatted === '' ? '0' : $formatted;
    }

    /**
     * "15 tk or 25 tk" style headline from active monthly plan prices.
     */
    private function monthlyPriceHeadline($plans, $prefix = 'Pay via Bkash only ', $suffix = ' to get approval:')
    {
        $monthlyPrices = $plans->where('plan_type', 'monthly')
            ->pluck('price')
            ->map(fn ($p) => (float) $p)
            ->sort()
            ->values();

        if ($monthlyPrices->count() >= 2) {
            return $prefix
                . $this->formatPlanPrice($monthlyPrices[0]) . ' tk or '
                . $this->formatPlanPrice($monthlyPrices[1]) . ' tk'
                . $suffix;
        }

        if ($monthlyPrices->count() === 1) {
            return $prefix . $this->formatPlanPrice($monthlyPrices[0]) . ' tk' . $suffix;
        }

        return $prefix . 'a package' . $suffix;
    }

    /**
     * "1. Basic Monthly: 15 Tk" style lines for WhatsApp/plain-text messages.
     */
    private function planLines($plans)
    {
        $lines = [];
        $i = 1;
        foreach ($plans as $plan) {
            $lines[] = $i . '. ' . $plan->title . ': ' . $this->formatPlanPrice($plan->price) . ' Tk';
            $i++;
        }
        return $lines;
    }

    /**
     * "Basic Monthly: 15 Tk<br>" style lines for HTML emails.
     */
    private function planHtmlLines($plans)
    {
        $lines = [];
        foreach ($plans as $plan) {
            $lines[] = e($plan->title) . ': ' . $this->formatPlanPrice($plan->price) . ' Tk<br>';
        }
        return $lines;
    }

    public function whatsappReminder(Listing $listing)
    {
        $listing->loadMissing('user');

        // 1. Resolve the phone number (listing user phone first, then listing phone_number).
        $rawPhone = $listing->user->phone ?? $listing->phone_number;

        if (empty($rawPhone)) {
            Toastr::error('No phone number found for this listing.');
            return redirect()->back();
        }

        // 2. Format Bangladesh phone number.
        $phone = BangladeshPhoneService::normalize($rawPhone);
        if (!$phone) {
            Toastr::error('No valid Bangladesh phone number found for this listing.');
            return redirect()->back();
        }

        // 3. Enforce a 5-day cooldown per listing (based on latest log).
        $lastLog = WhatsAppReminderLog::where('listing_id', $listing->id)
            ->where('type', 'package_activation')
            ->latest('created_at')
            ->first();

        if ($lastLog && $lastLog->created_at->gt(now()->subDays(5))) {
            $availableAt = $lastLog->created_at->copy()->addDays(5);
            Toastr::error('WhatsApp reminder already sent. You can send again after ' . $availableAt->format('d M Y h:i A') . '.');
            return redirect()->back();
        }

        // 4. Build the message (prices always pulled live from the plans table).
        $plans = $this->getPublicPaidPlans();
        $userName = $listing->user->name ?? 'Business Owner';
        $message  = "Dear {$userName},\n\n";
        $message .= "Your business \"{$listing->name}\" on bdtalika.com is still in Draft, so customers cannot see it.\n\n";
        $message .= "Activate your business today and get more visibility, and SEO benefits.\n\n";
        $message .= $this->monthlyPriceHeadline($plans) . "\n";
        foreach ($this->planLines($plans) as $line) {
            $message .= $line . "\n";
        }
        $message .= "\n";
        $message .= "Choose plan / payment details:\n";
        $message .= "https://bdtalika.com/user/listing\n\n";
        $message .= "Need help?\n";
        $message .= "Email: support@bdtalika.com\n";
        $message .= "Hotline: +88 01909791959\n\n";
        $message .= "Thank you,\n";
        $message .= "Bdtalika Team\n";
        $message .= "https://bdtalika.com";

        // 5. Save the log before redirecting.
        WhatsAppReminderLog::create([
            'listing_id' => $listing->id,
            'user_id'    => $listing->user_id,
            'phone'      => $phone,
            'message'    => $message,
            'type'       => 'package_activation',
            'sent_by'    => Auth::guard('admin')->id(),
        ]);

        // 6. Redirect to WhatsApp click-to-chat (no automated send).
        $url = 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
        return redirect()->away($url);
    }

    public function whatsappReminderLogs(Listing $listing)
    {
        $logs = WhatsAppReminderLog::where('listing_id', $listing->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'datetime' => $log->created_at->format('d M Y - h:i A'),
                    'phone'    => $log->phone,
                    'type'     => ucwords(str_replace('_', ' ', $log->type)),
                ];
            });

        return response()->json([
            'listing' => $listing->name,
            'logs'    => $logs,
        ]);
    }

    public function listingMailPage(Request $request, $id){
        $listing = Listing::with(['plan', 'user', 'latestCompletedBkashPayment', 'latestTransaction'])->findOrFail($id);
        $mailType = $request->get('type') === 'renewal' ? 'renewal' : 'draft';
        $plans = $this->getPublicPaidPlans();
        return view('admin.listing.draft_mail', compact('listing', 'mailType', 'plans'));
    }

    public function driftListingMail(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
        ]);

  
        $admin  = Auth::guard('admin')->user();
        $listing  = Listing::where('id', $request->listing_id)->select('name')->first();

        if (!$listing) {
            Toastr::error('Listing not found');
            return redirect()->back();
        }

        $to         = $request->to;
        $subject    = $request->subject;
        $msg        = $request->message;
        $from       = $admin->email;
        $gs = Generalsetting::findOrFail(1); 
        if ($gs->is_smtp == 1) {
            $datas = [
                'title'   => $listing->name,
                'to'      => $to,
                'subject' => $subject,
                'body'    => $msg ?? "Your listing is drafted",
            ];
            $mailer = new GeniusMailer();
            $mailer->sendListingMail($datas);
        } else {
            $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
            mail($to, $subject, $msg, $headers);
        }

        Toastr::success('Mail Sent Successfully');

        if ($request->redirect_to === 'paid') {
            return redirect()->route('admin.listing.paid');
        }

        return redirect()->route('admin.listing.pending');
    }
}
