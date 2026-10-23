<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListingType extends Model
{
    use HasFactory;
    protected $table = 'listing_types';

    public function listings()
    {
        return $this->hasMany(Listing::class, 'type');
    }
}
