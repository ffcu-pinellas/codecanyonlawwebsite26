<?php

use App\Actions\Fortify\UpdateUserPassword;
use App\Http\Controllers\AdminControllers\AdminController;
use App\Http\Controllers\AdminControllers\AdminBlogCategoryController;
use App\Http\Controllers\AdminControllers\AdminBlogController;
use App\Http\Controllers\AdminControllers\AdminCaseStudyController;
use App\Http\Controllers\AdminControllers\AdminServiceController;
use App\Http\Controllers\AdminControllers\AdminSliderController;
use App\Http\Controllers\AdminControllers\AdminPartnerController;
use App\Http\Controllers\AdminControllers\AdminTagController;
use App\Http\Controllers\AdminControllers\AppSettingsController;
use App\Http\Controllers\AdminControllers\PageSettingsController;
use App\Http\Controllers\AdminControllers\TestimonialController;
use App\Http\Controllers\AdminControllers\AdminAttorneyController;
use App\Http\Controllers\AdminControllers\AdminDesignationController;
use App\Http\Controllers\AdminControllers\AdminFaqController;
use App\Http\Controllers\AdminControllers\DynamicPageController;
use App\Http\Controllers\AdminControllers\UserController;
use App\Http\Controllers\ClientViewControllers\ClientViewController;
use App\Http\Controllers\GuestViewControllers\GuestViewController;
use App\Http\Controllers\MenuSettings\MenuCategoryController;
use App\Http\Controllers\MenuSettings\MenuItemController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/setup', function (){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo 'done';
    return redirect()->route('home');
});

Route::get('/login', [GuestViewController::class, 'loginRedirect'])->name('login');
Route::get('/forgot-password', [GuestViewController::class, 'forgetPassword'])->name('password.request');
Route::get('/reset-password/{token}', [GuestViewController::class, 'resetPassword'])->name('password.reset');

Route::get('admin/login', [GuestViewController::class, 'adminLogin'])->name('admin.login');
Route::get('register', [GuestViewController::class, 'userRegister'])->name('register');

Route::get('/', [GuestViewController::class, 'index'])->name('home');

// For contact
Route::get('/contacts', [GuestViewController::class, 'viewContactPage'])->name('view-contact-page');
Route::post('/contacts', [GuestViewController::class, 'storeContactMessage'])->name('store-contact');
// For About
Route::get('/about', [GuestViewController::class, 'viewAboutPage'])->name('view-about-page');
//for services
Route::get('/services', [GuestViewController::class, 'viewAllServicesPage'])->name('view-all-services-page');
Route::get('/service/{id}', [GuestViewController::class, 'viewSingleServicePage'])->name('view-single-service-page');
//for cases
Route::get('/cases', [GuestViewController::class, 'viewAllCasesPage'])->name('view-all-cases-page');
Route::get('/case/{id}', [GuestViewController::class, 'viewSingleCasePage'])->name('view-single-cases-page');
//for blogs
Route::get('/blogs', [GuestViewController::class, 'viewAllBlogsPage'])->name('view-all-blogs-page');
Route::get('/blog/{id}', [GuestViewController::class, 'viewSingleBlogPage'])->name('view-single-blog-page');
Route::get('blog-category/{id}', [GuestViewController::class, 'blogCategory'])->name('blog-category');
Route::get('blog-tag/{id}', [GuestViewController::class, 'blogTag'])->name('blog-tag');
Route::get('search-blog', [GuestViewController::class, 'searchBlog'])->name('search-blog');

// For Appointment
Route::post('/appointment', [GuestViewController::class, 'storeAppointment'])->name('store-appointment');
// for FAQ
Route::get('/faq', [GuestViewController::class, 'viewFaqPage'])->name('view-faq-page');

// for Team Details
Route::get('/teams', [GuestViewController::class, 'viewAllTeamsPage'])->name('view-all-teams-page');
Route::get('/attorney/{id}', [GuestViewController::class, 'viewAttorney'])->name('view-attorney');

