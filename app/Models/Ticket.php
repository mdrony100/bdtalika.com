<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['subject', 'priority', 'status'];

    public function message(){
        $this->hasMany(TicketMessage::class, 'ticket_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
