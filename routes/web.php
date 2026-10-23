<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\MonaAIController;
use App\Http\Controllers\MatchCaptchaController;
use App\Http\Controllers\User\ListingController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Cron\WhatsAppAbandonedPaymentController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\DirectoryController;
Route::get('/public/{any?}', function () {
    abort(404);
})->where('any', '.*');

Route::get('/clear', function () {

  Artisan::call('cache:clear');
  Artisan::call('config:clear');
  Artisan::call('config:cache');
  Artisan::call('view:clear');

  return "Cleared!";
});

Route::get('/cron/fetch-google-reviews', [FrontendController::class, 'fetchGoogleReviews']);
Route::get('/cron/listing-package-reminders', [AdminListingController::class, 'sendPackageReminderToDraftListings']);
Route::get('/cron/update-missing-whatsapp-listings-ai', [AdminListingController::class, 'updateMissingWhatsappListingsWithAi'])->name('cron.update.missing.whatsapp.listings.ai');
Route::get('/cron/customer-business-add-marketing-mails', [AdminUserController::class, 'sendBusinessAddMarketingCron'])->name('cron.customer.business.add.marketing.mails');
  Route::get('/cron/monthly-analytics-report', [AdminUserController::class, 'sendMonthlyAnalyticsReportCron'])->name('cron.monthly.analytics.report');
Route::get('/cron/whatsapp-abandoned-payment', WhatsAppAbandonedPaymentController::class)->name('cron.whatsapp.abandoned.payment');
Route::get('/mailtest', function() {
                $to = "bdtalika@gmail.com";
                $subject = "Test mail from website";
                $msg = "Hello , this is test mail from website";
                $from_name = "bdtalika";
                $from_email= "info@bdtalika.com";
                $headers = "From: " . $from_name . " <" . $from_email . ">";
                $res = mail($to, $subject, $msg, $headers);
                return $res;
    });
Route::redirect('admin', 'admin/login');
Route::post('the/genius/ocean/2441139', 'Frontend\FrontendController@subscription');
Route::get('finalize', 'Frontend\FrontendController@finalize');

Route::get('/under-maintenance', 'Frontend\FrontendController@maintenance')->name('front-maintenance');
Route::group(['middleware' => 'maintenance'], function () {
  // listing status change with plan duration
  Route::get('/listings/plans/validate-expiry', [FrontendController::class, 'expireListingPlans']);

  Route::get('/', [FrontendController::class, 'index'])->name('front.index');
  Route::get('/listing/{area?}/{category?}', [DirectoryController::class, 'listing'])->name('front.listing');
  Route::get('/pages/{type?}-in-{area?}', [DirectoryController::class, 'listingSearch'])->name('front.listing.page');
  Route::get('/details/{slug}', [DirectoryController::class, 'listingDetails'])->name('front.listing.details');
  Route::post('/listing/wishlist', [DirectoryController::class, 'wishlist'])->name('front.property.wishlist');
  Route::get('/author/{username}', [DirectoryController::class, 'authorDetails'])->name('front.author.details');
  Route::post('/listing-enquiry', [DirectoryController::class, 'listingEnquiry'])->name('front.listing.enquiry');
  Route::post('/listing-analytics/track', [DirectoryController::class, 'trackAnalytics'])->name('front.listing.analytics.track');
  Route::post('/listing-analytics/impressions', [DirectoryController::class, 'trackImpressions'])->name('front.listing.analytics.impressions');
  Route::post('/listing/review', [DirectoryController::class, 'review'])->name('front.listing.review');
  Route::post('/follow', [DirectoryController::class, 'follow'])->name('front.property.follow');
  Route::get('/holidays', [DirectoryController::class, 'publicHoliday'])->name('front.public-holiday');
  Route::get('/advertising', [DirectoryController::class, 'advertising'])->name('front.advertise');
  Route::get('/listing-type/{type?}', [DirectoryController::class, 'typeListing'])->name('front.type.listing');

  Route::get('browse-business-directory', [FrontendController::class, 'categoryDirectory'])->name('front.category.directory');
  Route::get('browse-business-cities', [FrontendController::class, 'citiesDirectory'])->name('front.cities.directory');
  Route::get('blogs', [FrontendController::class, 'blog'])->name('front.blog');
  Route::get('blog-details/{slug}', [FrontendController::class, 'blogdetails'])->name('blog.details');
  Route::get('/blog-search', [FrontendController::class, 'blogsearch'])->name('front.blogsearch');
  Route::get('/blog/category/{slug?}', [FrontendController::class, 'blogcategory'])->name('front.blogcategory');
  Route::get('/blog/tag/{slug}', [FrontendController::class, 'blogtags'])->name('front.blogtags');
  Route::get('/blog/archive/{slug}', [FrontendController::class, 'blogarchive'])->name('front.blogarchive');
  Route::get('/pricing-plans', [FrontendController::class, 'plans'])->name('front.plans');
  Route::get('/subscribe', [FrontendController::class, 'subscribePage'])->name('front.subscribe.page');
  Route::post('/claim/business/{id}', [FrontendController::class, 'claimBusiness'])->name('front.claim.business');
  Route::post('/report-business/{id}', [FrontendController::class, 'reportBusiness'])->name('front.report.business');

  Route::post('/banner/click/count/{id?}', [FrontendController::class, 'bannerClickCount'])->name('front.topbanner.click.count');
  Route::post('/alert-popup/click/count/{id?}', [FrontendController::class, 'alertpopupClickCount'])->name('front.alertpopup.click.count');


  Route::get('/contact', [FrontendController::class, 'contact'])->name('front.contact');
  Route::post('/contact', [FrontendController::class, 'contactemail'])->name('front.contact.submit');
  Route::get('/faq', [FrontendController::class, 'faq'])->name('front.faq');
  Route::get('/sitemap', [FrontendController::class, 'sitemap'])->name('sitemap');
  Route::get('/sitemap.xml', [FrontendController::class, 'sitemapGenerate']);
  Route::get('/{slug}', [FrontendController::class, 'page'])->name('front.page');
  Route::post('/subscriber', [FrontendController::class, 'subscriber'])->name('front.subscriber');
  Route::get('/get-captcha', [MatchCaptchaController::class, 'resetCaptcha'])->name('front.captcha');
  Route::post('/slider-view-count/{id?}', [FrontendController::class, 'sliderViewCount'])->name('front.slider.view.count');
  Route::post('/slider-analytics/track', [FrontendController::class, 'trackSliderAnalytics'])->name('front.slider.analytics.track');



  Route::get('/currency/{id}', [FrontendController::class, 'currency'])->name('front.currency');
  Route::get('/language/{id}', [FrontendController::class, 'language'])->name('front.language');

  Route::get('/signup-session/flus', [FrontendController::class, 'signupSession'])->name('front.signup.session');

  // listing page 
  Route::get('/division/city/{id?}', [ListingController::class, 'DivisionGetCity'])->name('front.division.to.city');
  Route::get('/city/area/{id?}', [ListingController::class, 'CityGetArea'])->name('front.city.to.area');
});



Route::get('/profit/send', [FrontendController::class, 'profitSend'])->name('front.profit.send');


Route::post('/mona/chat', [MonaAIController::class, 'chat']);
