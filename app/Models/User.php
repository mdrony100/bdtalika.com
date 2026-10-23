<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Notifiable;

class User extends Authenticatable
{
   protected $fillable = [
       'name',
       'username',
       'photo',
       'breadcumb',
       'zip',
       'residency',
       'city',
       'hide_email',
       'hide_phone',
       'address',
       'phone',
       'whatsapp_opt_in',
       'whatsapp_opt_in_at',
       'fax',
       'email',
       'password',
       'user_password',
       'verification_link',
       'affilate_code',
       'is_provider',
       'twofa',
       'status',
       'go',
       'details',
       'kyc_status',
       'kyc_info',
       'kyc_reject_reason',
       'ad_limit',
       'fb_link',
       'twitter_link',
       'instagram_link',
       'linkedin_link',
       'created_at',
       'is_agent',
       'direction',
       'website',
       'about',
    ];

    protected $dates = [
        'plan_end_date',
        'whatsapp_opt_in_at',
    ];

    protected $casts = [
        'whatsapp_opt_in' => 'boolean',
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function listings(){
        return $this->hasMany(Listing::class);
    }

    public function emailLogs(){
        return $this->hasMany(EmailLog::class);
    }

    public function booking(){
        return $this->hasMany(Booking::class);
    }

    public function enquries(){
        return $this->hasMany(ListingEnquiry::class);
    }

    public function plan(){
        return $this->belongsTo(Plan::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function messages(){
        return $this->hasMany(BookingConversation::class);
    }

    public function followers(){
        return $this->hasMany(Follower::class);
    }

}
