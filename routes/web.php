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
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    } catch (\Throwable $e) {}
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
    } catch (\Throwable $e) {}
    try {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
    } catch (\Throwable $e) {}
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
    } catch (\Throwable $e) {}

    // Clean up settings.json to remove bdcoder / bapric / bipric
    try {
        $settingsPath = storage_path('settings.json');
        if (file_exists($settingsPath)) {
            $settingsJson = file_get_contents($settingsPath);
            $settingsJson = str_ireplace('bdcoder', 'yourcpaexpert', $settingsJson);
            $settingsJson = str_ireplace('bapric', 'Your CPA Expert', $settingsJson);
            $settingsJson = str_ireplace('bipric', 'Your CPA Expert', $settingsJson);
            file_put_contents($settingsPath, $settingsJson);
        }
    } catch (\Throwable $settingsEx) {
        // Silence errors
    }

    // Clean up database tables from legacy hardcoded demo texts
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
            \Illuminate\Support\Facades\DB::table('users')->where('email', 'admin@bdcoder.com')->update(['email' => 'admin@yourcpaexpert.com']);
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('general_settings')) {
            \Illuminate\Support\Facades\DB::table('general_settings')->where('site_name', 'Bapric')->update(['site_name' => 'Your CPA Expert']);
            \Illuminate\Support\Facades\DB::table('general_settings')->where('author_name', 'BdCoder')->update(['author_name' => 'Your CPA Expert']);
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('footer_settings')) {
            \Illuminate\Support\Facades\DB::table('footer_settings')->where('footer_copy_right', 'like', '%bdCoder%')->update(['footer_copy_right' => 'Copyright © 2026 Your CPA Expert All reserved.']);
            \Illuminate\Support\Facades\DB::table('footer_settings')->where('footer_copy_right', 'like', '%bapric%')->update(['footer_copy_right' => 'Copyright © 2026 Your CPA Expert All reserved.']);
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('s_e_o_settings')) {
            $seo = \Illuminate\Support\Facades\DB::table('s_e_o_settings')->first();
            if (!$seo) {
                \Illuminate\Support\Facades\DB::table('s_e_o_settings')->insert([
                    'meta_keyword' => 'cpa, tax advisor, certified public accountant, accounting services, tax planning, business consulting, legal representation, your cpa expert',
                    'meta_description' => 'Your CPA Expert provides integrated certified public accounting, tax advisory, and legal representation services for businesses and individuals.',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                \Illuminate\Support\Facades\DB::table('s_e_o_settings')->where('id', $seo->id)->update([
                    'meta_keyword' => 'cpa, tax advisor, certified public accountant, accounting services, tax planning, business consulting, legal representation, your cpa expert',
                    'meta_description' => 'Your CPA Expert provides integrated certified public accounting, tax advisory, and legal representation services for businesses and individuals.',
                    'updated_at' => now()
                ]);
            }
        }
    } catch (\Throwable $dbEx) {
        // Silence errors
    }

    // Convert legacy relief requests to cases
    try {
        $reliefs = \App\Models\ReliefRequest::all();
        foreach ($reliefs as $relief) {
            do {
                $caseNumber = 'CS-' . rand(100000, 999999);
            } while (\App\Models\ClientCase::where('case_number', $caseNumber)->exists());

            // Create case
            $case = \App\Models\ClientCase::create([
                'case_number' => $caseNumber,
                'title' => $relief->reason ?: 'CPA & Legal Representation Case',
                'description' => "Client Name: " . $relief->name . "\nPhone: " . $relief->phone . "\nEmail: " . $relief->email . "\nAddress: " . $relief->address . "\n\nProposed Resolution / Target Goal:\n" . $relief->offer . "\n\nAdditional Background & Details:\n" . ($relief->details ?: ''),
                'client_id' => $relief->user_id,
                'status' => 'pending',
            ]);

            // Save file
            if ($relief->file && file_exists(public_path($relief->file))) {
                $fileExtension = pathinfo($relief->file, PATHINFO_EXTENSION);
                $newFileName = time() . '_' . uniqid() . '.' . $fileExtension;
                $newFilePath = '/upload/case-documents/' . $newFileName;

                $uploadPath = public_path('upload/case-documents');
                if (!\Illuminate\Support\Facades\File::exists($uploadPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($uploadPath, 0755, true);
                }

                copy(public_path($relief->file), public_path($newFilePath));

                // Create Document Vault entry
                \App\Models\CaseDocument::create([
                    'case_id' => $case->id,
                    'user_id' => $relief->user_id,
                    'title' => $relief->file_name ?: 'Intake Notice Document',
                    'file_path' => $newFilePath,
                    'file_type' => $fileExtension,
                    'file_size' => file_exists(public_path($newFilePath)) ? filesize(public_path($newFilePath)) : 0,
                    'is_client_uploaded' => true,
                ]);
            }

            \App\Models\ActivityLog::log('Case Request Promoted', 'Automatically converted assistance request #' . $relief->id . ' to Case #' . $case->case_number);
            $relief->delete();
        }
    } catch (\Throwable $e) {
        // Log or handle
    }

    echo 'done';
    return redirect()->route('home');
});

