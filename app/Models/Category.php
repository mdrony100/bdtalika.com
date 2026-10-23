<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'icon',
        'photo',
        'status',
        'type_id',
        'parent_id',
        'order_number',
        'meta_title',
        'meta_description',
        'is_top',
        'is_grid',
        'bg_color',
    ];

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function child(){
        return $this->hasMany(Category::class,'parent_id');
    }

    public function listings(){
        return $this->hasMany(Listing::class, 'category_id')->where('status', 1);
    }

    public function type(){
        return $this->belongsTo(ListingType::class, 'type_id');
    }

}
