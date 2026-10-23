<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wishlists;
use Carbon\Carbon;
use Illuminate\Support\Str;

use function GuzzleHttp\json_decode;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'category_id',
        'division_id',
        'city_id',
        'area_id',
        'total_review',
        'average_rating',
        'is_gmb',
        'name',
        'slug',
        'designation',
        'description',
        'location_id',
        'real_address',
        'map_location',
        'latitude',
        'longitude',
        'photo',
        'amenities',
        'amenity_icons',
        'distance',
        'schedules',
        'is_feature',
        'type',
        'plan_id',
        'plan_start_date',
        'plan_end_date',
        'status',
        'website',
        'email',
        'phone_number',
        'whatsapp_number',
        'facebook',
        'twitter',
        'linkedin',
        'youtube',
        'instagram',
        'listing_video_provider',
        'listing_video',
        'r_price',
        'r_bed',
        'r_baths',
        'r_square',
        'r_property_type',
        'car_price',
        'car_fuel_type',
        'car_manufacture_year',
        'car_engine_capacity',
        'car_mileage',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'package_reminder_sent_at',
    ];

    protected $dates = [
        'expire_date',
        'plan_start_date',
        'plan_end_date',
        'package_reminder_sent_at',
    ];

    protected $casts = [
        'expire_date' => 'datetime',
        'plan_start_date' => 'datetime',
        'plan_end_date' => 'datetime',
        'package_reminder_sent_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function listingType()
    {
        return $this->belongsTo(ListingType::class, 'type');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function latestWhatsappReminder()
    {
        return $this->hasOne(WhatsAppReminderLog::class, 'listing_id')
            ->where('type', 'package_activation')
            ->latestOfMany('created_at');
    }

    public function whatsAppMessages()
    {
        return $this->hasMany(WhatsAppMessage::class, 'listing_id');
    }

    public function latestCompletedBkashPayment()
    {
        return $this->hasOne(BkashPayment::class, 'listing_id')
            ->where('payment_status', 'completed')
            ->latestOfMany('paid_at');
    }

    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class, 'ad_id')
            ->where('status', 1)
            ->latestOfMany('created_at');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlists::class);
    }

    public function recentviews()
    {
        return $this->hasMany(RecentViewsListing::class);
    }

    public function analytics()
    {
        return $this->hasMany(ListingAnalytics::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function enquiries()
    {
        return $this->hasMany(ListingEnquiry::class);
    }

    public function reviews()
    {
        return $this->hasMany(ListingReview::class);
    }

    public function menus()
    {
        return $this->hasMany(ListingMenu::class);
    }

    public function services()
    {
        return $this->hasMany(ListingBeauty::class);
    }

    public function rooms()
    {
        return $this->hasMany(ListingRoom::class);
    }

    public function faqs()
    {
        return $this->hasMany(ListingFaq::class);
    }

    public function userFavourite($userId = NULL, $listingId)
    {
        if ($userId == NULL) {
            return false;
        }

        if (!Wishlists::whereUserId($userId)->whereListingId($listingId)->exists()) {
            return false;
        }

        return true;
    }

    public function directoryRatting($id)
    {
        $data = Listing::findOrFail($id);
        $totalReview = $data->reviews->count();
        $totalRate = $data->reviews->sum('rate');

        if ($totalRate != 0 &&  $totalReview != 0) {
            $rate = round($totalRate / $totalReview, 2);
        }
        return $rate;
    }

    public function directoryStar($id)
    {
        $rate = ceil($this->directoryRatting($id));
        $star = '';
        for ($i = 1; $i <= 5; $i++) {
            $star .= '<i class="fas fa-star active"></i>';
        }

        if ($restStart = 5 - $rate > 0) {
            for ($i = 1; $i <= $restStart; $i++) {
                $star .= '<i class="fas fa-star"></i>';
            }
        }

        return $star;
    }

    // public function openClose($id){
    //     $data = Listing::findOrFail($id);
    //     $schedules = json_decode($data->schedules, true);
    //     $day = Str::lower(now()->format('D'));


    //     if ($schedules !== null && array_key_exists($day.'_open', $schedules) && array_key_exists($day.'_close', $schedules)) {
    //         $open = $schedules[$day.'_open'];
    //         $close = $schedules[$day.'_close'];

    //         if ($open !== null && $open !=="closed" && $close !== null && $close !== "closed") {
    //             $open = Carbon::createFromFormat('h A', $open);
    //             $close = Carbon::createFromFormat('h A', $close);
    //             $now = Carbon::now();

    //             if ($now >= $open && $now <= $close) {
    //                 return 'open';
    //             } else {
    //                 return 'closed';
    //             }
    //         } else {
    //             return 'closed';
    //         }
    //     } else {
    //         return 'closed';
    //     }

    // }

    public function openClose($id)
    {
        $data           = Listing::findOrFail($id);
        // $schedules   = json_decode($data->schedules, true);
        $schedules      = $data->schedules;
        $day            = Str::lower(now()->format('D'));
        // Validation
        if (!is_array($schedules) || !isset($schedules[$day . '_open'], $schedules[$day . '_close'])) {
            return 'closed';
        }
        $open = $schedules[$day . '_open'];
        $close = $schedules[$day . '_close'];
        // Check for 'closed' string
        if ($open === 'closed' || $close === 'closed') {
            return 'closed';
        }
        // Time format with minutes, considering 'pm' and 'am'
        $open = Carbon::createFromFormat('h a', $open);
        $close = Carbon::createFromFormat('h a', $close);
        $now = Carbon::now();

        if ($now->isBetween($open, $close, true)) {
            return 'open';
        }
        return 'closed';
    }

    public function division()
    {
        return $this->belongsTo(Location::class, 'division_id');
    }

    public function city()
    {
        return $this->belongsTo(Location::class, 'city_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function leadFollowup()
    {
        return $this->hasOne(LeadFollowup::class, 'listing_id');
    }

    
}