// Dynamic Pages
Route::get('pages/{slug}', [GuestViewController::class, 'dynamicPage'])->name('pages');
// download file
Route::get('/download-chatting-file/{message}', [GuestViewController::class, 'downloadMessageFile'])->name('download.chatting-file');


//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//    return view('dashboard');
//})->name('dashboard');

Route::prefix('/client')->middleware(['auth:sanctum', 'verified', 'role:client'])->as('client.')->group(function (){
   Route::get('/dashboard', [ClientViewController::class, 'dashboard'])->name('dashboard');
    // profile routs
    Route::get('/profile', [ClientViewController::class, 'profile'])->name('profile');
    Route::post('/profile', [ClientViewController::class, 'profileUpdate'])->name('profile-update');
    Route::post('/profile-info', [ClientViewController::class, 'profileInfoUpdate'])->name('profile-info');
    Route::post('/password-update', [UpdateUserPassword::class, 'updateAdminPassword'])->name('password-update');
    // financial relief
    Route::get('/financial-relief', [ClientViewController::class, 'createReliefRequest'])->name('financial-relief');
    Route::post('/financial-relief', [ClientViewController::class, 'storeReliefRequest']);
    // chat
    Route::prefix('conversation')->as('conversation.')->group(function (){
        Route::get('/', [ClientViewController::class, 'getConversation'])->name('index');
        Route::get('/search-attorney', [ClientViewController::class, 'searchAttorney'])->name('search-attorney');
        Route::get('/start-chat/{user}', [ClientViewController::class, 'createConversation'])->name('start-chat');
        Route::get('get-conversation/{slug}', [ClientViewController::class, 'getMessage'])->name('get-conversation');
        Route::post('send-message/{slug}', [ClientViewController::class, 'sendMessage'])->name('send-message');
    });
});