Route::get('/debug-env', function() {
    $foundFiles = [];
    try {
        $dir = new RecursiveDirectoryIterator(base_path());
        $ite = new RecursiveIteratorIterator($dir);
        $files = new RegexIterator($ite, '/IMG_1933/i', preg_match::class === 'class' ? RegexIterator::GET_MATCH : RegexIterator::MATCH);
        foreach($files as $name => $object){
            $foundFiles[] = $name;
        }
    } catch (\Throwable $e) {
        $foundFiles = 'error: ' . $e->getMessage();
    }

    return [
        'public_path' => public_path(),
        'base_path' => base_path(),
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown',
        'public_upload_exists' => file_exists(public_path('upload')) ? 'yes' : 'no',
        'public_upload_settings_files' => file_exists(public_path('upload/settings')) ? scandir(public_path('upload/settings')) : 'settings folder not found',
        'document_root_upload_exists' => isset($_SERVER['DOCUMENT_ROOT']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload') ? 'yes' : 'no',
        'document_root_files' => isset($_SERVER['DOCUMENT_ROOT']) ? scandir($_SERVER['DOCUMENT_ROOT']) : 'no document root',
        'logo_setting_db' => DB::table('logo_settings')->first(),
        'found_files_search' => $foundFiles,
    ];
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
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/download-chatting-file/{message}', [GuestViewController::class, 'downloadMessageFile'])->name('download.chatting-file');
});


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

    // client cases and vault
    Route::get('/cases', [ClientViewController::class, 'casesIndex'])->name('cases.index');
    Route::get('/cases/{id}', [ClientViewController::class, 'caseDetails'])->name('cases.details');
    Route::post('/cases/{id}/upload-document', [ClientViewController::class, 'uploadCaseDocument'])->name('cases.upload-document');
    Route::get('/documents/preview/{id}', [ClientViewController::class, 'previewDocument'])->name('documents.preview');
    Route::get('/documents/download/{id}', [ClientViewController::class, 'downloadDocument'])->name('documents.download');

    // client invoices
    Route::get('/invoices', [ClientViewController::class, 'invoicesIndex'])->name('invoices.index');
    Route::get('/invoices/{id}', [ClientViewController::class, 'invoiceShow'])->name('invoices.show');
    Route::post('/invoices/{id}/submit-proof', [ClientViewController::class, 'submitPaymentProof'])->name('invoices.submit-proof');
});



