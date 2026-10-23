<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $dates = [
        'checkin_date',
        'checkout_date',
        'created_at',
        'updated_at'
    ];

    public function listing(){
        return $this->belongsTo(Listing::class);
    }

    public function service(){
        return $this->belongsTo(ListingBeauty::class,'service_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function conversations(){
        return $this->hasMany(BookingConversation::class);
    }
}
