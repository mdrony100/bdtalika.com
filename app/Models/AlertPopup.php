<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertPopup extends Model
{
    use HasFactory;
    protected $fillable = ['image','iframe_video', 'video_file', 'views', 'link', 'status'];

    public function getViewsAttribute($value)
    {
        return $value ?? 0;
    }
}
