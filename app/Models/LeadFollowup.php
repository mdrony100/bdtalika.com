<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFollowup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
        'status',
        'comment',
        'next_recall_date',
        'last_called_at',
        'called_by_admin_id',
    ];

    protected $casts = [
        'next_recall_date' => 'date',
        'last_called_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function calledByAdmin()
    {
        return $this->belongsTo(Admin::class, 'called_by_admin_id');
    }
}
