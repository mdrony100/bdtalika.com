<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingEnquiry extends Model
{
    use HasFactory;

    public function listing(){
        return $this->belongsTo(Listing::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function owner(){
        return $this->belongsTo(User::class,'listing_owner_id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'listing_owner_id');
    }
}
