<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use function GuzzleHttp\json_decode;

class Pagesetting extends Model
{
    protected $fillable = [
        'contact_success',
        'contact_email',
        'contact_title',
        'contact_text',
        'street',
        'phone',
        'site',
        'phone_code',
        'side_text',
        'review_blog',
        'category_title',
        'category_subtitle',
        'pricing_plan',
        'plan_title',
        'plan_subtitle',
        'directory_title',
        'directory_subtitle',
        'location_title',
        'location_subtitle',
        'blog_title',
        'blog_subtitle',
        'mission_title',
        'mission_text',
        'mission_photo',
        'process_title',
        'process_text',
        'process_photo',
        'partner_title',
        'partner_subtitle',
        'download_title',
        'download_subtitle',
        'download_text',
        'download_photo',
        'google_play_link',
        'app_store_link',
        'footer_top_photo',
        'footer_top_title',
        'footer_top_text',
        'hero_title',
        'hero_subtitle',
        'hero_photo',
        'referral_percentage',
        'listing_title',
        'listing_subtitle',
        'listing_details',
        'listing_photo',
        'call_title',
        'call_subtitle',
        'call_bg',
        'latitude',
        'longitude',
        'home_module',
        'about_details',
    ];

    public $timestamps = false;

    public function upload($name,$file,$oldname)
    {
        $file->move('assets/images',$name);
        if($oldname != null)
        {
            if (file_exists(public_path().'/assets/images/'.$oldname)) {
                unlink(public_path().'/assets/images/'.$oldname);
            }
        }
    }

    public function homeModuleCheck($value)
    {
        $home_module = $this->home_module != NULL ? $this->home_module : [];
        $sections = json_decode($home_module,true);
        if (in_array($value, $sections)){
            return true;
        }else{
            return false;
        }
    }

}
