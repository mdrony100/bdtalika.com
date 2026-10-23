<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'hide_email',
        'hide_phone',
        'email',
        'phone',
        'password',
        'address',
        'website',
        'direction',
        'role_id',
        'photo',
        'created_at',
        'updated_at',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function IsSuper(){
        if ($this->id == 1) {
           return true;
        }
        return false;
    }

    public function staff_role(){
        return $this->belongsTo('App\Models\Role','role_id')->withDefault();
    }

    public function role()
    {
    	return $this->belongsTo('App\Models\Role')->withDefault(function ($data) {
            foreach($data->getFillable() as $dt){
                $data[$dt] = __('Deleted');
            }
        });
    }

    public function Listing(){
        return $this->hasMany(Listing::class);
    }

    public function sectionCheck($value){
        $sections = explode(" , ", $this->role->section);
        if (in_array($value, $sections)){
            return true;
        }else{
            return false;
        }
    }

    public function messages(){
        return $this->hasMany(BookingConversation::class);
    }

    public function enquries(){
        return $this->hasMany(ListingEnquiry::class);
    }

}
