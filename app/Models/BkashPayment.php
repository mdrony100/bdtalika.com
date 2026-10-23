<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BkashPayment extends Model
{
    protected $fillable = [
        'user_id',
        'listing_id',
        'plan_id',
        'transaction_id',
        'merchant_invoice_number',
        'payment_id',
        'trx_id',
        'payer_reference',
        'customer_msisdn',
        'amount',
        'currency',
        'callback_status',
        'payment_status',
        'transaction_status',
        'status_code',
        'status_message',
        'raw_create_response',
        'raw_execute_response',
        'raw_query_response',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_create_response' => 'array',
        'raw_execute_response' => 'array',
        'raw_query_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
