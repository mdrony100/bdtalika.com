<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppMessage extends Model
{
    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'user_id',
        'admin_id',
        'listing_id',
        'idempotency_key',
        'recipient_phone',
        'template_name',
        'purpose',
        'source',
        'status',
        'meta_message_id',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class)->withDefault();
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class)->withDefault();
    }
}
