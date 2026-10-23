<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Plan;
use App\Models\Amenity;
use App\Models\Country;
use App\Models\Gallery;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Location;
use App\Models\Wishlists;
use App\Models\ListingFaq;
use App\Models\ListingMenu;
use App\Models\ListingRoom;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ListingBeauty;
use App\Services\AdminNotificationService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ListingRequest;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class ListingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function type()
    {
        $data['listings'] = Listing::whereUserId(auth()->id())->count();
        return view('user.listing.type', $data);
    }

    public function index()
    { 
        $listings = Listing::with(['plan', 'reviews'])->whereUserId(auth()->id())->orderBy('id', 'desc')->paginate(7);
        return view('user.listing.index', compact('listings'));
    }


    public function typeGetCategory($id)
    {
        $categories = Category::whereStatus(1)->where('type_id', $id)->orderBy('order_number', 'asc')->get();
        return response()->json($categories);
    }

    public function create()
    {
        // remove listing ad id 
        session()->forget('ad_id');
        $data['divisions']      = Location::whereParentId(NULL)->whereStatus(1)->orderBy('id', 'desc')->get();
        $data['listing_types']  = DB::table('listing_types')->where('status', 'active')->latest()->get();
        return view('user.listing.ad_step', $data);
    }


    public function adSetp2()
    {
        $ad_id                  = Session::get('ad_id');
        $category_id            = Listing::where('id', $ad_id)->pluck('category_id')->first();
        if ($category_id) {
            $category           = Category::where('id', $category_id)->pluck('slug')->first();
        }
        $data['amenities'] = Amenity::whereStatus(1)->orderBy('id', 'desc')->get();
        return view('user.listing.ad_step2', compact('category'), $data);
    }

    public function adSetp3()
    {
        $ad_id                  = Session::get('ad_id');
        $category_id            = Listing::where('id', $ad_id)->pluck('category_id')->first();
        if ($category_id) {
            $category           = Category::where('id', $category_id)->pluck('slug')->first();
        }
        return view('user.listing.ad_step3', compact('category'));
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'type'          => 'required',
            'name'          => 'required|max:70|unique:listings,name',
            'category_id'   => 'required',
            'division_id'   => 'required',
            'city_id'       => 'required',
            'description'   => 'max:20000',
            'real_address'  => 'max:300',
            'listing_video' => 'max:255',
            'phone_number'  => 'max:100',
            'whatsapp_number'  => 'max:100',
            'facebook'      => 'max:100',
            'twitter'       => 'max:100',
            'linkedin'      => 'max:100',
            'youtube'       => 'max:255',
            'website'       => 'max:100',
            'email'         => 'max:100',
            'instagram'     => 'max:255',
            // 'area_id'       => 'required',
            // 'photo'         => 'required',
        ]);

        $data               = new Listing();
        $data->name         = $request->name;
        $slug               = Str::slug($request->name);
        $data->user_id      = auth()->id();
        $data->status       = 0;
        $data->type         = $request->type;
        $slug_exist         = Listing::where('slug', $slug)->first();
        if ($slug_exist) {
            $timestamp      = now()->timestamp;
            $data->slug     = $slug . '-' . $timestamp;
        } else {
            $data->slug     = $slug;
        }
        $data->category_id      = $request->category_id;
        $data->division_id      = $request->division_id;
        $data->city_id          = $request->city_id;
        $data->area_id          = $request->area_id ?? null;
        $data->description      = $request->description;
        $data->real_address     = $request->real_address;
        $data->listing_video    = $request->listing_video;
        $data->website          = $request->website;
        $data->email            = $request->email;
        $data->phone_number     = $request->phone_number;
        $data->whatsapp_number  = $request->whatsapp_number;
        $data->facebook         = $request->facebook;
        $data->twitter          = $request->twitter;
        $data->linkedin         = $request->linkedin;
        $data->youtube          = $request->youtube;
        $data->instagram        = $request->instagram;
        $data->map_location     = $request->map_location;

        if ($request->hasFile('photo')) {
            $status = ExtensionValidation($request->file('photo'));
            if (!$status) {
                return ['errors' => [0 => 'file format not supported']];
            }
            $data->photo = processImage($request->file('photo'));
        }
        $data->save();
        app(AdminNotificationService::class)->newListingCreated($data);

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

        $request->session()->put('ad_id', $data->id);
        session()->put('stepOne', true);
        return redirect()->route('user.listing.create.step2');
    }


    public function storeStep2(Request $request)
    {

        $id     = $request->session()->get('ad_id');
        $data   = Listing::findOrFail($id);

        $amenities = [];
        if ($request->amenities) {
            foreach ($request->amenities as $key => $amenity) {
                array_push($amenities, $amenity[0]);
            }
        }
        $data->amenities       = json_encode($amenities, true);

        $schedule['sat_open']  = $request->has('saturday_opening') ? $request->saturday_opening : NULL;
        $schedule['sat_close'] = $request->has('saturday_closing') ? $request->saturday_closing : NULL;
        $schedule['sun_open']  = $request->has('sunday_opening') ? $request->sunday_opening : NULL;
        $schedule['sun_close'] = $request->has('sunday_closing') ? $request->sunday_closing : NULL;
        $schedule['mon_open']  = $request->has('monday_opening') ? $request->monday_opening : NULL;
        $schedule['mon_close'] = $request->has('monday_closing') ? $request->monday_closing : NULL;
        $schedule['tue_open']  = $request->has('tuesday_opening') ? $request->tuesday_opening : NULL;
        $schedule['tue_close'] = $request->has('tuesday_closing') ? $request->tuesday_closing : NULL;
        $schedule['wed_open']  = $request->has('wednesday_opening') ? $request->wednesday_opening : NULL;
        $schedule['wed_close'] = $request->has('wednesday_closing') ? $request->wednesday_closing : NULL;
        $schedule['thu_open']  = $request->has('thursday_opening') ? $request->thursday_opening : NULL;
        $schedule['thu_close'] = $request->has('thursday_closing') ? $request->thursday_closing : NULL;
        $schedule['fri_open']  = $request->has('friday_opening') ? $request->friday_opening : NULL;
        $schedule['fri_close'] = $request->has('friday_closing') ? $request->friday_closing : NULL;

        $data->schedules       = json_encode($schedule, true);
        $data->save();

        session()->put('stepTwo', true);
        return redirect()->route('user.listing.create.step3');
    }

    public function storeStep3(Request $request)
    {
        $id         = $request->session()->get('ad_id');
        $data       = Listing::findOrFail($id);
        $input      = $request->all();
        $data->fill($input)->save();

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
                if ($name !== null) {
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

        $data->save();
        // session()->forget('ad_id');
        session()->forget('stepOne');
        session()->forget('stepTwo');
        Session::flash('message', "New Data Added Successfully");
        // return redirect()->route('user.listing.index');
        return redirect()->route('user.listing.plan');
    }

    public function editStep1($id)
    {
        $data['data']        = Listing::findOrFail($id);
        $data['categories']  = Category::whereStatus(1)->orderBy('order_number', 'asc')->get();
        $data['divisions']   = Location::whereParentId(NULL)->whereStatus(1)->orderBy('id', 'desc')->get();
        $data['cities']      = Location::where('parent_id', $data['data']->division_id)->whereStatus(1)->get();
        $data['areas']       = Area::where('city_id', $data['data']->city_id)->whereStatus(1)->get();
        $data['galleries']   = Gallery::where('listing_id', $data['data']->id)->get();
        $data['listing_types']  = DB::table('listing_types')->where('status', 'active')->latest()->get();
        return view('user.listing.ad_edit', $data);
    }

    public function editStep2($id)
    {
        $data['data']        = Listing::findOrFail($id);
        $data['amenities']   = Amenity::whereStatus(1)->orderBy('id', 'desc')->get();
        $data['listingAmenities'] = $data['data']->amenities != NULL ? json_decode($data['data']->amenities, true) : [];
        $data['listingSchedules'] = $data['data']->schedules != NULL ? json_decode($data['data']->schedules, true) : [];
        $category_id    =  $data['data']->category_id;
        if ($category_id) {
            $data['category']   = Category::where('id', $category_id)->pluck('slug')->first();
        }
        return view('user.listing.ad_edit_step2', $data);
    }

    public function editStep3($id)
    {
        $data['data']        = Listing::findOrFail($id);
        $category_id    = $data['data']->category_id;
        if ($category_id) {
            $data['category']   = Category::where('id', $category_id)->pluck('slug')->first();
        }
        $category        =  Category::where('id', $data['data']->category_id)->first();
        if ($category->slug == 'restaurants') {
            $data['menus'] = ListingMenu::whereListingId($data['data']->id)->get();
        }
        if ($category->slug == 'hotels') {
            $data['rooms'] = ListingRoom::whereListingId($data['data']->id)->get();
        }
        if (
            $category->slug == 'beauty-spa' ||
            $category->slug == 'universities' ||
            $category->slug == 'lawyers' ||
            $category->slug == 'ac-repair-services'
        ) {
            $data['beauties'] = ListingBeauty::whereListingId($data['data']->id)->get();
        }
        $data['faqs'] = ListingFaq::whereListingId($data['data']->id)->get();
        return view('user.listing.ad_edit_step3', $data);
    }

    public function updateStep1(Request $request, $id)
    {
        // remove listing ad id 
        session()->forget('ad_id');

        $request->validate([
            'type'          => 'required',
            'name'          => 'required|max:70|unique:listings,name,' . $id,
            'category_id'   => 'required',
            'division_id'   => 'required',
            'city_id'       => 'required',
            'description'   => 'max:20000',
            'real_address'  => 'max:300',
            'listing_video' => 'max:255',
            'phone_number'  => 'max:100',
            'whatsapp_number'  => 'max:100',
            'facebook'      => 'max:100',
            'twitter'       => 'max:100',
            'linkedin'      => 'max:100',
            'youtube'       => 'max:255',
            'website'       => 'max:100',
            'email'         => 'max:100',
            'instagram'     => 'max:255',
            // 'area_id'    => 'required',
        ]);

        $data = Listing::find($id);
        if ($data->status == '2') {
            if(empty($data->plan_id)){
                $data->status = 0;
            }else{
                $data->status = 3;
            }
        }
        $data->name = $request->name;
        $data->user_id = auth()->id();
        $data->type             = $request->type;
        $data->category_id      = $request->category_id;
        $data->division_id      = $request->division_id;
        $data->city_id          = $request->city_id;
        $data->area_id          = $request->area_id ?? null;
        $data->description      = $request->description;
        $data->real_address     = $request->real_address;
        $data->listing_video    = $request->listing_video;
        $data->website          = $request->website;
        $data->email            = $request->email;
        $data->phone_number     = $request->phone_number;
        $data->whatsapp_number     = $request->whatsapp_number;
        $data->facebook         = $request->facebook;
        $data->twitter          = $request->twitter;
        $data->linkedin         = $request->linkedin;
        $data->youtube          = $request->youtube;
        $data->instagram        = $request->instagram;
        $data->map_location     = $request->map_location;
        $data->updated_at       = Carbon::now() ;



        if ($request->hasFile('photo')) {
            $status = ExtensionValidation($request->file('photo'));
            if (!$status) {
                return ['errors' => [0 => 'file format not supported']];
            }
            // old file delete
            $imagePath = 'assets/images/' . $data->photo;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $data->photo = processImage($request->file('photo'));
        }

        $data->update();
        // gallery    
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
        Session::flash('message', "Data Updated Successfully");
        if ($request->cancel_edit == 1) {
            return redirect()->route('user.listing.index');
        } else {
            return redirect()->route('user.listing.edit.step2', $data->id);
        }
    }


    public function updateStep2(Request $request, $id)
    {
        $data = Listing::findOrFail($id);
        $amenities = [];
        if ($request->amenities) {
            foreach ($request->amenities as $key => $amenity) {
                array_push($amenities, $amenity[0]);
            }
        }
        $data->amenities       = json_encode($amenities, true);

        $schedule['sat_open']   = $request->has('saturday_opening')  ? $request->saturday_opening : NULL;
        $schedule['sat_close']  = $request->has('saturday_closing')  ? $request->saturday_closing : NULL;
        $schedule['sun_open']   = $request->has('sunday_opening')  ? $request->sunday_opening : NULL;
        $schedule['sun_close']  = $request->has('sunday_closing')  ? $request->sunday_closing : NULL;
        $schedule['mon_open']   = $request->has('monday_opening')  ? $request->monday_opening : NULL;
        $schedule['mon_close']  = $request->has('monday_closing')  ? $request->monday_closing : NULL;
        $schedule['tue_open']   = $request->has('tuesday_opening')  ? $request->tuesday_opening : NULL;
        $schedule['tue_close']  = $request->has('tuesday_closing')  ? $request->tuesday_closing : NULL;
        $schedule['wed_open']   = $request->has('wednesday_opening')  ? $request->wednesday_opening : NULL;
        $schedule['wed_close']  = $request->has('wednesday_closing')  ? $request->wednesday_closing : NULL;
        $schedule['thu_open']   = $request->has('thursday_opening')  ? $request->thursday_opening : NULL;
        $schedule['thu_close']  = $request->has('thursday_closing')  ? $request->thursday_closing : NULL;
        $schedule['fri_open']   = $request->has('friday_opening')  ? $request->friday_opening : NULL;
        $schedule['fri_close']  = $request->has('friday_closing')  ? $request->friday_closing : NULL;
        $data->schedules        = json_encode($schedule, true);

        $data->update();

        Session::flash('message', "Data Updated Successfully");
        if ($request->cancel_edit == 1) {
            return redirect()->route('user.listing.index');
        } else {
            return redirect()->route('user.listing.edit.step3', $data->id);
        }
    }

    public function updateStep3(Request $request, $id)
    {
        $data   = Listing::findOrFail($id);
        $input  = $request->all();

        if ($request->menu_title !== NULL) {
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

        if ($request->room_name) {
            foreach ($request->room_name as $key => $name) {
                if (isset($request->room_id[$key])) {
                    $room               = ListingRoom::findOrFail($request->room_id[$key]);
                    $room->listing_id   = $data->id;
                    $room->room_name    = $name;
                    if (isset($request->room_price[$key])) {
                        $room->room_price = $request->room_price[$key];
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
                    $room               = new ListingRoom();
                    $room->listing_id   = $data->id;
                    $room->room_name    = $name;
                    if (isset($request->room_price[$key])) {
                        $room->room_price = $request->room_price[$key];
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

        if ($request->faq_name) {
            $existingFaqIds = is_array($request->faq_id) ? array_filter($request->faq_id) : [];
            ListingFaq::where('listing_id', $data->id)
                ->whereNotIn('id', $existingFaqIds)
                ->delete();
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
        $data->update($input);
        $data->update();
        Session::flash('message', "Data Updated Successfully");

        $plan = Plan::where('id', $data->plan_id)->first();
        if (isset($plan->plan_type) && $plan->plan_type == 'free'){
            session()->put('ad_id', $data->id);
            return redirect()->route('user.listing.plan');
        }else{
            return redirect()->route('user.listing.index');
        }
    }


    // public function ListingPlan($ad_id = null)
    // {
    //     if(!empty($ad_id)){
    //         session()->put('ad_id', $ad_id);
    //     }
    //     $plans      = Plan::where('status', 1)->orderBy('id', 'desc')->get();
    //     $lifeTimeplan = Plan::where('status', 1)
    //                     ->where('plan_type', 'lifetime')
    //                     ->where('regular_price', '>', 0)
    //                     ->first();
    //     $listing    = Listing::where('id', session()->get('ad_id'))->select('plan_id', 'status')->first();
    //     return view('user.listing.plan', compact('plans', 'listing', 'lifeTimeplan'));
    // }

    public function ListingPlan($ad_id = null){
        if(!empty($ad_id)){
            session()->put('ad_id', $ad_id);
        }

        $plans = Plan::where('status', 1)
            ->where('is_private', 0)
            ->orderBy('sorting', 'desc')
            ->get();

        $lifeTimeplan = Plan::where('status', 1)
            ->where('is_private', 0)
            ->where('plan_type', 'lifetime')
            ->where('regular_price', '>', 0)
            ->first();

        $listing = Listing::where('id', session()->get('ad_id'))
            ->select('plan_id', 'status')
            ->first();

        // NEW CODE
        $notifications = Listing::join('users', 'listings.user_id', '=', 'users.id')
            ->join('plans', 'listings.plan_id', '=', 'plans.id')
            ->whereIn('plans.id', [1, 2])
            ->whereNotNull('listings.slug')
            ->where('listings.status', 1)
            ->select(
                'users.name',
                'users.photo',
                'plans.title as plan_name',
                'plans.plan_type',
                'listings.slug'
            )
            ->inRandomOrder()
            ->limit(50)
            ->get();

        return view('user.listing.plan', compact(
            'plans',
            'listing',
            'lifeTimeplan',
            'notifications'
        ));
    }

    public function ListingPlanCheckout(Request $request, $id)
    {
        
        // $id & $request->id means new plan request id
        $ad_id      = session()->get('ad_id');
        $listing    = Listing::where('id', $ad_id)->first();
        $plan       = Plan::where('id', $request->id)->first();
        if (isset($listing->plan_id) && $listing->plan_id === $plan->id) {
            Session::flash('error', "The plan is already active for this listing.");
            session()->forget('ad_id');
            return redirect()->route('user.listing.index');
        } else { 
            // for free plan
            if ($plan->plan_type == 'free') {
                // currently we are not accepting free listing
                Session::flash('error', "Sorry !! Currently we are not accepting free listing. You have to take paid plan");
                return redirect()->route('user.listing.plan');

                //$plan_duration = null;
                $today = Carbon::now();
                //$end_date = $today->addDays($plan_duration - 1);
                Listing::where('id', $ad_id)->update([
                    'plan_id'         => $plan->id,
                    'plan_start_date' => Carbon::now(),
                    'plan_end_date'   => null,
                    'status'    => 3
                ]);
                session()->forget('ad_id');
                Session::flash('message', "Review is required. We will notify you soon.");
                return redirect()->route('user.listing.index');
            } else {
                // go for premium plan
                return view('user.listing.checkout', compact('plan'));
            }
        }
    }

    public function bkashPayment(Request $request, $id)
    {
        $request->validate([
            'phone'         => 'required',
            'txn_number'    => 'required'
        ],[
            'txn_number.required' => 'The transaction field is required'
        ]);

        try {
            $ad_id          = session()->get('ad_id');
            $listing        = Listing::where('id', $ad_id)->first();
            $plan           = Plan::where('id', $id)->first();
            $today          = Carbon::now();
            $plan_duration   = $plan->post_duration;
            if($plan->plan_type == 'free' || $plan->plan_type == 'lifetime'){
                $end_date   = null;
            }else{
                $end_date   = $today->addDays($plan_duration - 1);
            }
            Listing::where('id', $ad_id)->update([
                'plan_id'         => $plan->id,
                'plan_start_date' => Carbon::now(),
                'plan_end_date' => $end_date,
                'status'    => 3
            ]);

            // payment 
            $data           = new Transaction;
            $data->user_id  = Auth::id();
            $data->plan_id  = $id;
            $data->ad_id    = $ad_id;
            $data->email    = Auth::user()->email;
            $data->phone    = $request->phone;
            $data->amount   = $request->amount;
            $data->type     = $plan->plan_type;
            $data->plan_duration = $plan->post_duration ?? 'lifetime';
            $data->plan_name     = $plan->title;
            $data->txnid         = $request->txn_number;
            $data->save();
            session()->forget('ad_id');
            Session::flash('message', "Review is required. We will notify you soon.");
            return redirect()->route('user.listing.index');
        } catch (\Exception $e) {  
            Session::flash('error', "Someting wrong, Please try later.");
            return redirect()->back();
        }
    }


    public function store(ListingRequest $request)
    {

        $user = auth()->user();
        // if (now() > $user->plan_end_date) {
        //     return ['errors' => [0 => 'Plan date expired, Please buy a new plan.']];
        // }

        $plan = Plan::findOrFail($user->plan_id);
        if (!$plan) {
            return ['errors' => [0 => 'Please buy a new plan.']];
        }

        if ($user->ad_limit < Listing::whereUserId(auth()->id())->count()) {
            return ['errors' => [0 => 'Please buy a new plan.']];
        }

        $data = new Listing();
        $input = $request->all();

        if (isset($input['photo']) && $input['photo']) {
            $status = ExtensionValidation($input['photo']);
            if (!$status) {
                return ['errors' => [0 => 'file format not supported']];
            }
            $image = $request->file('photo');
            $name = time() . '.webp';
            $imagePath = 'assets/images/' . $name;
            Image::make($image)->save($imagePath);
            $watermark = 'assets/images/watermark.png';
            $watermarkedImage = Image::make($imagePath)->insert($watermark, 'bottom-right', 10, 10)->save($imagePath);
            $input['photo'] = $name;
        }

        $input['slug'] = Str::slug($request->slug);
        $amenities = [];

        if ($request->amenities) {
            foreach ($request->amenities as $key => $amenity) {
                array_push($amenities, $amenity[0]);
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
        $input['schedules'] = json_encode($schedule, true);

        $input['user_id'] = auth()->id();
        $input['status'] = 0;
        $data->fill($input)->save();

        $user->ad_limit = $user->ad_limit - 1;
        $user->update();

        // if ($files = $request->file('gallery')) {
        //     foreach ($files as  $key => $file) {
        //         $gallery = new Gallery();
        //         if ($file) {
        //             $status = ExtensionValidation($file);
        //             if (!$status) {
        //                 return ['errors' => [0 => 'file format not supported']];
        //             }
        //             $gallery['photo'] = processImage($file);
        //         }
        //         $gallery['listing_id'] = $data->id;
        //         $gallery->save();
        //     }
        // }

        if ($request->hasFile('gallery')) {
            $images = $request->file('gallery');
            foreach ($images as $img) {
                $status = ExtensionValidation($img);
                if (!$status) {
                    return ['errors' => [0 => 'file format not supported']];
                }
                $make_name = hexdec(uniqid()) . '.webp';
                $webpPath = 'assets/images/' . $make_name;
                Image::make($img)->save($webpPath, 80, 'webp');

                $watermark = 'assets/images/watermark.png';
                Image::make($webpPath)->insert($watermark, 'bottom-right', 10, 10)->save($webpPath);

                Gallery::insert([
                    'listing_id' => $data->id,
                    'photo'      => $make_name
                ]);
            }
        }



        if ($request->menu_title) {
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

        if ($request->room_name) {
            foreach ($request->room_name as $key => $name) {
                $room = new ListingRoom();
                $room->listing_id = $data->id;
                $room->room_name = $name;
                if (isset($request->room_price[$key])) {
                    $room->room_price = $request->room_price[$key];
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

        if ($request->service_name) {
            foreach ($request->service_name as $key => $name) {
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


        if ($request->faq_name) {
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

        $msg = __('New Data Added Successfully.') . ' ' . '<a href="' . route('user.listing.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data['data'] = Listing::findOrFail($id);
        $data['listingAmenities'] = $data['data']->amenities != NULL ? json_decode($data['data']->amenities, true) : [];
        $data['listingSchedules'] = $data['data']->schedules != NULL ? json_decode($data['data']->schedules, true) : [];

        if ($data['data']->type == 'restaurants') {
            $data['menus'] = ListingMenu::whereListingId($data['data']->id)->get();
        }

        if ($data['data']->type == 'hotels') {
            $data['rooms'] = ListingRoom::whereListingId($data['data']->id)->get();
        }

        if (
            $data['data']->type == 'beauty-spa' ||
            $data['data']->type == 'universities' ||
            $data['data']->type == 'lawyers' ||
            $data['data']->type == 'ac-repair-services'
        ) {
            $data['beauties'] = ListingBeauty::whereListingId($data['data']->id)->get();
        }

        $data['faqs'] = ListingFaq::whereListingId($data['data']->id)->get();
        $data['categories'] = Category::whereStatus(1)->orderBy('order_number', 'asc')->get();
        $data['divisions'] = Location::whereParentId(NULL)->whereStatus(1)->orderBy('id', 'desc')->get();
        if ($data['data']->division_id !== null) {
            $data['cities'] = Location::where('parent_id', $data['data']->division_id)->get();
        }
        $data['amenities'] = Amenity::whereStatus(1)->orderBy('id', 'desc')->get();
        $data['countries'] = Country::whereStatus(1)->orderBy('id', 'desc')->get();
        return view('user.listing.edit', $data);
    }

    public function update(ListingRequest $request, $id)
    {
        $data = Listing::findOrFail($id);
        $input = $request->all();

        if (isset($input['photo']) && $input['photo']) {
            $status = ExtensionValidation($input['photo']);
            if (!$status) {
                return ['errors' => [0 => 'file format not supported']];
            }
            $imagePath = 'assets/images/' . $data['photo'];
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $image = $request->file('photo');
            $name = time() . '.webp';
            $imagePath = 'assets/images/' . $name;
            Image::make($image)->save($imagePath);
            $watermark = 'assets/images/watermark.png';
            $watermarkedImage = Image::make($imagePath)->insert($watermark, 'bottom-right', 10, 10)->save($imagePath);
            $input['photo'] = $name;
        }

        $input['slug'] = Str::slug($request->slug);

        $amenities = [];

        if ($request->amenities) {
            foreach ($request->amenities as $key => $amenity) {
                array_push($amenities, $amenity[0]);
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
        $input['schedules'] = json_encode($schedule, true);

        $data->update($input);

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

        if ($request->room_name) {
            foreach ($request->room_name as $key => $name) {
                if (isset($request->room_id[$key])) {
                    $room = ListingRoom::findOrFail($request->room_id[$key]);
                    $room->listing_id = $data->id;
                    $room->room_name = $name;
                    if (isset($request->room_price[$key])) {
                        $room->room_price = $request->room_price[$key];
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

        if ($request->faq_name) {
            foreach ($request->faq_name as $key => $title) {
                if (isset($request->faq_id[$key])) {
                    $faq = ListingFaq::findOrFail($request->faq_id[$key]);
                    $faq->listing_id = $data->id;
                    $faq->faq_name = $title;
                    if (isset($request->faq_details[$key])) {
                        $faq->faq_details = $request->faq_details[$key];
                    }

                    $faq->save();
                } else {
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
        }

        $msg = __('Data Updated Successfully.') . ' ' . '<a href="' . route('admin.listing.index') . '"> ' . __('View Lists.') . '</a>';
        return response()->json($msg);
    }


    public function saved_listing()
    {
        $data['wishlists'] = Wishlists::whereUserId(auth()->id())->paginate(6);

        return view('user.listing.saved', $data);
    }

    public function savedDelete($id)
    {
        Wishlists::findOrFail($id)->delete();
        return back()->with('message', 'Directory removed successfully');
    }

    public function delete($id)
    {
        session()->forget('ad_id');
        session()->forget('stepOne');
        session()->forget('stepTwo');
        $data = Listing::findOrFail($id);

        // gallery image delete
        $galleries = Gallery::where('listing_id', $id)->get();
        if ($galleries->isNotEmpty()) {
            foreach ($galleries as $gallery) {
                if (File::exists('assets/images/' . $gallery->photo)) {
                    File::delete('assets/images/' . $gallery->photo);
                }
                $gallery->delete();
            }
        }

        // menus delete
        $menus = ListingMenu::where('listing_id', $id)->get();
        if ($menus->isNotEmpty()) {
            foreach ($data->menus as $menu) {
                if (File::exists('assets/images/' . $menu->menu_photo)) {
                    File::delete('assets/images/' . $menu->menu_photo);
                }
                $menu->delete();
            }
        }

        // servies
        $services = ListingBeauty::where('listing_id', $id)->get();
        if ($services->isNotEmpty()) {
            foreach ($data->services as $service) {
                if (File::exists('assets/images/' . $service->service_photo)) {
                    File::delete('assets/images/' . $service->service_photo);
                }
                $service->delete();
            }
        }
        // rooms
        $rooms = ListingRoom::where('listing_id', $id)->get();
        if ($rooms->isNotEmpty()) {
            foreach ($data->rooms as $room) {
                if (File::exists('assets/images/' . $room->room_photo)) {
                    File::delete('assets/images/' . $room->room_photo);
                }
                $room->delete();
            }
        }
        // faq
        $faq = ListingFaq::where('listing_id', $id)->get();
        if (isset($faq)) {
            foreach ($data->faqs as $faq) {
                $faq->delete();
            }
        }

        // listing photo
        if (File::exists('assets/images/' . $data->photo)) {
            File::delete('assets/images/' . $data->photo);
        }

        $data->delete();
        return back()->with('message', 'Data deleted successfully');
    }

    public function DivisionGetCity($id)
    {
        $city = Location::where('status', 1)->where('parent_id', $id)->get();
        return response()->json($city);
    }

    public function CityGetArea($id)
    {
        $city = Area::where('status', 1)->where('city_id', $id)->get();
        return response()->json($city);
    }

    public function deleteFaq(Request $request, $id)
    {
        ListingFaq::where('id', $request->id)->delete();
        return back()->with('message', 'Faq deleted successfully');
    }

    public function menuDestroy($id)
    {
        $data = ListingMenu::findOrFail($id);
        @unlink('assets/images/' . $data->photo);
        $data->delete();
        return back()->with('message', 'Menu deleted successfully');
    }

    public function beautyDestroy($id)
    {
        $data = ListingBeauty::findOrFail($id);
        @unlink('assets/images/' . $data->photo);
        $data->delete();
        return back()->with('message', 'Service deleted successfully');
    }

    public function roomDestroy($id)
    {
        $data = ListingRoom::findOrFail($id);
        @unlink('assets/images/' . $data->photo);
        $data->delete();
        return back()->with('message', 'Hotel Room deleted successfully');
    }

    public function galleryPhotoDelete($id)
    {
        $gallery = Gallery::where('id', $id)->first();
        if (File::exists('assets/images/' . $gallery->photo)) {
            File::delete('assets/images/' . $gallery->photo);
        }
        $gallery->delete();
        return back()->with('message', 'Photo deleted successfully');
    }

    public function rejectReason($id)
    {
        $data = Listing::where('id', $id)->value('reject_reason');
        return response()->json(['reject_reason' => $data]);
    }
}
