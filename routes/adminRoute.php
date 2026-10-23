<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\SMSController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\FontController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CrmController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\MissionController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\SeoToolController;
use App\Http\Controllers\Admin\SitemapController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KycManageController;
use App\Http\Controllers\Admin\AlertPopupController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\PageSettingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\TransactionInvoiceController;
use App\Http\Controllers\Admin\AdminLanguageController;
use App\Http\Controllers\Admin\SocialSettingController;
use App\Http\Controllers\Admin\AccountProcessController;
use App\Http\Controllers\Admin\BookingRequestController;
use App\Http\Controllers\Admin\DiretoryReviewController;
use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\CronLogController;
use App\Http\Controllers\Admin\ErrorLogController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\WhatsAppMessagingController;


Route::prefix('admin')->group(function(){

    Route::get('/cache/clear', function() {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return redirect()->route('admin.dashboard')->with('cache','System Cache Has Been Removed.');
    })->name('admin.cache.clear');

      Route::get('/login', [LoginController::class,'showLoginForm'])->name('admin.login');
      Route::post('/login', [LoginController::class,'login'])->name('admin.login.submit');
      Route::get('/forgot', [LoginController::class,'showForgotForm'])->name('admin.forgot');
      Route::post('/forgot-submit', [LoginController::class,'forgot'])->name('admin.forgot.submit');
      Route::get('/change-password/{token}', [LoginController::class,'showChangePassForm'])->name('admin.change.token');
      Route::post('/change-password', [LoginController::class,'changepass'])->name('admin.change.password');
      Route::get('/logout', [LoginController::class,'logout'])->name('admin.logout');

      Route::get('/profile', [DashboardController::class,'profile'])->name('admin.profile');
    Route::post('/profile/update', [DashboardController::class,'profileupdate'])->name('admin.profile.update');

      Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
      Route::get('/password', [DashboardController::class,'passwordreset'])->name('admin.password');
      Route::post('/password/update', [DashboardController::class,'changepass'])->name('admin.password.update');

      Route::get('/contacts',[DashboardController::class,'getContacts'])->name('admin.contact.index');
      Route::get('/contact/view/{id}',[DashboardController::class,'viewContact'])->name('admin.contact.view');


      Route::get('/tickets/', [TicketController::class, 'index'])->name('admin.ticket.index'); 
      Route::get('/ticket/view/{id}', [TicketController::class, 'view'])->name('admin.ticket.view');
      Route::post('/ticket/message/{id}', [TicketController::class, 'ticketMessage'])->name('admin.ticket.message');
      Route::post('/ticket/status/{id}', [TicketController::class, 'ticketStatusUpdate'])->name('admin.ticket.status');


      Route::group(['middleware'=>'permissions:Menu Builder'],function(){
        Route::get('/menu-builder', [GeneralSettingController::class,'menubuilder'])->name('admin.gs.menubuilder');
      });

      Route::group(['middleware'=>'permissions:Manage Plan'],function(){
        Route::get('/plans/datatables', [PlanController::class,'datatables'])->name('admin.plans.datatables');
        Route::get('/plans', [PlanController::class,'index'])->name('admin.plans.index');
        Route::get('/plans/create', [PlanController::class,'create'])->name('admin.plans.create');
        Route::get('/plans/edit/{id}', [PlanController::class,'edit'])->name('admin.plans.edit');
        Route::post('/plans/create', [PlanController::class,'store'])->name('admin.plans.store');
        Route::get('/plans/status/{id1}/{id2}', [PlanController::class,'status'])->name('admin.plans.status');
        Route::post('/plans/update/{id}', [PlanController::class,'update'])->name('admin.plans.update');
        Route::get('/plans/delete/{id}', [PlanController::class,'destroy'])->name('admin.plans.delete');
        Route::get('/plans/bulk-delete', [PlanController::class,'bulkdelete'])->name('admin.plans.bulk.delete');
      });

      Route::group(['middleware'=>'permissions:Categories'],function(){
        Route::get('/categories/datatables', [CategoryController::class,'datatables'])->name('admin.categories.datatables');
        Route::get('/categories', [CategoryController::class,'index'])->name('admin.categories.index');
        Route::get('/categories/create', [CategoryController::class,'create'])->name('admin.categories.create');
        Route::get('/categories/edit/{id}', [CategoryController::class,'edit'])->name('admin.categories.edit');
        Route::post('/categories/store', [CategoryController::class,'store'])->name('admin.categories.store');
        Route::get('/categories/status/{id1}/{id2}', [CategoryController::class,'status'])->name('admin.categories.status');
        Route::post('/categories/update/{id}', [CategoryController::class,'update'])->name('admin.categories.update');
        Route::get('/categories/delete/{id}', [CategoryController::class,'destroy'])->name('admin.categories.delete');
        Route::get('/categories/bulk-delete', [CategoryController::class,'bulkdelete'])->name('admin.categories.bulk.delete');
     });


     Route::group(['middleware'=>'permissions:Slider'],function(){
        Route::get('/sliders/', [SliderController::class,'index'])->name('admin.slider.index');
        Route::get('/slider/create', [SliderController::class,'create'])->name('admin.slider.create');
        Route::post('/slider/store', [SliderController::class,'store'])->name('admin.slider.store');
        Route::get('/slider/edit/{id}', [SliderController::class,'edit'])->name('admin.slider.edit');
        Route::post('/slider/update/{id}', [SliderController::class,'update'])->name('admin.slider.update');
        Route::get('/slider/delete/{id}', [SliderController::class,'delete'])->name('admin.slider.delete');
        Route::get('/slider/analytics/{id}', [SliderController::class,'analytics'])->name('admin.slider.analytics');
        Route::post('/slider/send-report/{id}', [SliderController::class,'sendReport'])->name('admin.slider.send.report');
     });

      Route::group(['middleware'=>'permissions:Amenities'],function(){
        Route::get('/amenities/datatables', [AmenityController::class,'datatables'])->name('admin.amenities.datatables');
        Route::get('/amenities', [AmenityController::class,'index'])->name('admin.amenities.index');
        Route::get('/amenities/create', [AmenityController::class,'create'])->name('admin.amenities.create');
        Route::get('/amenities/edit/{id}', [AmenityController::class,'edit'])->name('admin.amenities.edit');
        Route::post('/amenities/store', [AmenityController::class,'store'])->name('admin.amenities.store');
        Route::get('/amenities/status/{id1}/{id2}', [AmenityController::class,'status'])->name('admin.amenities.status');
        Route::post('/amenities/update/{id}', [AmenityController::class,'update'])->name('admin.amenities.update');
        Route::get('/amenities/delete/{id}', [AmenityController::class,'destroy'])->name('admin.amenities.delete');
        Route::get('/amenities/bulk-delete', [AmenityController::class,'bulkdelete'])->name('admin.amenities.bulk.delete');
     });

     Route::group(['middleware'=>'permissions:Locations'],function(){
        Route::get('/locations/datatables', [LocationController::class,'datatables'])->name('admin.locations.datatables');
        Route::get('/locations', [LocationController::class,'index'])->name('admin.locations.index');
        Route::get('/locations/create', [LocationController::class,'create'])->name('admin.locations.create');
        Route::get('/locations/edit/{id}', [LocationController::class,'edit'])->name('admin.locations.edit');
        Route::post('/locations/store', [LocationController::class,'store'])->name('admin.locations.store');
        Route::get('/locations/status/{id1}/{id2}', [LocationController::class,'status'])->name('admin.locations.status');
        Route::post('/locations/update/{id}', [LocationController::class,'update'])->name('admin.locations.update');
        Route::get('/locations/delete/{id}', [LocationController::class,'destroy'])->name('admin.locations.delete');
        Route::get('/locations/bulk-delete', [LocationController::class,'bulkdelete'])->name('admin.locations.bulk.delete');
     });

        Route::group(['middleware'=>'permissions:Directory Listing'],function(){
        Route::get('/listing/datatables/{status}', [ListingController::class,'datatables'])->name('admin.listing.datatables');
        Route::get('/listing', [ListingController::class,'index'])->name('admin.listing.index');
        Route::get('/mailtesting', [ListingController::class,'mail']);
        Route::get('/paid-listing', [ListingController::class,'paid'])->name('admin.listing.paid');
        Route::get('/pending-listing', [ListingController::class,'pending'])->name('admin.listing.pending');
        Route::get('/upcoming-renew', [ListingController::class,'upcomingRenew'])->name('admin.listing.upcoming.renew');
        Route::get('/requires-review-listing', [ListingController::class,'requiresreview'])->name('admin.listing.requires.review');
        Route::get('/approved-listing', [ListingController::class,'approved'])->name('admin.listing.approved');
        Route::get('/listing-by-views', [ListingController::class,'listingByViews'])->name('admin.listing.byviews');
        Route::get('/listing-by-views/analytics/{id}', [ListingController::class,'listingByViewsAnalytics'])->name('admin.listing.byviews.analytics');
        Route::get('/listing-seo-offer/settings', [ListingController::class,'seoOfferSettings'])->name('admin.listing.seo.offer.settings');
        Route::post('/listing-seo-offer/settings/update', [ListingController::class,'seoOfferSettingsUpdate'])->name('admin.listing.seo.offer.settings.update');
        Route::get('/reject-listing', [ListingController::class,'reject'])->name('admin.reject.listing');
        Route::get('/claim-listing', [ListingController::class,'claimListing'])->name('admin.claim.listing');
        Route::get('/claim-listing-solve/{id}', [ListingController::class,'claimListingSolve'])->name('admin.claim.listing.solve');
        Route::get('/claim-listing/{id}', [ListingController::class,'claimListingView'])->name('admin.claim.listing.view');
        Route::get('/report-listing', [ListingController::class,'reportListing'])->name('admin.report.listing');
        Route::get('/report-listing/{id}', [ListingController::class,'reportListingView'])->name('admin.report.listing.view');
        Route::get('/report-listing-delete/{id}', [ListingController::class,'reportListingDelete'])->name('admin.report.listing.delete');
        Route::get('/draft-listing/mail/{id}', [ListingController::class,'listingMailPage'])->name('admin.listing.mail.page');
        Route::post('/draft/listing/mail', [ListingController::class,'driftListingMail'])->name('admin.listing.sendmail');
        Route::get('/listings/{listing}/whatsapp-reminder', [ListingController::class,'whatsappReminder'])->name('admin.listing.whatsapp.reminder');
        Route::get('/listings/{listing}/whatsapp-logs', [ListingController::class,'whatsappReminderLogs'])->name('admin.listing.whatsapp.logs');

        Route::get('/whatsapp-messaging', [WhatsAppMessagingController::class,'index'])->name('admin.whatsapp.index');
        Route::post('/whatsapp-messaging/{listing}/send', [WhatsAppMessagingController::class,'send'])->name('admin.whatsapp.send');


        Route::get('/listing/type', [ListingController::class,'type'])->name('admin.listing.type');
        Route::get('/listing/type/category/{id?}', [ListingController::class,'BusinessTypeCategory'])->name('admin.businesstype.to.category');
        Route::post('/listing/create/type', [ListingController::class,'createType'])->name('admin.listing.create.type');
        Route::get('/listing/create', [ListingController::class,'create'])->name('admin.listing.create');
        Route::get('/listing/edit/type/{id}', [ListingController::class,'editType'])->name('admin.listing.edit.type');
        Route::post('/listing/update/type/{id}', [ListingController::class,'updteType'])->name('admin.listing.update.type');
        Route::get('/listing/edit/{id}', [ListingController::class,'edit'])->name('admin.listing.edit');
        Route::get('/listing/view/{id}', [ListingController::class,'view'])->name('admin.listing.view');
        Route::post('/listing/store', [ListingController::class,'store'])->name('admin.listing.store');
        Route::post('/listing/update/{id}', [ListingController::class,'update'])->name('admin.listing.update');
        Route::get('/listing/status/{id1}/{id2}', [ListingController::class,'status'])->name('admin.listing.status');
        Route::get('/listing/reject/{id?}', [ListingController::class,'listingReject'])->name('admin.listing.reject');
        Route::post('/listing/reject/submit/{id}', [ListingController::class,'listingRejectSubmit'])->name('admin.listing.reject.submit');
        Route::get('/listing/delete/{id}', [ListingController::class,'destroy'])->name('admin.listing.delete');
        Route::get('/listing-menu/delete/{id}', [ListingController::class,'menuDestroy'])->name('admin.listing.menu.delete');
        Route::get('/listing-room/delete/{id}', [ListingController::class,'roomDestroy'])->name('admin.listing.room.delete');
        Route::get('/listing/faq/{id}', [ListingController::class,'faqDestroy'])->name('admin.listing.faq.delete');
        Route::get('/listing/service/{id}', [ListingController::class,'serviceDestroy'])->name('admin.listing.service.delete');
        Route::get('/listing/bulk-delete', [ListingController::class,'bulkdelete'])->name('admin.listing.bulk.delete');
        Route::get('/listing/review/{id?}', [ListingController::class,'Review'])->name('admin.listing.review');
        Route::post('/listing/get/review/{id}', [ListingController::class,'getReview'])->name('admin.listing.reviewFetch');
        Route::post('/listing/update/review/{id}', [ListingController::class,'updateReview'])->name('admin.listing.review.update');
//open ai 
        Route::post('/admin/generate-description', [ListingController::class, 'generateDescription'])
    ->name('admin.generate.description');

        Route::get('/admin/gemini/', [ListingController::class,'generateContent'])->name('admin.gemini');


        Route::get('/slug/check', [ListingController::class, 'listingSlugCheck'])->name('admin.listing.slug.check');
        Route::get('/slug/check/{id?}', [ListingController::class, 'listingUpdateSlugCheck'])->name('admin.listing.update.slug.check');

        Route::get('/gallery/show', [GalleryController::class,'show'])->name('admin.gallery.show');
        Route::post('/gallery/store', [GalleryController::class,'store'])->name('admin.gallery.store');
        Route::get('/gallery/delete', [GalleryController::class,'destroy'])->name('admin.gallery.delete');
     });

     Route::group(['middleware'=>'permissions:Directory Reviews'],function(){
        Route::get('/diretory/review/datatables', [DiretoryReviewController::class,'datatables'])->name('admin.diretory.review.datatables');
        Route::get('/diretory/review', [DiretoryReviewController::class,'index'])->name('admin.diretory.review.index');
        Route::get('/diretory/review/status/{id1}/{id2}', [DiretoryReviewController::class,'status'])->name('admin.diretory.review.status');
     });

     Route::group(['middleware'=>'permissions:Booking Request'],function(){
        Route::get('/booking/datatables/{type}', [BookingRequestController::class,'datatables'])->name('admin.booking.datatables');
        Route::get('/hotel-booking', [BookingRequestController::class,'hotel'])->name('admin.hotel.booking');
        Route::get('/restaurant-booking', [BookingRequestController::class,'restaurant'])->name('admin.restaurant.booking');
        Route::get('/beauty-booking', [BookingRequestController::class,'beauty'])->name('admin.beauty.booking');
        Route::get('/booking/status/{id1}/{id2}', [BookingRequestController::class,'status'])->name('admin.booking.status');
        Route::get('/booking/delete/{id}', [BookingRequestController::class,'destroy'])->name('admin.booking.delete');
        Route::get('/booking/bulk-delete', [BookingRequestController::class,'bulkdelete'])->name('admin.booking.bulk.delete');
        Route::get('/booking-message/{id}', [BookingRequestController::class,'message'])->name('admin.booking.message');
        Route::post('/booking-message/{id}', [BookingRequestController::class,'messageSubmit'])->name('admin.booking.message.submit');
     });

     Route::group(['middleware'=>'permissions:Directory Enquiry'],function(){
        Route::get('/listing-enquiry/datatables', [EnquiryController::class,'datatables'])->name('admin.enquiry.datatables');
        Route::get('/listing-enquiry', [EnquiryController::class,'index'])->name('admin.listing.enquiry');
        Route::get('/listing-enquiry/delete/{id}', [EnquiryController::class,'destroy'])->name('admin.enquiry.delete');
        Route::get('/listing-enquiry/bulk-delete', [EnquiryController::class,'bulkdelete'])->name('admin.listing.enquiry.bulk.delete');
     });


      Route::get('/areas/datatables', [AreaController::class,'datatables'])->name('admin.areas.datatables');
      Route::get('/areas', [AreaController::class,'index'])->name('admin.areas.index');
      Route::get('/areas/create', [AreaController::class,'create'])->name('admin.areas.create');
      Route::get('/areas/edit/{id}', [AreaController::class,'edit'])->name('admin.areas.edit');
      Route::post('/areas/create', [AreaController::class,'store'])->name('admin.areas.store');
      Route::get('/areas/status/{id1}/{id2}', [AreaController::class,'status'])->name('admin.areas.status');
      Route::post('/areas/update/{id}', [AreaController::class,'update'])->name('admin.areas.update');
      Route::get('/areas/delete/{id}', [AreaController::class,'destroy'])->name('admin.areas.delete');
      Route::get('/areas/bulk-delete', [AreaController::class,'bulkdelete'])->name('admin.areas.bulk.delete');

      Route::get('/division/city/{id?}', [AreaController::class,'DivisionGetCity'])->name('admin.division.to.city');
      Route::get('/city/area/{id?}', [AreaController::class,'CityGetArea'])->name('admin.city.to.area');
      Route::get('/search/keywords', [DashboardController::class,'searchKeywords'])->name('admin.search.keywords');


      Route::get('/banners', [BannerController::class,'index'])->name('admin.banner.index');
      Route::get('/banner/create', [BannerController::class,'create'])->name('admin.banner.create');
      Route::post('/banner/store', [BannerController::class,'store'])->name('admin.banner.store');
      Route::get('/banner/edit/{id}', [BannerController::class,'edit'])->name('admin.banner.edit');
      Route::post('/banner/update/{id}', [BannerController::class,'update'])->name('admin.banner.update');
      Route::get('/banner/{id}', [BannerController::class,'delete'])->name('admin.banner.delete');


      Route::group(['middleware'=>'permissions:Manage Customers'],function(){
        Route::get('/users/bonus', 'Admin\BonusController@index')->name('admin.user.bonus');
        Route::post('/users/edit/', 'Admin\BonusController@update')->name('admin.bonus.update');

        Route::get('/users/datatables', [UserController::class,'datatables'])->name('admin-user-datatables');
        Route::get('/users', [UserController::class,'index'])->name('admin.user.index');
        Route::post('/users/send-email', [UserController::class,'sendEmail'])->name('admin-user-send-email');
        Route::get('/users/send-business-add-mail/{id}', [UserController::class,'sendBusinessAddMarketingMail'])->name('admin-user-send-business-add-mail');
        Route::get('/users/login-as-user/{id}', [UserController::class,'loginAsUser'])->name('admin.user.login.as.user');
        Route::get('/users/back-to-admin', [UserController::class,'backToAdminPanel'])->name('admin.user.back.to.admin');
        Route::get('/users/logs/{id}', [UserController::class,'logs'])->name('admin.user.logs');
        Route::get('/users/email-logs/{id}', [UserController::class,'emailLogs'])->name('admin.user.email.logs');
        Route::get('/cron-logs', [CronLogController::class,'index'])->name('admin.cron.logs');
        Route::delete('/cron-logs/clear', [CronLogController::class,'clear'])->name('admin.cron.logs.clear');
        Route::delete('/cron-logs/{id}', [CronLogController::class,'destroy'])->name('admin.cron.logs.delete');
        Route::get('/error-logs', [ErrorLogController::class,'index'])->name('admin.error.logs');
        Route::get('/error-logs/clear', [ErrorLogController::class,'clear'])->name('admin.error.logs.clear');
        Route::get('/error-logs/{id}/delete', [ErrorLogController::class,'destroy'])->name('admin.error.logs.delete');
        Route::get('/users/edit/{id}', [UserController::class,'edit'])->name('admin-user-edit');
        Route::get('/users/verify/{id}', [UserController::class,'verifyUser'])->name('admin-user-verify');
        Route::post('/users/edit/{id}', [UserController::class,'update'])->name('admin-user-update');
        Route::get('/users/delete/{id}', [UserController::class,'destroy'])->name('admin-user-delete');
        Route::get('/user/{id}/show', [UserController::class,'show'])->name('admin-user-show');
        Route::get('/users/ban/{id1}/{id2}', [UserController::class,'ban'])->name('admin-user-ban');
        Route::get('/user/default/image', [UserController::class,'image'])->name('admin-user-image');
        Route::get('/users/deposit/{id}', [UserController::class,'deposit'])->name('admin-user-deposit');
        Route::post('/user/deposit/{id}', [UserController::class,'depositUpdate'])->name('admin-user-deposit-update');
        Route::post('/user/balance/add/deduct', [UserController::class,'adddeduct'])->name('admin.user.balance.add.deduct');
      });

      Route::group(['middleware'=>'permissions:CRM Leads'],function(){
        Route::get('/crm/leads', [CrmController::class,'index'])->name('admin.crm.index');
        Route::get('/crm/leads/to-call/datatables', [CrmController::class,'toCallDatatables'])->name('admin.crm.tocall.datatables');
        Route::get('/crm/leads/follow-up/datatables', [CrmController::class,'followUpDatatables'])->name('admin.crm.followup.datatables');
        Route::get('/crm/leads/end-journey/datatables', [CrmController::class,'endJourneyDatatables'])->name('admin.crm.endjourney.datatables');
        Route::post('/crm/leads/update-status', [CrmController::class,'updateStatus'])->name('admin.crm.update.status');
      });

      Route::group(['middleware'=>'permissions:Transactions'],function(){
        Route::get('/transactions/datatables', [TransactionController::class,'datatables'])->name('admin.transactions.datatables');
        Route::get('/transactions', [TransactionController::class,'index'])->name('admin.transactions.index');
        Route::get('/transactions/invoice/{id}', [TransactionInvoiceController::class,'adminDownload'])->name('admin.transactions.invoice');
        Route::get('/transactions/banner-invoice', [TransactionController::class,'bannerInvoiceCreate'])->name('admin.transactions.banner.invoice');
        Route::post('/transactions/banner-invoice/store', [TransactionController::class,'bannerInvoiceStore'])->name('admin.transactions.banner.invoice.store');
        Route::get('/admin/transaction/delete/{id}',
          [TransactionController::class, 'delete'])->name('admin-transaction-delete');
      });

      Route::get('/expenses', [ExpenseController::class,'index'])->name('admin.expenses.index');
      Route::get('/expenses/create', [ExpenseController::class,'create'])->name('admin.expenses.create');
      Route::post('/expenses', [ExpenseController::class,'store'])->name('admin.expenses.store');
      Route::get('/expenses/{id}/edit', [ExpenseController::class,'edit'])->name('admin.expenses.edit');
      Route::post('/expenses/{id}', [ExpenseController::class,'update'])->name('admin.expenses.update');
      Route::get('/expenses/{id}/delete', [ExpenseController::class,'destroy'])->name('admin.expenses.delete');

      Route::group(['middleware'=>'permissions:Manage Blog'],function(){
        Route::get('/blog/datatables', [BlogController::class,'datatables'])->name('admin.blog.datatables'); //JSON REQUEST
        Route::get('/blog', [BlogController::class,'index'])->name('admin.blog.index');
        Route::get('/blog/create', [BlogController::class,'create'])->name('admin.blog.create');
        Route::post('/blog/create', [BlogController::class,'store'])->name('admin.blog.store');
        Route::get('/blog/edit/{id}', [BlogController::class,'edit'])->name('admin.blog.edit');
        Route::post('/blog/edit/{id}', [BlogController::class,'update'])->name('admin.blog.update');
        Route::get('/blog/delete/{id}', [BlogController::class,'destroy'])->name('admin.blog.delete');
        Route::get('/blog/status/{id}', [BlogController::class,'statusToggle'])->name('admin.blog.status');

        Route::get('/blog/category/datatables', [BlogCategoryController::class,'datatables'])->name('admin.cblog.datatables'); //JSON REQUEST
        Route::get('/blog/category', [BlogCategoryController::class,'index'])->name('admin.cblog.index');
        Route::get('/blog/category/create', [BlogCategoryController::class,'create'])->name('admin.cblog.create');
        Route::post('/blog/category/create', [BlogCategoryController::class,'store'])->name('admin.cblog.store');
        Route::get('/blog/category/edit/{id}', [BlogCategoryController::class,'edit'])->name('admin.cblog.edit');
        Route::post('/blog/category/edit/{id}', [BlogCategoryController::class,'update'])->name('admin.cblog.update');
        Route::get('/blog/category/delete/{id}', [BlogCategoryController::class,'destroy'])->name('admin.cblog.delete');
      });

      Route::get('/taxes/datatables', [TaxController::class,'datatables'])->name('admin.taxes.datatables');
      Route::get('/taxes', [TaxController::class,'index'])->name('admin.taxes.index');
      Route::get('/taxes/create', [TaxController::class,'create'])->name('admin.taxes.create');
      Route::get('/taxes/edit/{id}', [TaxController::class,'edit'])->name('admin.taxes.edit');
      Route::post('/taxes/create', [TaxController::class,'store'])->name('admin.taxes.store');
      Route::get('/taxes/status/{id1}/{id2}', [TaxController::class,'status'])->name('admin.taxes.status');
      Route::post('/taxes/update/{id}', [TaxController::class,'update'])->name('admin.taxes.update');
      Route::get('/taxes/delete/{id}', [TaxController::class,'destroy'])->name('admin.taxes.delete');

      Route::group(['middleware'=>'permissions:General Settings'],function(){
        Route::get('/general-settings/logo', [GeneralSettingController::class,'logo'])->name('admin.gs.logo');
        Route::get('/general-settings/favicon', [GeneralSettingController::class,'fav'])->name('admin.gs.fav');
        Route::get('/general-settings/loader', [GeneralSettingController::class,'load'])->name('admin.gs.load');
        Route::post('/general-settings/update/all', [GeneralSettingController::class,'generalupdate'])->name('admin.gs.update');
        Route::get('/general-settings/contents', [GeneralSettingController::class,'contents'])->name('admin.gs.contents');
        Route::get('/general-settings/moneytransfer', [GeneralSettingController::class,'moneytransfer'])->name('admin.gs.moneytransfer');
        Route::get('/general-settings/theme', [GeneralSettingController::class,'theme'])->name('admin.gs.theme');
        Route::get('/general-settings/custom-css', [GeneralSettingController::class,'customcss'])->name('admin.gs.customcss');
        Route::post('/general-settings/custom-css', [GeneralSettingController::class,'customcssSubmit'])->name('admin.gs.customcss.submit');

        Route::get('/general-settings/breadcumb', [GeneralSettingController::class,'breadcumb'])->name('admin.gs.breadcumb');
        Route::get('/general-settings/status/{field}/{status}', [GeneralSettingController::class,'status'])->name('admin.gs.status');
        Route::get('/general-settings/footer', [GeneralSettingController::class,'footer'])->name('admin.gs.footer');
        Route::get('/general-settings/affilate', [GeneralSettingController::class,'affilate'])->name('admin.gs.affilate');
        Route::get('/general-settings/error-banner', [GeneralSettingController::class,'errorbanner'])->name('admin.gs.error.banner');
        Route::get('/general-settings/popup', [GeneralSettingController::class,'popup'])->name('admin.gs.popup');
        Route::get('/general-settings/manage-cookie', [GeneralSettingController::class,'cookie'])->name('admin.gs.cookie');
        Route::get('/general-settings/maintenance', [GeneralSettingController::class,'maintain'])->name('admin.gs.maintenance'); 
        Route::get('/twilio-sms-settings', [GeneralSettingController::class,'twilio'])->name('admin.gs.twilio');
        Route::get('/nexmo-sms-settings', [GeneralSettingController::class,'nexmo'])->name('admin.gs.nexmo');
      });

      Route::group(['middleware'=>'permissions:Home Page Manage'],function(){

        Route::get('/account/process/datatables', [AccountProcessController::class,'datatables'])->name('admin.account.process.datatables');
        Route::get('/account/process', [AccountProcessController::class,'index'])->name('admin.account.process.index');
        Route::get('/account/process/create', [AccountProcessController::class,'create'])->name('admin.account.process.create');
        Route::get('/account/process/edit/{id}', [AccountProcessController::class,'edit'])->name('admin.account.process.edit');
        Route::post('/account/process/store', [AccountProcessController::class,'store'])->name('admin.account.process.store');
        Route::post('/account/process/edit/{id}', [AccountProcessController::class,'update'])->name('admin.account.process.update');
        Route::get('/account/process/delete/{id}', [AccountProcessController::class,'destroy'])->name('admin.account.process.delete');

        Route::get('/mission/datatables', [MissionController::class,'datatables'])->name('admin.mission.datatables');
        Route::get('/mission', [MissionController::class,'index'])->name('admin.mission.index');
        Route::get('/mission/create', [MissionController::class,'create'])->name('admin.mission.create');
        Route::get('/mission/edit/{id}', [MissionController::class,'edit'])->name('admin.mission.edit');
        Route::post('/mission/store', [MissionController::class,'store'])->name('admin.mission.store');
        Route::post('/mission/edit/{id}', [MissionController::class,'update'])->name('admin.mission.update');
        Route::get('/mission/delete/{id}', [MissionController::class,'destroy'])->name('admin.mission.delete');

        Route::get('/review/datatables', [ReviewController::class,'datatables'])->name('admin.review.datatables');
        Route::get('/review', [ReviewController::class,'index'])->name('admin.review.index');
        Route::get('/review/create', [ReviewController::class,'create'])->name('admin.review.create');
        Route::get('/review/edit/{id}', [ReviewController::class,'edit'])->name('admin.review.edit');
        Route::post('/review/store', [ReviewController::class,'store'])->name('admin.review.store');
        Route::post('/review/edit/{id}', [ReviewController::class,'update'])->name('admin.review.update');
        Route::get('/review/delete/{id}', [ReviewController::class,'destroy'])->name('admin.review.delete');

        Route::get('/partner/datatables', [PartnerController::class,'datatables'])->name('admin.partner.datatables');
        Route::get('/partner', [PartnerController::class,'index'])->name('admin.partner.index');
        Route::get('/partner/create', [PartnerController::class,'create'])->name('admin.partner.create');
        Route::get('/partner/edit/{id}', [PartnerController::class,'edit'])->name('admin.partner.edit');
        Route::post('/partner/store', [PartnerController::class,'store'])->name('admin.partner.store');
        Route::post('/partner/edit/{id}', [PartnerController::class,'update'])->name('admin.partner.update');
        Route::get('/partner/delete/{id}', [PartnerController::class,'destroy'])->name('admin.partner.delete');

        Route::get('/faq/datatables', [FaqController::class,'datatables'])->name('admin.faq.datatables');
        Route::get('/faq', [FaqController::class,'index'])->name('admin.faq.index');
        Route::get('/faq/create', [FaqController::class,'create'])->name('admin.faq.create');
        Route::get('/faq/edit/{id}', [FaqController::class,'edit'])->name('admin.faq.edit');
        Route::get('/faq/delete/{id}', [FaqController::class,'destroy'])->name('admin.faq.delete');
        Route::post('/faq/update/{id}', [FaqController::class,'update'])->name('admin.faq.update');
        Route::post('/faq/create', [FaqController::class,'store'])->name('admin.faq.store');

        Route::get('/page-settings/hero', [PageSettingController::class,'hero'])->name('admin.ps.hero');
        Route::get('/homepage/customization', [PageSettingController::class,'customization'])->name('admin.ps.customization');
        Route::post('/homepage/customization/update', [PageSettingController::class,'customizationUpdate'])->name('admin.ps.customization.update');
        Route::get('/page-settings/about', [PageSettingController::class,'about'])->name('admin.ps.about');
        Route::get('/page-settings/listing-submit', [PageSettingController::class,'listing'])->name('admin.ps.listing');
        Route::get('/page-settings/download-apps', [PageSettingController::class,'download'])->name('admin.ps.download');
        Route::get('/page-settings/call-to-action', [PageSettingController::class,'calltoaction'])->name('admin.ps.call');
        Route::get('/page-settings/section/heading', [PageSettingController::class,'sectionHeading'])->name('admin.ps.heading');
        Route::post('/page-settings/contact/update', [PageSettingController::class,'contactupdate'])->name('admin.ps.contactupdate');
        Route::post('/page-settings/update/all', [PageSettingController::class,'update'])->name('admin.ps.update');
      });

      Route::group(['middleware'=>'permissions:Email Settings'],function(){
        Route::get('/email-templates/datatables', [EmailController::class,'datatables'])->name('admin.mail.datatables');
        Route::get('/email-templates', [EmailController::class,'index'])->name('admin.mail.index');
        Route::get('/email-templates/{id}', [EmailController::class,'edit'])->name('admin.mail.edit');
        Route::post('/email-templates/{id}', [EmailController::class,'update'])->name('admin.mail.update');
        Route::get('/email-config', [EmailController::class,'config'])->name('admin.mail.config');
        Route::get('/groupemail', [EmailController::class,'groupemail'])->name('admin.group.show');
        Route::post('/groupemailpost', [EmailController::class,'groupemailpost'])->name('admin.group.submit');
      });

      Route::get('/sms-templates/datatables', [SMSController::class,'datatables'])->name('admin.sms.datatables');
      Route::get('/sms-templates', [SMSController::class,'index'])->name('admin.sms.index');
      Route::get('/sms-templates/{id}', [SMSController::class,'edit'])->name('admin.sms.edit');
      Route::post('/sms-templates/{id}', [SMSController::class,'update'])->name('admin.sms.update');


      Route::group(['middleware'=>'permissions:Message'],function(){
        Route::post('/send/message', [MessageController::class,'usercontact'])->name('admin.send.message');
        Route::get('/user/ticket', [MessageController::class,'index'])->name('admin.user.message');
        Route::get('/messages/datatables/', [MessageController::class,'datatables'])->name('admin.message.datatables');
        Route::get('/message/{id}', [MessageController::class,'message'])->name('admin.message.show');
        Route::get('/message/{id}/delete', [MessageController::class,'messagedelete'])->name('admin.message.delete');
        Route::post('/message/post', [MessageController::class,'postmessage'])->name('admin.message.store');
        Route::get('/message/load/{id}', [MessageController::class,'messageshow'])->name('admin-message-load');
      });


      Route::group(['middleware'=>'permissions:Payment Settings'],function(){
        Route::post('/general-settings/update/all',[ GeneralSettingController::class,'generalupdate'])->name('admin.gs.update');
        Route::get('/paymentgateway/datatables', [PaymentGatewayController::class,'datatables'])->name('admin.payment.datatables');
        Route::get('/paymentgateway', [PaymentGatewayController::class,'index'])->name('admin.payment.index');
        Route::get('/paymentgateway/create', [PaymentGatewayController::class,'create'])->name('admin.payment.create');
        Route::post('/paymentgateway/create', [PaymentGatewayController::class,'store'])->name('admin.payment.store');
        Route::get('/paymentgateway/edit/{id}', [PaymentGatewayController::class,'edit'])->name('admin.payment.edit');
        Route::post('/paymentgateway/update/{id}', [PaymentGatewayController::class,'update'])->name('admin.payment.update');
        Route::get('/paymentgateway/delete/{id}', [PaymentGatewayController::class,'destroy'])->name('admin.payment.delete');
        Route::get('/paymentgateway/status/{id1}/{id2}', [PaymentGatewayController::class,'status'])->name('admin.payment.status');

        Route::get('/general-settings/currency/{status}', [GeneralSettingController::class,'currency'])->name('admin.gs.iscurrency');
        Route::get('/currency/datatables', [CurrencyController::class,'datatables'])->name('admin.currency.datatables');
        Route::get('/currency',[ CurrencyController::class,'index'])->name('admin.currency.index');
        Route::get('/currency/create', [CurrencyController::class,'create'])->name('admin.currency.create');
        Route::post('/currency/create', [CurrencyController::class,'store'])->name('admin.currency.store');
        Route::get('/currency/edit/{id}', [CurrencyController::class,'edit'])->name('admin.currency.edit');
        Route::post('/currency/update/{id}', [CurrencyController::class,'update'])->name('admin.currency.update');
        Route::get('/currency/delete/{id}', [CurrencyController::class,'destroy'])->name('admin.currency.delete');
        Route::get('/currency/status/{id1}/{id2}', [CurrencyController::class,'status'])->name('admin.currency.status');
      });

      Route::group(['middleware'=>'permissions:Manage Roles'],function(){
        Route::get('/role/datatables', [RoleController::class,'datatables'])->name('admin.role.datatables');
        Route::get('/role', [RoleController::class,'index'])->name('admin.role.index');
        Route::get('/role/create', [RoleController::class,'create'])->name('admin.role.create');
        Route::post('/role/create', [RoleController::class,'store'])->name('admin.role.store');
        Route::get('/role/edit/{id}', [RoleController::class,'edit'])->name('admin.role.edit');
        Route::post('/role/edit/{id}', [RoleController::class,'update'])->name('admin.role.update');
        Route::get('/role/delete/{id}', [RoleController::class,'destroy'])->name('admin.role.delete');
      });

      Route::group(['middleware'=>'permissions:Manage Staff'],function(){
        Route::get('/staff/datatables', [StaffController::class,'datatables'])->name('admin.staff.datatables');
        Route::get('/staff', [StaffController::class,'index'])->name('admin.staff.index');
        Route::get('/staff/create', [StaffController::class,'create'])->name('admin.staff.create');
        Route::post('/staff/create', [StaffController::class,'store'])->name('admin.staff.store');
        Route::get('/staff/edit/{id}', [StaffController::class,'edit'])->name('admin.staff.edit');
        Route::post('/staff/update/{id}', [StaffController::class,'update'])->name('admin.staff.update');
        Route::get('/staff/delete/{id}', [StaffController::class,'destroy'])->name('admin.staff.delete');
      });

      Route::group(['middleware'=>'permissions:Manage KYC Form'],function(){
        Route::get('/manage-kyc/datatables', [KycManageController::class,'datatables'])->name('admin.manage.kyc.datatables');
        Route::get('/manage-kyc-form',[KycManageController::class,'index'])->name('admin.manage.kyc');
        Route::get('/manage-kyc-module',[KycManageController::class,'module'])->name('admin.manage.module');
        Route::post('/manage-kyc-module/update',[KycManageController::class,'moduleUpdate'])->name('admin.manage.module.update');

        Route::get('/manage-kyc-form/{user}',[KycManageController::class,'userKycForm'])->name('admin.manage.kyc.user');
        Route::post('/manage-kyc-form/{user}',[KycManageController::class,'kycForm']);
        Route::post('/kyc-form/update',[KycManageController::class,'kycFormUpdate'])->name('admin.kyc.form.update');
        Route::post('/kyc-form/delete',[KycManageController::class,'deletedField'])->name('admin.kyc.form.delete');
        Route::get('/kyc-info/{user}',[KycManageController::class,'kycInfo'])->name('admin.kyc.info');
        Route::get('/kyc-info/user/{id}',[KycManageController::class,'kycDetails'])->name('admin.kyc.details');
        Route::get('/users/kyc/{id1}/{id2}', [KycManageController::class,'kyc'])->name('admin.user.kyc');
      });

      Route::group(['middleware'=>'permissions:Language Manage'],function(){
        Route::get('/general-settings/language/{status}',[ GeneralSettingController::class,'language'])->name('admin.gs.islanguage');
        Route::get('/languages/datatables', [LanguageController::class,'datatables'])->name('admin.lang.datatables');
        Route::get('/languages', [LanguageController::class,'index'])->name('admin.lang.index');
        Route::get('/languages/create', [LanguageController::class,'create'])->name('admin.lang.create');
        Route::get('/languages/edit/{id}', [LanguageController::class,'edit'])->name('admin.lang.edit');
        Route::post('/languages/create', [LanguageController::class,'store'])->name('admin.lang.store');
        Route::post('/languages/edit/{id}', [LanguageController::class,'update'])->name('admin.lang.update');
        Route::get('/languages/status/{id1}/{id2}', [LanguageController::class,'status'])->name('admin.lang.st');
        Route::get('/languages/delete/{id}',[ LanguageController::class,'destroy'])->name('admin.lang.delete');

        Route::get('/adminlanguages/datatables', [AdminLanguageController::class,'datatables'])->name('admin.tlang.datatables');
        Route::get('/adminlanguages', [AdminLanguageController::class,'index'])->name('admin.tlang.index');
        Route::get('/adminlanguages/create', [AdminLanguageController::class,'create'])->name('admin.tlang.create');
        Route::get('/adminlanguages/edit/{id}', [AdminLanguageController::class,'edit'])->name('admin.tlang.edit');
        Route::post('/adminlanguages/create', [AdminLanguageController::class,'store'])->name('admin.tlang.store');
        Route::post('/adminlanguages/edit/{id}', [AdminLanguageController::class,'update'])->name('admin.tlang.update');
        Route::get('/adminlanguages/status/{id1}/{id2}', [AdminLanguageController::class,'status'])->name('admin.tlang.st');
        Route::get('/adminlanguages/delete/{id}', [AdminLanguageController::class,'destroy'])->name('admin.tlang.delete');
      });

      Route::group(['middleware'=>'permissions:Fonts'],function(){
        Route::get('/fonts/datatables', [FontController::class,'datatables'])->name('admin.font.datatables');
        Route::get('/fonts', [FontController::class,'index'])->name('admin.font.index');
        Route::get('/font/create', [FontController::class,'create'])->name('admin.font.create');
        Route::post('/font/store', [FontController::class,'store'])->name('admin.font.store');
        Route::get('/font/edit/{id}', [FontController::class,'edit'])->name('admin.font.edit');
        Route::post('/font/update/{id}', [FontController::class,'update'])->name('admin.font.update');
        Route::get('/font/status/{id1}/{id2}', [FontController::class,'status'])->name('admin.font.status');
        Route::get('/font/delete/{id}', [FontController::class,'destroy'])->name('admin.font.delete');
      });

      Route::group(['middleware'=>'permissions:Menu Page Settings'],function(){
        Route::get('/page-settings/contact', [PageSettingController::class,'contact'])->name('admin.ps.contact');

        Route::get('/page/datatables', [PageController::class,'datatables'])->name('admin.page.datatables'); //JSON REQUEST
        Route::get('/page', [PageController::class,'index'])->name('admin.page.index');
        Route::get('/page/create', [PageController::class,'create'])->name('admin.page.create');
        Route::post('/page/create', [PageController::class,'store'])->name('admin.page.store');
        Route::get('/page/edit/{id}', [PageController::class,'edit'])->name('admin.page.edit');
        Route::post('/page/update/{id}', [PageController::class,'update'])->name('admin.page.update');
        Route::get('/page/delete/{id}', [PageController::class,'destroy'])->name('admin.page.delete');
        Route::get('/page/status/{id1}/{id2}', [PageController::class,'status'])->name('admin.page.status');
        
        Route::get('/custom/pages', [PageController::class,'customPageIndex'])->name('admin.custom.page.index');
        Route::get('/custom/page/{id?}', [PageController::class,'customPageEdit'])->name('admin.custom.page.edit');
        Route::post('/custom/page/{id?}', [PageController::class,'customPageUpdate'])->name('admin.custom.page.update');
        Route::get('/login-registration/page', [PageSettingController::class,'loginpage'])->name('admin.ps.login');

      });

      Route::group(['middleware'=>'permissions:Seo Tools'],function(){
        Route::get('/seotools/analytics', [SeoToolController::class,'analytics'])->name('admin.seotool.analytics');
        Route::post('/seotools/analytics/update', [SeoToolController::class,'analyticsupdate'])->name('admin.seotool.analytics.update');
        Route::get('/seotools/keywords', [SeoToolController::class,'keywords'])->name('admin.seotool.keywords');
        Route::post('/seotools/keywords/update', [SeoToolController::class,'keywordsupdate'])->name('admin.seotool.keywords.update');
        Route::get('/products/popular/{id}',[SeoToolController::class,'popular'])->name('admin.prod.popular');

        Route::get('/social-links/datatables', [SocialLinkController::class,'datatables'])->name('admin.social.links.datatables'); //JSON REQUEST
        Route::get('/social-links', [SocialLinkController::class,'index'])->name('admin.social.links.index');
        Route::get('/social-links/create', [SocialLinkController::class,'create'])->name('admin.social.links.create');
        Route::post('/social-links/create', [SocialLinkController::class,'store'])->name('admin.social.links.store');
        Route::get('/social-links/edit/{id}', [SocialLinkController::class,'edit'])->name('admin.social.links.edit');
        Route::post('/social-links/update/{id}', [SocialLinkController::class,'update'])->name('admin.social.links.update');
        Route::get('/social-links/delete/{id}', [SocialLinkController::class,'destroy'])->name('admin.social.links.delete');
        Route::get('/social-links/status/{id1}/{id2}', [SocialLinkController::class,'status'])->name('admin.social.links.status');
      });

      Route::group(['middleware'=>'permissions:Sitemaps'],function(){
        Route::get('/sitemap/datatables', [SitemapController::class,'datatables'])->name('admin.sitemap.datatables');
        Route::get('/sitemap',[SitemapController::class,'index'])->name('admin.sitemap.index');
        Route::get('/sitemap/create',[SitemapController::class,'create'])->name('admin.sitemap.create');
        Route::post('/sitemap/store', [SitemapController::class,'store'])->name('admin.sitemap.store');
        Route::get('/sitemap/{id}/update',[SitemapController::class,'update'])->name('admin.sitemap.update');
        Route::get('/sitemap/{id}/delete', [SitemapController::class,'delete'])->name('admin.sitemap.delete');
        Route::post('/sitemap/download', [SitemapController::class,'download'])->name('admin.sitemap.download');
      });

      Route::group(['middleware'=>'permissions:Subscribers'],function(){
        Route::get('/subscribers/datatables', [SubscriberController::class,'datatables'])->name('admin.subs.datatables'); //JSON REQUEST
        Route::get('/subscribers', [SubscriberController::class,'index'])->name('admin.subs.index');
        Route::get('/subscribers/download', [SubscriberController::class,'download'])->name('admin.subs.download');
      });
      

      Route::group(['middleware'=>'permissions:Social Setting'],function(){
        Route::get('/social', [SocialSettingController::class,'index'])->name('admin.social.index');
        Route::post('/social/update', [SocialSettingController::class,'socialupdate'])->name('admin.social.update');
        Route::post('/social/update/all', [SocialSettingController::class,'socialupdateall'])->name('admin.social.update.all');
        Route::get('/social/facebook', [SocialSettingController::class,'facebook'])->name('admin.social.facebook');
        Route::get('/social/google', [SocialSettingController::class,'google'])->name('admin.social.google');
        Route::get('/social/facebook/{status}', [SocialSettingController::class,'facebookup'])->name('admin.social.facebookup');
        Route::get('/social/google/{status}', [SocialSettingController::class,'googleup'])->name('admin.social.googleup');
      });

      Route::get('/check/movescript', [DashboardController::class,'movescript'])->name('admin-move-script');
      Route::get('/generate/backup', [DashboardController::class,'generate_bkup'])->name('admin-generate-backup');
      Route::get('/activation', [DashboardController::class,'activation'])->name('admin-activation-form');
      Route::post('/activation', [DashboardController::class,'activation_submit'])->name('admin-activate-purchase');
      Route::get('/clear/backup', [DashboardController::class,'clear_bkup'])->name('admin-clear-backup');
      
      
      // video alert popup
      Route::get('/alert-popup/', [AlertPopupController::class,'index'])->name('admin.alertpopup.index');
      Route::get('/alert-popup/create', [AlertPopupController::class,'create'])->name('admin.alertpopup.create');
      Route::post('/alert-popup/store', [AlertPopupController::class,'store'])->name('admin.alertpopup.store');
      Route::get('/alert-popup/edit/{id}', [AlertPopupController::class,'edit'])->name('admin.alertpopup.edit');
      Route::post('/alert-popup/update/{id}', [AlertPopupController::class,'update'])->name('admin.alertpopup.update');
      Route::get('/alert-popup/delete/{id}', [AlertPopupController::class,'delete'])->name('admin.alertpopup.delete');




    });
