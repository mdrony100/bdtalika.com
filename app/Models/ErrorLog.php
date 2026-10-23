<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'status_code',
        'exception',
        'message',
        'file',
        'line',
        'url',
        'method',
        'user_id',
        'ip_address',
        'user_agent',
        'trace',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
}
