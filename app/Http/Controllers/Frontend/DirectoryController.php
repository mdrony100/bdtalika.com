<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\City;
use App\Models\User;
use App\Models\Admin;
use App\Models\Holiday;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Blog;
use App\Models\Follower;
use App\Models\Location;
use App\Models\Wishlists;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\ListingReview;
use App\Models\Generalsetting;
use App\Models\ListingAnalytics;
use App\Models\ListingEnquiry;
use App\Models\RecentViewsListing;
use App\Classes\GeniusMailer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DirectoryController extends Controller
{
    public function listing(Request $request, $area_query = 'bangladesh', $category_query = null)
    {
        $data['all_categories'] = Category::whereStatus(1)->orderBy('order_number', 'asc')->get();
        $data['top_categories'] = Category::whereStatus(1)->where('is_top', 1)->orwhere('is_grid', 1)->with('listings')->orderBy('title', 'asc')->orderBy('order_number', 'asc')->limit(20)->get();
        $data['divisions']      = Location::whereParentId(NUll)->whereStatus(1)->orderBy('order_number', 'asc')->get();
        $data['cities']         = Location::whereNotNull('parent_id')->whereStatus(1)->with('areas')->with('ads')->orderBy('order_number', 'asc')->get();
        // Plan priority: lifetime first, then monthly (premium before basic = higher price first), then yearly, then free
        $query                  = Listing::select('listings.*')
            ->leftJoin('plans', 'listings.plan_id', '=', 'plans.id')
            ->where('listings.status', 1)
            ->orderByRaw("CASE plans.plan_type WHEN 'lifetime' THEN 0 WHEN 'monthly' THEN 1 WHEN 'yearly' THEN 2 WHEN 'free' THEN 3 ELSE 4 END ASC")
            ->orderByRaw('COALESCE(plans.price, 0) DESC')
            ->orderBy('listings.is_feature', 'desc');
        // category
        if ($category_query) {
            $category           = Category::where('slug', $category_query)->first();
            $data['category']   = $category;
            if (!empty($category)) {
                $data['category_query_display']  = ucfirst(str_replace("-", " ", $category_query));
                $query->where('category_id', $category->id)->get();
            }

            // type wise related category
            $data['related_categories'] = [];
            if ($category && $category->type_id) {
                $data['related_categories'] = Category::whereStatus(1)
                    ->where('type_id', $category->type_id)
                    ->get();
            }
        }

        if ($area_query) {
            if ($area_query != 'bangladesh') {
                $division           = Location::whereParentId(NUll)->whereStatus(1)->where('slug', $area_query)->first();
                $city               = Location::whereStatus(1)->where('parent_id', '!=', NUll)->where('slug', $area_query)->first();
                $data['division']   = $division;
                $data['city']       = $city;
                if ($division) {
                    $data['area_query_display']       = ucfirst(str_replace("-", " ", $area_query));
                    $query->where('division_id', $division->id);
                } elseif ($city) {
                    $data['area_query_display']       = ucfirst(str_replace("-", " ", $area_query));
                    $query->where('city_id', $city->id);
                } else {
                    $area = Area::active()->where('slug', $area_query)->first();
                    $data['area']       = $area;
                    if (!empty($area)) {
                        $data['area_query_display']       = ucfirst(str_replace("-", " ", $area_query));
                        $query->where('area_id', $area->id)->get();
                    }
                }
            }
        }
        // keywords
        if ($request->keywords != null) {
            $search_keywords = $request->keywords;
            DB::table('search_keywords')->insert(['keywords' => $search_keywords]);

            if ($request->has('keywords') && $request->keywords != null) {
                $query->where('name', 'LIKE', "%$request->keywords%");
            }

            $keywords = explode(" ", $request->keywords);
            // Keywords match with division
            $query->orWhereHas('division', function ($query) use ($keywords) {
                $query->where(function ($query) use ($keywords) {
                    foreach ($keywords as $item) {
                        $query->orWhere('name', 'LIKE', "%$item%");
                    }
                });
            });

            // Keywords match with area
            $query->orWhereHas('area', function ($query) use ($keywords) {
                $query->where(function ($query) use ($keywords) {
                    foreach ($keywords as $item) {
                        $query->orWhere('title', 'LIKE', "%$item%");
                    }
                });
            });
        }

        $data['area_query']         = $area_query == null ? 'bangladesh' : $area_query;
        $data['get_category_query'] = $category_query;
        $data['listings']           =  $query->orderBy('listings.id', 'desc')->paginate(20);

        // SEO: unique title + description for every category/location combination
        $categoryDisplay = $data['category_query_display'] ?? '';
        $locationDisplay  = $data['area_query_display'] ?? 'Bangladesh';
        $locationEntity   = !empty($division) ? $division : (!empty($city) ? $city : (!empty($area) ? $area : null));

        if (!empty($category)) {
            $data['meta_title']    = $category->meta_title ?? $category->title;
            $data['selected_area'] = 'in ' . $locationDisplay;

            // SEO: link matching published blog guides from the category page
            $catWords = preg_split('/[\s,&|]+/u', trim($categoryDisplay ?: $category->title));
            $catWord  = '';
            foreach ($catWords as $w) {
                if (preg_match('/[A-Za-z]/u', $w)) {
                    $catWord = rtrim($w, 's');
                    break;
                }
            }
            $data['related_guides'] = ($catWord !== '')
                ? Blog::where('status', 1)->where('title', 'LIKE', "%{$catWord}%")
                    ->orderByDesc('created_at')->limit(3)->get(['title', 'slug'])
                : collect([]);
            $data['meta_description'] = $category->meta_description
                ?? 'Find the best ' . mb_strtolower($categoryDisplay) . ' in ' . $locationDisplay
                . ($locationDisplay !== 'Bangladesh' ? ', Bangladesh' : '')
                . '. Addresses, phone numbers, reviews and opening hours on BDTalika, the free business directory of Bangladesh.';
        } elseif (!empty($locationEntity)) {
            $data['meta_title']    = $locationEntity->meta_title ?? 'Bangladesh Business Directory List';
            $data['selected_area'] = stripos($data['meta_title'], $locationDisplay) === false ? 'in ' . $locationDisplay : '';
            $data['meta_description'] = $locationEntity->meta_description
                ?? 'Browse the complete business directory of ' . $locationDisplay
                . ', Bangladesh. Companies, hospitals, restaurants, hotels and local services with addresses and phone numbers on BDTalika.';
        } else {
            $data['meta_title']    = 'Bangladesh Business Directory List | Companies & Services in Bangladesh - BDTalika';
            $data['selected_area'] = '';
        }

        // full <title> tag including brand (H1 in the view uses meta_title + selected_area)
        $data['seo_title'] = trim($data['meta_title'] . ' ' . $data['selected_area']);
        if (strpos($data['seo_title'], 'BDTalika') === false) {
            $data['seo_title'] .= ' | BDTalika';
        }
        return view('frontend.list', $data);
    }


    public function listingSearch(Request $request, $type = NUll, $area = NUll)
    {
        $query = Listing::whereStatus(1)->where('type', $type);
        if ($area) {
            $query->whereHas('area', function ($q) use ($area) {
                $q->where('slug', $area);
            });
        }
        $data['all_categories'] = Category::whereStatus(1)->with('listings')->orderBy('order_number', 'asc')->get();
        $data['divisions']      = Location::whereParentId(NUll)->whereStatus(1)->with('listings')->orderBy('order_number', 'asc')->get();
        $data['cities']         = Location::where('parent_id', '!=', NUll)->whereStatus(1)->orderBy('order_number', 'asc')->get();
        $data['listings']       =  $query->orderBy('id', 'desc')->paginate(12);
        $data['recent_added']   = Listing::whereStatus(1)->orderBy('id', 'desc')->limit(15)->get();
        return view('frontend.list_search', $data);
    }


    public function listingDetails($slug)
    {
        $data       = Listing::whereHas('category')->whereSlug($slug)->whereIn('status', ['1', '4'])->first();
         // Proper SEO-friendly 404
        if (!$data) {
            abort(404);
        }
            $data['amenities']  = $data->amenities != NULL ? json_decode($data->amenities, true) : [];
            $data['schedules']  = $data->schedules != NULL ? json_decode($data->schedules, true) : [];
            $data['reviews']    = ListingReview::whereListingId($data->id)->whereStatus(1)->get();
            $data['gs']         = DB::table('generalsettings')->first();
            $data['data']       = $data;
            $data['categoryType'] = Category::where('id', $data->category_id)->first();
            //for button condition
            $plan = getListingPlan($data->plan_id);
            $isFreePlan = !isset($plan) || $plan->plan_type == 'free';
            $data['isFreePlan'] = $isFreePlan;
            $data['isOwner'] = auth()->check() && auth()->id() == $data->user_id;
            $captchaFirst = random_int(1, 9);
            $captchaSecond = random_int(1, 9);
            session(["listing_message_captcha_{$data->id}" => $captchaFirst + $captchaSecond]);
            $data['messageCaptchaFirst'] = $captchaFirst;
            $data['messageCaptchaSecond'] = $captchaSecond;
            
            $this->recentViews($data->id, $data->user_id);
            ListingAnalytics::track($data, 'view');

            if (auth()->user()) {
                $data['recentViews'] = RecentViewsListing::whereNotIn('listing_id', [$data->id])->whereUserId(auth()->id())->orderBy('id', 'desc')->limit(4)->get();
            }
            $data['relatedListings'] = Listing::whereNotIn('id', [$data->id])->inrandomorder()->where('category_id', $data->category_id)->whereStatus(1)->limit(8)->get();
            return view('frontend.details', $data);
        
    }

    public function recentViews($id, $userId)
    {
        $agent = new Agent();
        $data = new RecentViewsListing();
        $data->user_id    = auth()->id();
        $data->listing_owner_id  = $userId;
        $data->listing_id = $id;
        $data->device = $agent->device();
        $data->browser = $agent->browser();
        $data->operating_system = $agent->platform();
        $data->save();
        return;
    }

    public function authorDetails($username)
    {
        if (isset($username) && !empty($username)) {
            if ($username == 'admin') {
                $data['listings'] = Listing::where('admin_id', NULL)->where('user_id', NULL)->orderBy('is_feature', 'desc')->paginate(30);
                $data['admin'] = Admin::first();
            } else {
                $data['user'] = User::whereUsername($username)->first();
                if (isset($data['user']->id) && !empty($data['user']->id)) {
                    $data['listings'] = Listing::whereUserId($data['user']->id)->whereStatus(1)->orderBy('is_feature', 'desc')->paginate(30);
                } else {
                    return view('errors.404');
                }
            }
        } else {
            return view('errors.404');
        }


        return view('frontend.author_details', $data);
    }

    public function listingEnquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'listing_id' => 'required|exists:listings,id',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:100',
            'details' => 'required|string|max:2000',
            'captcha_answer' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('open_listing_message_modal', true);
        }

        $captchaKey = "listing_message_captcha_{$request->listing_id}";
        if ((int) $request->captcha_answer !== (int) $request->session()->get($captchaKey)) {
            return back()->withErrors(['captcha_answer' => __('Captcha answer is incorrect.')])->withInput()->with('open_listing_message_modal', true);
        }

        if (auth()->check() && Listing::whereId($request->listing_id)->whereUserId(auth()->id())->exists()) {
            return back()->with('warning', 'You are the owner of this directory!');
        }

        if (auth()->check() && ListingEnquiry::whereListingId($request->listing_id)->whereUserId(auth()->id())->exists()) {
            return back()->with('warning', 'You have already submitted message for this directory!');
        }

        $listing = Listing::with('user')->whereId($request->listing_id)->firstOrFail();

        $data = new ListingEnquiry();
        $data->user_id = auth()->id();
        $data->listing_owner_id = $listing->user_id;
        $data->listing_id = $request->listing_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->details = $request->details;
        $data->save();
        ListingAnalytics::track($listing, 'message', $request);
        $request->session()->forget($captchaKey);

        $recipient = $listing->email ?: optional($listing->user)->email;
        if (!$recipient && function_exists('getAdmin')) {
            $recipient = optional(getAdmin())->email;
        }

        if ($recipient) {
            try {
                $body = '<p>You have new message. Please go to bdtalika.com and check customer message.</p>'
                    . '<div class="btn-wrap"><a class="anchor-btn" href="' . route('user.customer.enquiry') . '">Check Customer Message</a></div>';

                (new GeniusMailer())->sendCustomMail([
                    'to' => $recipient,
                    'subject' => 'New customer message on BDTalika',
                    'body' => $body,
                ]);
            } catch (\Exception $e) {
                \Log::error('Listing enquiry mail failed: ' . $e->getMessage());
            }
        }

        return back()->with('message', 'Thank you for contacting us! We will get back to you soon');
    }

    public function trackAnalytics(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'event_type' => 'required|in:phone,whatsapp,website,email',
        ]);

        $listing = Listing::findOrFail($request->listing_id);
        ListingAnalytics::track($listing, $request->event_type, $request);

        return response()->json(['success' => true]);
    }

    public function trackImpressions(Request $request)
    {
        // Batched listing-card impressions from directory/search/homepage grids
        $request->validate([
            'ids'   => 'required|array|max:100',
            'ids.*' => 'integer|exists:listings,id',
        ]);

        $ids = array_values(array_unique(array_map('intval', $request->ids)));

        $listings = Listing::whereIn('id', $ids)->where('status', 1)->get(['id', 'user_id']);

        foreach ($listings as $listing) {
            ListingAnalytics::track($listing, 'impression', $request);
        }

        return response()->json(['success' => true]);
    }

    public function wishlist(Request $request)
    {
        if (auth()->user() == NULL) {
            return response()->json(['error' => 'Please login first!']);
        }

        if (Wishlists::whereUserId(auth()->id())->whereListingId($request->listing_id)->exists()) {
            $wishlist = Wishlists::whereUserId(auth()->id())->whereListingId($request->listing_id)->first();
            $wishlist->delete();
            return response()->json(['error' => 'Item remove from favourite list']);
        }

        $wishlist = new Wishlists();
        $wishlist->user_id = auth()->id();
        $wishlist->listing_id = $request->listing_id;
        $wishlist->save();

        return response()->json(['success' => 'Item added into your favourite list.']);
    }

    public function review(Request $request)
    {
        $request->validate([
            'stars' => 'required|numeric|between:1,5',
            'message' => 'required',
        ]);

        if (auth()->user() == NULL) {
            return back()->with('warning', 'Please login first!');
        }

        if (Listing::whereUserId(auth()->id())->whereId($request->listing_id)->exists()) {
            return back()->with('warning', 'You are not allow to give review own directory!');
        }

        if (ListingReview::whereUserId(auth()->id())->whereListingId($request->listing_id)->exists()) {
            return back()->with('warning', 'you have given review already!');
        }

        if (!User::whereId(auth()->id())->exists()) {
            return back()->with('warning', 'Please login first!');
        }
        $user = Auth::user();
        $listing            = Listing::whereId($request->listing_id)->first();
        $review             = new ListingReview();
        $review->listing_owner_id = $listing->user_id;
        $review->listing_id = $request->listing_id;
        $review->user_id    = $user->id;
        $review->name       = $user->name;
        $review->email      = $user->email;
        $review->message    = $request->message;
        $review->rate       = $request->stars;
        $review->save();
        return back()->with('message', 'Review received! Thanks for sharing your thoughts.');
    }

    public function follow(Request $request)
    {
        if (auth()->user() == NULL) {
            return response()->json(['error' => 'Please login first!']);
        }

        if ($request->has('listing_id')) {
            if (Listing::whereId($request->listing_id)->whereUserId(auth()->id())->exists()) {
                return response()->json(['error' => 'You can not follow yourself!']);
            }
        }

        if (Follower::whereFollowerId(auth()->id())->exists()) {
            return response()->json(['error' => 'You already follow this owner!!']);
        }

        $data           = new Follower();
        $data->user_id  = $request->has('owner_id') ? $request->owner_id : null;
        $data->follower_id = auth()->id();
        $data->save();
        return response()->json(['success' => 'Item added into your favourite list.']);
    }

    public function publicHoliday()
    {
        $today          = Carbon::today();
        $isHolidayToday = Holiday::where('date', $today)->exists();
        $nextHoliday    = Holiday::where('date', '>', $today)->orderBy('date')->first();
        $holidays       = Holiday::all();
        return view('frontend.public_holiday', compact('isHolidayToday', 'nextHoliday', 'today', 'holidays'));
    }

    public function advertising()
    {
        return view('frontend.advertising');
    }

    // public function typeListing($type){
    //     $type = DB::table('listing_types')->where('slug', $type)->first(); 
    //     $data['data'] = Listing::whereStatus(1)->where('type', $type->id)->get();
    //     $data['all_categories'] = Category::whereStatus(1)->orderBy('order_number', 'asc')->get();
    //     $data['divisions']      = Location::whereParentId(NUll)->whereStatus(1)->orderBy('order_number', 'asc')->get();
    //     return view('frontend.list', $data);
    // }

}
