<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class)->withDefault();
    }
}
