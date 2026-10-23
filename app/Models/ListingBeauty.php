<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingBeauty extends Model
{
    use HasFactory;

    public function listing(){
        return $this->belongsTo(Listing::class);
    }

    public function bookings(){
        return $this->hasMany(Booking::class);
    }
}
