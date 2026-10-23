<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppReminderLog extends Model
{
    protected $table = 'whatsapp_reminder_logs';

    protected $fillable = [
        'listing_id',
        'user_id',
        'phone',
        'message',
        'type',
        'sent_by',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
