<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopBanner extends Model
{
    use HasFactory;
    protected $fillable = ['image', 'link', 'views', 'status'];

    public function getViewsAttribute($value)
    {
        return $value ?? 0;
    }
}
