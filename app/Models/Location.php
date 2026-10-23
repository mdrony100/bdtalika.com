<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'order_number',
        'photo',
        'meta_title',
        'meta_description',
        'status'
    ];

    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id')->withDefault(function ($data) {
            foreach ($data->getFillable() as $dt) {
                $data[$dt] = __('Deleted');
            }
        });
    }
    public function child()
    {
        return $this->hasMany(Location::class, 'parent_id')->where('status', 1);
    }

    public function properties()
    {
        return $this->hasMany(Listing::class);
    }

    public function listings(){
        return $this->hasMany(Listing::class, 'division_id')->where('status', 1);
    }

    public function ads(){
        return $this->hasMany(Listing::class, 'city_id')->where('status', 1);
    }

    public function areas()
    {
        return $this->hasMany(Area::class, 'city_id')->where('status', 1);
    }

    public function cities()
    {
        return $this->hasMany(Location::class, 'parent_id')->where('status', 1);
    }
}
