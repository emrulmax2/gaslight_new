<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\LayoutController;

use App\Http\Controllers\AuthController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\loggedin;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\EngineerController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\UserSettings;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Customers\JobController;
use App\Http\Controllers\Customers\PropertyController;
use App\Http\Controllers\Customers\JobDocumentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('theme-switcher/{activeTheme}', [ThemeController::class, 'switch'])->name('theme-switcher');
Route::get('layout-switcher/{activeLayout}', [LayoutController::class, 'switch'])->name('layout-switcher');


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth','signed'])->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');


Route::controller(AuthController::class)->middleware(loggedin::class)->group(function() {
    Route::get('login', 'loginView')->name('login');
    
    Route::post('login', 'login')->name('login.check');
    
});

Route::controller(RegisteredUserController::class)->middleware(loggedin::class)->group(function() {

    Route::get('register', 'index')->name('register');
    Route::post('register', 'store')->name('register');
});

Route::middleware(Authenticate::class)->group(function() {
    Route::controller(Dashboard::class)->group(function () {
        Route::get('/', 'index')->name('company.dashboard');
    });

    Route::controller(UserSettings::class)->group(function () {
        Route::get('/settings', 'index')->name('user.settings');
    });

    Route::resource('company', CompanyController::class);
    Route::controller(CompanyController::class)->group(function() {
        Route::get('company-list', 'list')->name('company.list'); 
        Route::post('company-restore/{id}', 'restore')->name('company.restore'); 
    });

    Route::resource('engineer', EngineerController::class);
    Route::controller(EngineerController::class)->group(function() {
        Route::get('engineer-list', 'list')->name('engineer.list'); 
        Route::post('engineer-restore/{id}', 'restore')->name('engineer.restore'); 
    });

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::controller(CustomerController::class)->group(function() {
        Route::get('customers', 'index')->name('customers'); 
        Route::get('customers/list', 'list')->name('customers.list'); 
        Route::get('customers/create', 'create')->name('customers.create'); 
        Route::post('customers/store', 'store')->name('customers.store');
        Route::get('customers/edit/{customer}', 'edit')->name('customers.edit');
        Route::post('customers/update', 'update')->name('customers.update');
        Route::delete('customers/destroy/{customer_id}', 'destroy')->name('customers.destroy'); 
        Route::post('customers/restore/{customer_id}', 'restore')->name('customers.restore');

        Route::post('customers/get-details', 'getDetails')->name('customers.get.details');
        Route::post('customers/search', 'search')->name('customers.search');
    });

    Route::controller(JobController::class)->group(function() {
        Route::get('customers/{customer}/jobs', 'index')->name('customers.jobs'); 
        Route::get('customers/{customer}/jobs/list', 'list')->name('customers.jobs.list'); 
        Route::post('customers/{customer}/jobs/store', 'store')->name('customers.jobs.store');
        Route::get('customers/{customer}/jobs/{job}', 'show')->name('customers.jobs.show'); 
        Route::post('customers/{customer}/jobs/update', 'update')->name('customers.jobs.update'); 
        Route::delete('customers/{customer}/jobs/destroy/{job_id}', 'destroy')->name('customers.jobs.destroy'); 
        Route::post('customers/{customer}/jobs/restore/{job_id}', 'restore')->name('customers.jobs.restore');
    });

    Route::controller(JobDocumentController::class)->group(function() {
        Route::post('customers/{customer}/jobs/{job}/document/store', 'store')->name('customers.jobs.document.store');
        Route::get('customers/{customer}/jobs/{job}/document/list', 'list')->name('customers.jobs.document.list'); 
        Route::delete('customers/{customer}/jobs/{job}/document}/destroy/{document_id}', 'destroy')->name('customers.jobs.document.destroy'); 
        Route::post('customers/{customer}/jobs/{job}/document/restore/{document_id}', 'restore')->name('customers.jobs.document.restore');
    });

    Route::controller(InvoiceController::class)->group(function() {
        Route::get('invoice', 'invoice')->name('invoice');
    });

    //Route::controller(CustomerPropertyController::class)->group(function() {
        // Route::get('properties', 'index')->name('properties'); 
        // Route::get('properties/list', 'list')->name('properties.list'); 
        // Route::get('properties/create', 'create')->name('properties.create'); 
        //Route::post('properties/store', 'store')->name('properties.store');
        /*Route::get('customers/show/{customer}', 'show')->name('customers.show'); 
        Route::delete('customers/destroy/{customer_id}', 'destroy')->name('customers.destroy'); 
        Route::post('customers/restore/{customer_id}', 'restore')->name('customers.restore');*/

        //Route::post('properties/search', 'search')->name('properties.search');
    //});

    Route::controller(PropertyController::class)->group(function() {
        Route::get('customers/{customer}/job-addresses', 'index')->name('customers.job-addresses'); 
        Route::get('customers/{customer}/job-addresses/list', 'list')->name('customers.job-addresses.list'); 
        Route::post('/get-customers-address','getCustomerAddre')->name('getCustomer.address');
        Route::post('customers/{customer}/job-addresses/store', 'store')->name('customers.job-addresses.store');
        Route::get('customers/{customer}/job-addresses/edit/{property_id}', 'edit')->name('customers.job-addresses.edit'); 
        Route::post('customers/{customer}/job-addresses/update/{property_id}', 'update')->name('customers.job-addresses.update'); 
        Route::delete('customers/{customer}/job-addresses/destroy/{property_id}', 'destroy')->name('customers.job-addresses.destroy'); 
        Route::post('customers/{customer}/job-addresses/restore/{property_id}', 'restore')->name('customers.job-addresses.restore');

        Route::post('job-addresses/search', 'search')->name('properties.search');
    });
});

