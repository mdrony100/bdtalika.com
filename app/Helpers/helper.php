<?php

use App\Models\Plan;
use App\Models\Admin;
use App\Models\Listing;
use App\Models\Currency;
use Illuminate\Support\Str;
use App\Models\Generalsetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

if (!function_exists('globalCurrency')) {
    function globalCurrency()
    {
        $currency = Session::get('currency') ?  DB::table('currencies')->where('id', '=', Session::get('currency'))->first() : DB::table('currencies')->where('is_default', '=', 1)->first();
        return $currency;
    }
}

if (!function_exists('defaultCurrency')) {
    function defaultCurrency()
    {
        $currency = DB::table('currencies')->where('is_default', '=', 1)->first();
        return $currency;
    }
}

if (!function_exists('showSignAmount')) {
    function showSignAmount($amount)
    {
        $gs = Generalsetting::first();
        $currency = globalCurrency();

        $price = round(($amount) * $currency->value, 2);
        if ($gs->currency_format == 0) {
            return $currency->sign . ' ' . $price;
        } else {
            return $price . ' ' . $currency->sign;
        }
    }
}

if (!function_exists('showNameAmount')) {
    function showNameAmount($amount)
    {
        $gs = Generalsetting::first();
        $currency = globalCurrency();

        $price = round(($amount) * $currency->value, 2);
        if ($gs->currency_format == 0) {
            return $currency->name . ' ' . $price;
        } else {
            return $price . ' ' . $currency->name;
        }
    }
}

if (!function_exists('showAmount')) {
    function showAmount($amount)
    {
        $gs = Generalsetting::first();
        $currency = globalCurrency();

        return $price = round(($amount) * $currency->value, 2);
    }
}

if (!function_exists('showAdminAmount')) {
    function showAdminAmount($price)
    {
        $gs = Generalsetting::first();
        $currency = defaultCurrency();

        $price = round(($price) * $currency->value, 2);
        if ($gs->currency_format == 0) {
            return $currency->name . ' ' . $price;
        } else {
            return $price . ' ' . $currency->name;
        }
    }
}

if (!function_exists('showBladeAdminAmount')) {
    function showBladeAdminAmount($price)
    {
        $gs = Generalsetting::first();
        $currency = defaultCurrency();

        $price = round(($price) * $currency->value, 2);
        return $price;
    }
}

if (!function_exists('adminCurrencyAmount')) {
    function adminCurrencyAmount($amount)
    {
        $currency = Currency::where('is_default', '=', 1)->first();
        return $amount / $currency->value;
    }
}

if (!function_exists('userBaseAmount')) {
    function userBaseAmount($amount)
    {
        $currency = globalCurrency();
        return $amount / $currency->value;
    }
}

if (!function_exists('getAdmin')) {
    function getAdmin()
    {
        return Admin::first();
    }
}

if (!function_exists('prefix')) {
    function prefix()
    {
        $gs = GeneralSetting::first();
        return $gs->prefix != NULL ? $gs->prefix : 'admin';
    }
}

if (!function_exists('sanitizeTag')) {
    function sanitizeTag($tags)
    {
        $common_rep   = ["value", "{", "}", "[", "]", ":", "\""];
        $amenities = str_replace($common_rep, '', $tags);

        return $amenities;
    }
}

if (!function_exists('fileName')) {
    function fileName($file)
    {
        return Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
    }
}

if (!function_exists('upload')) {
    function upload($name, $file, $oldname)
    {
        $file->move('assets/images', $name);
        if ($oldname != null) {
            if (file_exists(public_path() . '/assets/images/' . $oldname)) {
                unlink(public_path() . '/assets/images/' . $oldname);
            }
        }
    }
}


function handleMakeImage($file, $resize_array = null, $ticket = false)
{
    $image_name = imageNameValidation($file);
    $locaion = base_path('../assets/images/');

    $fileExts = ['pdf', 'doc', 'docx', 'csv'];
    if ($ticket || in_array($file->getClientOriginalExtension(), $fileExts)) {
        $locaion = base_path('../assets/ticket/');
        $file->move($locaion, $image_name);
    } else {
        if ($resize_array) {
            $image = Image::make($file)->resize($resize_array[0], $resize_array[1]);
            if ($file->getClientOriginalExtension() == 'gif') {
                copy($file->getRealPath(), $locaion . '/' . $image_name);
            } else {
                $image->save($locaion . '/' . $image_name);
            }
        } else {
            $image = Image::make($file);
            if ($file->getClientOriginalExtension() == 'gif') {
                copy($file->getRealPath(), $locaion . '/' . $image_name);
            } else {
                $image->save($locaion . '/' . $image_name);
            }
        }
    }
    return $image_name;
}

// function processImage($file, $width = null, $height = false)
// {
//     $image = $file;
//     $name = time() . '.webp';
//     $imagePath = 'assets/images/' . $name;
//     $canvasWidth = $width ?: 850;
//     $canvasHeight = $height ?: 650;

//     $imageInstance = Image::make($image);
//     $imageInstance->fit($canvasWidth, $canvasHeight, function ($constraint) {
//         $constraint->upsize();
//     });

