<?php

namespace App\Providers;

use App\Models\Font;
use App\Models\Category;
use App\Models\City;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if($this->app->environment('production') && DB::table('generalsettings')->first()->is_ssl == 1) {
            \URL::forceScheme('https');
        }

        view()->composer('*',function($settings){
            $paystack    = PaymentGateway::whereKeyword('paystack')->first();
            $paystack = $paystack->convertAutoData();

            $settings->with('seo', DB::table('seotools')->first());
            $settings->with('paystackKey', $paystack['key']);
            $settings->with('gs', DB::table('generalsettings')->first());
            $settings->with('pages', DB::table('pages')->whereStatus(1)->get());
            $settings->with('ps', DB::table('pagesettings')->first());
            $settings->with('social', DB::table('socialsettings')->first());
            $settings->with('sociallinks', DB::table('social_links')->orderBy('id','desc')->get());
            $settings->with('default_font', Font::where('is_default','=',1)->first());
            $settings->with('defaultCurrency', globalCurrency());

            // $settings->with('top_categories', Category::whereIsTop(1)
            // ->whereStatus(1)
            // ->orderBy('id', 'desc')
            // ->limit(10)
            // ->get());

            
            if (\Request::is('admin') || \Request::is('admin/*')) {
                $data = DB::table('admin_languages')->where('is_default','=',1)->first();
                App::setlocale($data->name);
            }
            else {

                if (Session::has('language')) {
                    $data = DB::table('languages')->find(Session::get('language'));
                    App::setlocale($data->name);
                }
                else {
                    $data = DB::table('languages')->where('is_default','=',1)->first();
                    App::setlocale($data->name);
                }
            }

            if (Session::has('currency')) {
                $data = DB::table('currencies')->find(Session::get('currency'));
                $settings->with('currency', $data);
            }
            else {
                $data = DB::table('currencies')->where('is_default','=',1)->first();
                $settings->with('currency', $data);
            }
        });

        Paginator::useBootstrap();

    }
}