Route::controller(FileUploadController::class)->group(function() {
    Route::post('/file-upload', 'upload')->name('file.upload');
    Route::delete('/file-delete/{id}', 'delete')->name('file.delete');
});


Route::controller(PageController::class)->group(function () {
    Route::get('dashboard-overview-1', 'dashboardOverview1')->name('dashboard-overview-1');
    Route::get('dashboard-overview-2-page', 'dashboardOverview2')->name('dashboard-overview-2');
    Route::get('dashboard-overview-3-page', 'dashboardOverview3')->name('dashboard-overview-3');
    Route::get('dashboard-overview-4-page', 'dashboardOverview4')->name('dashboard-overview-4');
    Route::get('categories-page', 'categories')->name('categories');
    Route::get('add-product-page', 'addProduct')->name('add-product');
    Route::get('product-list-page', 'productList')->name('product-list');
    Route::get('product-grid-page', 'productGrid')->name('product-grid');
    Route::get('transaction-list-page', 'transactionList')->name('transaction-list');
    Route::get('transaction-detail-page', 'transactionDetail')->name('transaction-detail');
    Route::get('seller-list-page', 'sellerList')->name('seller-list');
    Route::get('seller-detail-page', 'sellerDetail')->name('seller-detail');
    Route::get('reviews-page', 'reviews')->name('reviews');
    Route::get('inbox-page', 'inbox')->name('inbox');
    Route::get('file-manager-page', 'fileManager')->name('file-manager');
    Route::get('point-of-sale-page', 'pointOfSale')->name('point-of-sale');
    Route::get('chat-page', 'chat')->name('chat');
    Route::get('post-page', 'post')->name('post');
    Route::get('calendar-page', 'calendar')->name('calendar');
    Route::get('crud-data-list-page', 'crudDataList')->name('crud-data-list');
    Route::get('crud-form-page', 'crudForm')->name('crud-form');
    Route::get('users-layout-1-page', 'usersLayout1')->name('users-layout-1');
    Route::get('users-layout-2-page', 'usersLayout2')->name('users-layout-2');
    Route::get('users-layout-3-page', 'usersLayout3')->name('users-layout-3');
    Route::get('profile-overview-1-page', 'profileOverview1')->name('profile-overview-1');
    Route::get('profile-overview-2-page', 'profileOverview2')->name('profile-overview-2');
    Route::get('profile-overview-3-page', 'profileOverview3')->name('profile-overview-3');
    Route::get('wizard-layout-1-page', 'wizardLayout1')->name('wizard-layout-1');
    Route::get('wizard-layout-2-page', 'wizardLayout2')->name('wizard-layout-2');
    Route::get('wizard-layout-3-page', 'wizardLayout3')->name('wizard-layout-3');
    Route::get('blog-layout-1-page', 'blogLayout1')->name('blog-layout-1');
    Route::get('blog-layout-2-page', 'blogLayout2')->name('blog-layout-2');
    Route::get('blog-layout-3-page', 'blogLayout3')->name('blog-layout-3');
    Route::get('pricing-layout-1-page', 'pricingLayout1')->name('pricing-layout-1');
    Route::get('pricing-layout-2-page', 'pricingLayout2')->name('pricing-layout-2');
    Route::get('invoice-layout-1-page', 'invoiceLayout1')->name('invoice-layout-1');
    Route::get('invoice-layout-2-page', 'invoiceLayout2')->name('invoice-layout-2');
    Route::get('faq-layout-1-page', 'faqLayout1')->name('faq-layout-1');
    Route::get('faq-layout-2-page', 'faqLayout2')->name('faq-layout-2');
    Route::get('faq-layout-3-page', 'faqLayout3')->name('faq-layout-3');
    //Route::get('login-page', 'login')->name('login');
    //Route::get('register-page', 'register')->name('register');
    Route::get('error-page-page', 'errorPage')->name('error-page');
    Route::get('update-profile-page', 'updateProfile')->name('update-profile');
    Route::get('change-password-page', 'changePassword')->name('change-password');
    Route::get('regular-table-page', 'regularTable')->name('regular-table');
    Route::get('tabulator-page', 'tabulator')->name('tabulator');
    Route::get('modal-page', 'modal')->name('modal');
    Route::get('slide-over-page', 'slideOver')->name('slide-over');
    Route::get('notification-page', 'notification')->name('notification');
    Route::get('tab-page', 'tab')->name('tab');
    Route::get('accordion-page', 'accordion')->name('accordion');
    Route::get('button-page', 'button')->name('button');
    Route::get('alert-page', 'alert')->name('alert');
    Route::get('progress-bar-page', 'progressBar')->name('progress-bar');
    Route::get('tooltip-page', 'tooltip')->name('tooltip');
    Route::get('dropdown-page', 'dropdown')->name('dropdown');
    Route::get('typography-page', 'typography')->name('typography');
    Route::get('icon-page', 'icon')->name('icon');
    Route::get('loading-icon-page', 'loadingIcon')->name('loading-icon');
    Route::get('regular-form-page', 'regularForm')->name('regular-form');
    Route::get('datepicker-page', 'datepicker')->name('datepicker');
    Route::get('tom-select-page', 'tomSelect')->name('tom-select');
    Route::get('file-upload-page', 'fileUpload')->name('file-upload');
    Route::get('wysiwyg-editor-classic-page', 'wysiwygEditorClassic')->name('wysiwyg-editor-classic');
    Route::get('wysiwyg-editor-inline-page', 'wysiwygEditorInline')->name('wysiwyg-editor-inline');
    Route::get('wysiwyg-editor-balloon-page', 'wysiwygEditorBalloon')->name('wysiwyg-editor-balloon');
    Route::get('wysiwyg-editor-balloon-block-page', 'wysiwygEditorBalloonBlock')->name('wysiwyg-editor-balloon-block');
    Route::get('wysiwyg-editor-document-page', 'wysiwygEditorDocument')->name('wysiwyg-editor-document');
    Route::get('validation-page', 'validation')->name('validation');
    Route::get('chart-page', 'chart')->name('chart');
    Route::get('slider-page', 'slider')->name('slider');
    Route::get('image-zoom-page', 'imageZoom')->name('image-zoom');
});
