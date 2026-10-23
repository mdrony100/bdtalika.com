<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $fillable = [
        'division_id',
        'city_id',
        'order_number',
        'title',
        'status',
        'photo',
        'meta_title',
        'meta_description',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function division()
    {
        return $this->belongsTo(Location::class, 'division_id')->where('status', 1);
    }

    public function city()
    {
        return $this->belongsTo(Location::class, 'city_id')->where('status', 1);
    }

    public function listings(){
        return $this->hasMany(Listing::class, 'area_id')->where('status', 1);
    }
    
}