Route::group(['prefix' => 'admin', 'as'=>'admin.', 'middleware' => ['auth:sanctum','verified', 'admin']], function () {
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
        Route::post('/save', [UserController::class, 'userIndexSave'])->name('save');
        Route::delete('/destroy/{id}', [UserController::class, 'userDestroy'])->name('destroy');
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
        Route::post('/{id}/approve-case', [AdminController::class, 'approveAndCreateCase'])->name('approve-case');
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

    // Admin Staff Management
    Route::prefix('staff')->as('staff.')->middleware(['role:admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'destroy'])->name('destroy');
        Route::get('/time-logs/{id}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'timeLogs'])->name('time-logs');
        Route::get('/login-logs/{id}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'loginLogs'])->name('login-logs');
        Route::get('/messages/{id}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'messages'])->name('messages');
        Route::post('/messages/{id}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'sendMessage'])->name('send-message');
        Route::get('/messages/{id}/poll', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'pollMessages'])->name('messages.poll');
        Route::get('/download-payment-form/{id}/{type}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'downloadPaymentForm'])->name('download-payment-form');
        Route::post('/verify-payment/{id}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'verifyPayment'])->name('verify-payment');

        // Ledger management
        Route::get('/ledger/{id}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'ledgerIndex'])->name('ledger.index');
        Route::post('/ledger/{id}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'ledgerStore'])->name('ledger.store');
        Route::post('/ledger/{id}/{entry}/approve', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'ledgerApprove'])->name('ledger.approve');
        Route::post('/ledger/{id}/{entry}/pay', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'ledgerPay'])->name('ledger.pay');
        Route::delete('/ledger/{id}/{entry}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'ledgerDestroy'])->name('ledger.destroy');
        Route::get('/ledger/proof/{entry}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'downloadLedgerProof'])->name('ledger.proof');

        // Task management
        Route::get('/tasks/list', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'tasksIndex'])->name('tasks.index');
        Route::post('/tasks/store', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'tasksStore'])->name('tasks.store');
        Route::post('/tasks/{task}/status', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'tasksStatus'])->name('tasks.status');
        Route::delete('/tasks/{task}', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'tasksDestroy'])->name('tasks.destroy');

        // Payouts management
        Route::get('/payouts/list', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'payoutsIndex'])->name('payouts.index');
        Route::post('/payouts/{payout}/status', [App\Http\Controllers\AdminControllers\AdminStaffController::class, 'payoutsStatus'])->name('payouts.status');
    });

    // Case Management (Admin/Attorney)
    Route::prefix('cases')->as('cases.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/upload-document', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'uploadDocument'])->name('upload-document');
        Route::delete('/document/{doc_id}', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'destroyDocument'])->name('destroy-document');
        Route::get('/document/preview/{doc_id}', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'previewDocument'])->name('document.preview');
        Route::post('/{id}/milestones', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'addMilestone'])->name('add-milestone');
        Route::delete('/milestones/{milestone_id}', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'destroyMilestone'])->name('destroy-milestone');
    });

    // Invoice Management (Admin/Attorney)
    Route::prefix('invoices')->as('invoices.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminControllers\AdminInvoiceController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AdminControllers\AdminInvoiceController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\AdminControllers\AdminInvoiceController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [App\Http\Controllers\AdminControllers\AdminInvoiceController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [App\Http\Controllers\AdminControllers\AdminInvoiceController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [App\Http\Controllers\AdminControllers\AdminInvoiceController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/mark-paid', [App\Http\Controllers\AdminControllers\AdminInvoiceController::class, 'markPaid'])->name('mark-paid');
        Route::post('/{id}/send-email', [App\Http\Controllers\AdminControllers\AdminInvoiceController::class, 'sendEmail'])->name('send-email');
        Route::post('/{id}/approve-proof', [App\Http\Controllers\AdminControllers\AdminInvoiceController::class, 'approvePaymentProof'])->name('approve-proof');
        Route::post('/{id}/reject-proof', [App\Http\Controllers\AdminControllers\AdminInvoiceController::class, 'rejectPaymentProof'])->name('reject-proof');
    });

    // Document Generator
    Route::get('/document-generator', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'documentGenerator'])->name('document-generator');
    Route::post('/document-generator/generate', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'generateDocument'])->name('document-generator.generate');

    // System Activity Logs
    Route::get('/activity-logs', [App\Http\Controllers\AdminControllers\AdminCaseController::class, 'activityLogs'])->name('activity-logs');

});

// Staff Public/Auth/Dashboard Routes
Route::get('/staff/login', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'login']);

Route::prefix('/staff')->middleware(['auth:sanctum', 'verified', 'role:staff'])->as('staff.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'dashboard'])->name('dashboard');
    Route::post('/clock-in', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'clockIn'])->name('clock-in');
    Route::post('/clock-out', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'clockOut'])->name('clock-out');
    Route::get('/payment-method', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'paymentMethod'])->name('payment-method');
    Route::post('/payment-method', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'updatePaymentMethod']);
    Route::get('/messages', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'messages'])->name('messages');
    Route::post('/messages', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'sendMessage']);
    Route::get('/messages/poll', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'pollMessages'])->name('messages.poll');

    // Tasks
    Route::get('/tasks', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'tasksIndex'])->name('tasks.index');
    Route::post('/tasks/{task}/complete', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'tasksComplete'])->name('tasks.complete');

    // Financial Ledger & Payout requests
    Route::get('/financial-ledger', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'financialLedger'])->name('financial-ledger');
    Route::post('/request-payout', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'requestPayout'])->name('request-payout');
    Route::post('/reimbursement/request', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'requestReimbursement'])->name('reimbursement.request');
    Route::get('/ledger/proof/{entry}', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'downloadLedgerProof'])->name('ledger.proof');
    Route::get('/direct-deposit-form', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'generateDirectDepositForm'])->name('direct-deposit-form.download');
    
    // Invoices
    Route::get('/invoices', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'invoicesIndex'])->name('invoices.index');
    Route::get('/invoices/{id}', [App\Http\Controllers\StaffControllers\StaffViewController::class, 'invoiceShow'])->name('invoices.show');
});