//     Image::canvas($canvasWidth, $canvasHeight, '#EBEEF7')->insert($imageInstance, 'center')->save($imagePath);
//     $watermark = 'assets/images/watermark.png';
//     $watermark_image = Image::make($watermark);
//     $watermarkSize = round(10 * $imageInstance->width() / 50);
//     $watermark_image->resize($watermarkSize, null, function ($constraint) {
//         $constraint->aspectRatio();
//     });
//     $watermarkedImage = Image::make($imagePath)->insert($watermark_image, 'bottom-right', 10, 10)->save($imagePath);
//     return $name;
// }

function processImage($file)
{
    $image = $file;
    $name = uniqid() . '.webp';
    $imagePath = 'assets/images/' . $name;

    // Load the image
    $imageInstance = Image::make($image);
    $canvasWidth = $imageInstance->width();
    $canvasHeight = $imageInstance->height();

    // Process and save the image
    $imageInstance->save($imagePath);

    // Add watermark to the image
    $watermark = 'assets/images/watermark.png';
    $watermark_image = Image::make($watermark);
    $watermarkSize = round(10 * $imageInstance->width() / 50);
    $watermark_image->resize($watermarkSize, null, function ($constraint) {
        $constraint->aspectRatio();
    });
    $watermarkedImage = Image::make($imagePath)->insert($watermark_image, 'bottom-right', 10, 10)->save($imagePath);

    return $name;
}




function handleUpdateImage($file, $field, $resize_array = null)
{
    $image_name = imageNameValidation($file);
    $locaion = base_path('../assets/images/');

    if ($field && file_exists($locaion . $field)) {
        unlink($locaion . $field);
    }
    if ($resize_array) {
        $image = Image::make($file)->resize($resize_array[0], $resize_array[1]);
        if ($file->getClientOriginalExtension() == 'gif') {
            copy($file->getRealPath(), $locaion . '/' . $image_name);
        } else {
            $image->save($locaion . '/' . $image_name);
        }
    } else {
        $image = Image::make($file);
        if ($file->getClientOriginalExtension() == 'gif') {
            copy($file->getRealPath(), $locaion . '/' . $image_name);
        } else {
            $image->save($locaion . '/' . $image_name);
        }
    }
    return $image_name;
}


function handleDeleteImage($field)
{
    $locaion = base_path('../assets/images/');
    if ($field && file_exists($locaion . $field)) {
        unlink($locaion . $field);
    }
}

function imageNameValidation($image)
{
    $extension = $image->getClientOriginalExtension();
    $old_name  = explode('.', $image->getClientOriginalName());
    $new_name = rand() . time() . '.' . $extension;
    return $new_name;
}

function ExtensionValidation($image)
{
    $extension = ['jpg', 'JPG', 'jpeg', 'JPEG', 'zip', 'pdg', 'csv', 'png', 'PNG', 'pdf', 'doc', 'docx', 'webp'];
    $image_extension = $image->getClientOriginalExtension();
    if (in_array($image_extension, $extension)) {
        return true;
    } else {
        return false;
    }
}

function agentReview()
{
}


function email($data)
{

    $gs = Generalsetting::first();

    if ($gs->is_smtp != 1) {
        $headers = "From: $gs->sitename <$gs->email_from> \r\n";
        $headers .= "Reply-To: $gs->sitename <$gs->email_from> \r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        mail($data['email'], $data['subject'], $data['message'], $headers);
    } else {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $gs->smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $gs->smtp_user;
            $mail->Password   = $gs->smtp_pass;
            if ($gs->smtp_encryption == 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port       = $gs->smtp_port;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($gs->from_email, $gs->from_name);
            $mail->addAddress($data['email'], $data['name']);
            $mail->addReplyTo($gs->from_email, $gs->from_name);
            $mail->isHTML(true);
            $mail->Subject = $data['subject'];
            $mail->Body    = $data['message'];
            $mail->send();
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}

if (!function_exists('typeAreaMatchInListings')) {
    function typeAreaMatchInListings($dhaka_areas, $type)
    {
        $matchedAreas = [];
        if (isset($dhaka_areas) && $dhaka_areas->count() > 0) {
            foreach ($dhaka_areas as $area) {
                $listingsInArea = Listing::where('type', $type)->where('area_id', $area->id)->exists();
                if ($listingsInArea) {
                    $matchedAreas[] = $area->slug;
                }
            }
        }
        return $matchedAreas;
    }
}

if (!function_exists('getSiteSettings')) {
    function getSiteSettings()
    {
        return Cache::remember('site_settings', 60 * 60, function () {
            return DB::table('settings')->first();
        });
    }
}

// Listing active plan
if (!function_exists('getListingPlan')) {
    function getListingPlan($plan_id)
    {
        return Plan::where('id', $plan_id)->first();
    }
}

// Listing  plan duration days count
if (!function_exists('ListingPlanDuration')) {
    function ListingPlanDuration($ad_id)
    {
        $listing = Listing::with('plan')->find($ad_id);

        if (!$listing || !$listing->plan) {
            return null;
        }

        if (
            $listing->status == '1'
            && $listing->plan_id
            && in_array($listing->plan->plan_type, ['free', 'lifetime'])
        ) {
            return '<span class="text-success">Your business has been extended for a lifetime</span>';
        }

        if ($listing->plan_start_date && $listing->plan_end_date) {
            $startDate = Carbon::parse($listing->plan_start_date);
            $endDate   = Carbon::parse($listing->plan_end_date);
            $days      = $endDate->diffInDays($startDate) + 1;

            if ($days > 0) {
                return '<span class="text-danger">Your business will be disabled in ' . $days . ' days</span>';
            }
        }

        return null;
    }

}




 