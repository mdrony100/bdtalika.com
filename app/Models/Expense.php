<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'title',
        'type',
        'amount',
        'currency',
        'billing_cycle',
        'expense_date',
        'renewal_date',
        'vendor',
        'payment_method',
        'note',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'renewal_date' => 'date',
        'amount' => 'decimal:2',
    ];
}