Route::group(['prefix' => 'admin', 'as'=>'admin.', 'middleware' => ['auth:sanctum','verified']], function () {
    // dashboard route
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    // profile routs
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/profile', [AdminController::class, 'profileUpdate'])->name('profile-update');
    Route::post('/profile-info', [AdminController::class, 'profileInfoUpdate'])->name('profile-info');
    Route::post('/password-update', [UpdateUserPassword::class, 'updateAdminPassword'])->name('password-update');
    Route::post('/delete-account', [AdminController::class, 'adminDelete'])->name('delete-account');

    // Contact us
    Route::middleware(['permission:contact'])->group(function (){
        Route::get('/contact-us', [AdminController::class, 'getContactMessage'])->name('contact.index');
        Route::get('/contact-view/{id}', [AdminController::class, 'viewContactMessage'])->name('contact.view');
        Route::delete('/contact/{id}', [AdminController::class, 'destroyContactMessage'])->name('contact.destroy');
    });

    //user, role & permission
    Route::prefix('user')->as('user.')->middleware(['role:admin'])->group(function (){
        //role
        Route::prefix('role')->as('role.')->group(function (){
            Route::get('/', [UserController::class, 'roleIndex'])->name('index');
            Route::get('/create', [UserController::class, 'roleCreate'])->name('create');
            Route::post('/', [UserController::class, 'roleStore'])->name('store');
            Route::get('/edit/{role}', [UserController::class, 'roleEdit'])->name('edit');
            Route::post('/edit/{role}', [UserController::class, 'getRolePermission']);
            Route::put('/update/{role}', [UserController::class, 'roleUpdate'])->name('update');
            Route::delete('/destroy/{role}', [UserController::class, 'roleDestroy'])->name('destroy');
        });
        //client
        Route::prefix('client')->as('client.')->group(function (){
            Route::get('/', [UserController::class, 'clientIndex'])->name('index');
        });

        Route::get('/', [UserController::class, 'userIndex'])->name('index');
        Route::get('/save', [UserController::class, 'userIndexSave'])->name('save');
    });

    // Chating
    Route::prefix('conversation')->as('conversation.')->group(function (){
        Route::get('/', [AdminController::class, 'conversationIndex'])->name('index');
        Route::get('get-conversation/{slug}', [AdminController::class, 'getMessage'])->name('get-conversation');
        Route::post('send-message/{slug}', [AdminController::class, 'sendMessage'])->name('send-message');
    });

    // Get Appointment
    Route::middleware(['permission:get_appointment'])->group(function (){
        Route::get('/appointments', [AdminController::class, 'getAppointment'])->name('appointment.index');
        Route::get('/appointment-view/{id}', [AdminController::class, 'viewAppointment'])->name('appointment.view');
        Route::delete('/appointment/{id}', [AdminController::class, 'destroyAppointment'])->name('appointment.destroy');
    });

    // Get Financial Relief
    Route::prefix('financial-relief')->as('financial-relief.')->group(function (){
        Route::get('/', [AdminController::class, 'getReliefRequests'])->name('index');
        Route::get('/{relief}', [AdminController::class, 'viewReliefRequest'])->name('view');
        Route::delete('/{relief}', [AdminController::class, 'destroyReliefRequest'])->name('destroy');
    });

    // app settings
    Route::prefix('settings')->as('settings.')->middleware(['permission:settings'])->group(function () {

        Route::get('general', [AppSettingsController::class, 'getGeneralSettings'])->name('general');
        Route::post('general', [AppSettingsController::class, 'saveGeneralSettings'])->name('general-save');

        //Top header
        Route::get('/top-header', [AppSettingsController::class, 'topHeaderIndex'])->name('topHeader.index');
        Route::post('/top-header-store', [AppSettingsController::class, 'topHeaderStore'])->name('topHeader.store');

        //footer
        Route::get('/footer', [AppSettingsController::class, 'footerIndex'])->name('footer.index');
        Route::post('/store-footer', [AppSettingsController::class, 'storeFooter'])->name('footer.store');

        // Logo Favicon
        Route::get('logo-favicon', [AppSettingsController::class, 'getLogoFaviconSettings'])->name('logo-favicon');
        Route::post('logo-favicon', [AppSettingsController::class, 'saveLogoFaviconSettings'])->name('logo-favicon-save');

        // Seo Setting
        Route::get('seo', [AppSettingsController::class, 'getSeoSettings'])->name('seo');
        Route::post('seo', [AppSettingsController::class, 'saveSeoSettings'])->name('seo-save');

        // Smtp Settings
        Route::get('smtp', [AppSettingsController::class, 'getSmtpSettings'])->name('smtp');
        Route::post('smtp', [AppSettingsController::class, 'saveSmtpSettings'])->name('smtp-save');

        // Insert Header-footer Settings
        Route::get('insert-header-footer', [AppSettingsController::class, 'getInsertHeaderFooterSettings'])->name('insert-header-footer');
        Route::post('insert-header-footer', [AppSettingsController::class, 'saveInsertHeaderFooterSettings'])->name('insert-header-footer-save');
       //social media
        Route::get('social-media', [AppSettingsController::class, 'socialMediaSettings'])->name('social-media');
        Route::post('social-media-save', [AppSettingsController::class, 'saveSocialMediaSettings'])->name('social-media-save');

    });

    // menu settings
    Route::prefix('settings/menu')->as('menu.')->middleware(['permission:settings'])->group(function (){
        Route::resource('/category', MenuCategoryController::class, ['only' => ['index', 'store', 'edit', 'update', 'destroy']]);
        Route::resource('/item', MenuItemController::class, ['only' => ['index', 'create', 'store']]);
    });

    //page settings
    Route::prefix('page-settings')->as('page-settings.')->middleware(['permission:page_settings'])->group(function (){
        // home page
        Route::get('/home', [PageSettingsController::class, 'homeIndex'])->name('home-page.index');
        //contact page
        Route::get('/contact-page', [PageSettingsController::class, 'contactIndex'])->name('contact-page.index');
        //about page
        Route::get('/about-page', [PageSettingsController::class, 'aboutIndex'])->name('about-page.index');
        //services page
        Route::get('/services-page', [PageSettingsController::class, 'servicesIndex'])->name('services-page.index');
        //cases page
        Route::get('/cases-page', [PageSettingsController::class, 'casesIndex'])->name('cases-page.index');
        //blogs page
        Route::get('/blogs-page', [PageSettingsController::class, 'blogsIndex'])->name('blogs-page.index');
        //teams page
        Route::get('/teams-page', [PageSettingsController::class, 'teamsIndex'])->name('teams-page.index');
        //faq page
        Route::get('/faq-page', [PageSettingsController::class, 'faqIndex'])->name('faq-page.index');
        //client dashboard page
        Route::get('/client-dashboard-page', [PageSettingsController::class, 'clientDashboardPageIndex'])->name('client-dashboard-page.index');
        // page seo
        Route::post('seo', [PageSettingsController::class, 'seoSettings'])->name('page.seo');
        //get page input fields
        Route::get('/input-fields', [PageSettingsController::class, 'inputFields'])->name('input-fields');
        //store any page without image
        Route::post('/store-page', [PageSettingsController::class, 'store'])->name('contact-page.store');
        // store page with image
        Route::post('/store-page-img', [PageSettingsController::class, 'storeWithImg'])->name('contact-page.store-img');
    });

    // For testimonial
    Route::prefix('testimonial')->as('testimonial.')->middleware(['permission:testimonial'])->group(function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('index');
        Route::get('/form', [TestimonialController::class, 'form'])->name('form');
        Route::post('/store', [TestimonialController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TestimonialController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [TestimonialController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [TestimonialController::class, 'delete'])->name('delete');
    });

    // For slider
    Route::resource('/slider',AdminSliderController::class)->middleware(['permission:slider_settings']);

    // For Service
    Route::resource('/service',AdminServiceController::class)->middleware(['permission:services']);

    // For partner
    Route::resource('/partner',AdminPartnerController::class)->middleware(['permission:partner']);

    // For designation
    Route::resource('/designation',AdminDesignationController::class)->middleware(['permission:designation']);

    // For attorney
    Route::resource('/attorney',AdminAttorneyController::class)->middleware(['permission:attorney']);

    // For Faq
    Route::resource('/faq',AdminFaqController::class)->middleware(['permission:faq']);

    // For Case Study
    Route::resource('/casestudy',AdminCaseStudyController::class)->middleware(['permission:case_study']);

    // For Blogs Section
    Route::prefix('blog')->as('blog.')->middleware(['permission:blog'])->group(function () {

       Route::resource('/tag',AdminTagController::class);

       Route::resource('/category',AdminBlogCategoryController::class);

       Route::resource('/weblog',AdminBlogController::class);
       Route::get('/weblog/featured/{id}',[AdminBlogController::class,'makeFeatured']);
       Route::get('/comment-settings',[AdminBlogController::class, 'commentSettingsIndex'])->name('comment-settings');
       Route::post('/comment-settings',[AdminBlogController::class, 'commentSettingsStore'])->name('comment-settings');
    });

    // For dynamic page
    Route::prefix('dynamic-page')->as('dynamic-page.')->middleware(['permission:dynamic_page'])->group(function () {
        Route::get('/page-index/{slug?}', [DynamicPageController::class, 'index'])->name('page-index');
        Route::post('/page-store/{slug?}', [DynamicPageController::class, 'store'])->name('page-store');
        Route::get('/destroy-page/{slug?}', [DynamicPageController::class, 'pageDestroy'])->name('destroy-page');
    });

});
