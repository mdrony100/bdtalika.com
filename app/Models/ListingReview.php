<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'name',
        'rate',
        'message',
        'user_photo',
        'reviewed_at'
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function listing(){
        return $this->belongsTo(Listing::class);
    }

    public static function agentRatings($user_id)
    {
        $stars = PropertyReview::when('property_owner_id', function($query) use ($user_id) {
            $query->where('property_owner_id', '=', $user_id);
        })->avg('rate');

        return round($stars,2);
    }

    public static function agentRatingCount($user_id)
    {
        $stars = PropertyReview::when('property_owner_id', function($query) use ($user_id) {
            $query->where('property_owner_id', '=', $user_id);
        })->count();

        return $stars;
    }
}
