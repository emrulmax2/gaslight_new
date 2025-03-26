<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\LayoutController;

use App\Http\Controllers\AuthController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckFirstLogin;
use App\Http\Middleware\loggedin;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SuperAdminAuthController;
use App\Http\Controllers\BoilerBrandAndManualPageController;
use App\Http\Controllers\Calculator\GasRateCalculator;
use App\Http\Controllers\Calendars\CalendarController;
use App\Http\Controllers\SuperAdmin\BoilerManualController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\UserSettings;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\CustomerJobAddressController;
use App\Http\Controllers\Customers\CustomerJobsController;
use App\Http\Controllers\Customers\JobController;
use App\Http\Controllers\Customers\PropertyController;
use App\Http\Controllers\Customers\JobDocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Jobs\JobController as JobsJobController;
use App\Http\Controllers\UserSettings\NumberingController;
use App\Http\Controllers\UserSettings\ReminderEmailTemplateController;
use App\Http\Middleware\SuperAdminAuthenticate;
use App\Http\Middleware\SuperAdminLoggedIn;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Profiler\Profile;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\Records\GasServiceRecordController;
use App\Http\Controllers\Records\GasWarningNoticeController;
use App\Http\Controllers\Records\HomeOwnerGasSafetyController;
use App\Http\Controllers\Records\RecordController;
use App\Http\Controllers\Records\InvoiceController;
use App\Http\Controllers\Records\QuoteController;
use App\Http\Controllers\SuperAdmin\BoilerBrandController;
use App\Models\BoilerBrand;
use App\Models\GasServiceRecord;

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

Route::prefix('/super-admin')->name('superadmin.')->group(function() {

    Route::controller(SuperAdminAuthController::class)->middleware(SuperAdminLoggedIn::class)->group(function() {
        Route::get('login', 'loginView')->name('login');
        Route::post('login', 'login')->name('login.check');

    });
    Route::controller(SuperAdminDashboard::class)->middleware(SuperAdminAuthenticate::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::middleware(SuperAdminAuthenticate::class)->group(function() {
        
        Route::get('logout', [SuperAdminAuthController::class, 'logout'])->name('logout');
        Route::get('users-list', [UserController::class, 'list'])->name('users.list');
 
        Route::resource('boiler-brand', BoilerBrandController::class)->except(['create']);

        Route::controller(BoilerBrandController::class)->group(function() {
    
            Route::get('boiler-brand-list', 'list')->name('boiler-brand.list'); 
            Route::post('boiler-brand-restore/{id}', 'restore')->name('boiler-brand.restore'); 
    
        });

        Route::resource('boiler-manual', BoilerManualController::class)->except(['create']);
        Route::controller(BoilerManualController::class)->group(function() {
            Route::get('boiler-manual-list', 'list')->name('boiler-manual.list'); 
            Route::post('boiler-manual-import', 'import')->name('boiler-manual.import'); 
            Route::get('boiler-manual-export/{id}', 'export')->name('boiler-manual.export'); 
            Route::post('boiler-manual-restore/{id}', 'restore')->name('boiler-manual.restore'); 
        });

    });
       
});

Route::middleware(SuperAdminAuthenticate::class)->group(function() {
    Route::get('/impersonate/{id}', [ImpersonateController::class, 'impersonate'])->name('impersonate');
});

Route::get('/impersonate-stop', [ImpersonateController::class, 'stopImpersonate'])->name('impersonate.stop');

Route::controller(RegisteredUserController::class)->middleware(loggedin::class)->group(function() {
    Route::get('register', 'index')->name('register');
    Route::post('register', 'store')->name('register');
});

Route::middleware(Authenticate::class)->group(function() {
    Route::controller(Dashboard::class)->group(function () {
        Route::get('/', 'index')->name('company.dashboard')->middleware(CheckFirstLogin::class);
    });

    Route::controller(BoilerBrandAndManualPageController::class)->group(function() {
        
        Route::get('boiler-manuals', 'index')->name('boiler-manuals');
        Route::get('boiler-manuals/{id}', 'boilerBrandManualByBoilerBrandId')->name('boiler-manuals.show');

    });
    Route::controller(UserSettings::class)->group(function () {
        Route::get('/settings', 'index')->name('user.settings');
    });

    Route::resource('company', CompanyController::class)->except(['update']);
    Route::controller(CompanyController::class)->group(function() {
        Route::get('company-list', 'list')->name('company.list'); 
        Route::get('initial-setup', 'initialSetup')->name('initial.setup'); 
        Route::post('company-restore/{id}', 'restore')->name('company.restore'); 
        Route::post('company/update', 'update')->name('company.update'); 
    });

    Route::resource('staff', StaffController::class);
    Route::controller(StaffController::class)->group(function() {

        Route::get('staff-list', 'list')->name('staff.list'); 
        Route::post('staff-restore/{id}', 'restore')->name('staff.restore'); 
        Route::post('staff-draw-signature/','drawSignatureStore')->name('staff.draw-signature'); 
        Route::post('staff-signature-upload/','fileUploadStore')->name('staff.upload-signature'); 
        
        
        
    });

    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::controller(CustomerController::class)->group(function() {
        Route::get('customers', 'index')->name('customers'); 
        Route::get('customers/list', 'list')->name('customers.list'); 
        Route::get('customers/create', 'create')->name('customers.create'); 
        Route::post('customers/store', 'store')->name('customers.store');
        Route::get('customers/show/{customer}', 'edit')->name('customers.edit');
        Route::post('customers/update', 'update')->name('customers.update');
        Route::delete('customers/destroy/{customer_id}', 'destroy')->name('customers.destroy'); 
        Route::post('customers/restore/{customer_id}', 'restore')->name('customers.restore');

        Route::post('customers/get-details', 'getDetails')->name('customers.get.details');
        Route::post('customers/search', 'search')->name('customers.search');
    });

    Route::controller(CustomerJobAddressController::class)->group(function(){
        Route::get('customer/{customer_id}/job-addresses', 'index')->name('customer.job-addresses');
        Route::get('customer/{customer_id}/job-addresses/list', 'list')->name('customer.job-addresses.list');
        Route::get('customer/{customer_id}/job-addresses/create', 'job_address_create')->name('customer.job-addresses.create');
        Route::post('customer/{customer_id}/job-addresses/store', 'job_address_store')->name('customer.job-addresses.store');
        Route::get('customer/{customer_id}/job-addresses/{address_id}/show', 'job_address_edit')->name('customer.job-addresses.edit');
        Route::post('customer/{customer_id}/job-addresses/{address_id}/update', 'job_address_update')->name('customer.job-addresses.update');
        Route::delete('customer/job-addresses/{address_id}/delete', 'job_address_destroy')->name('customer.job-addresses.job_address_destroy');
        Route::post('customer/job-addresses/{address_id}/restore', 'job_address_restore')->name('customer.job-addresses.job_address_restore');
    });


    Route::controller(CustomerJobsController::class)->group(function(){
        Route::get('customer/{customer_id}/jobs', 'index')->name('customer.jobs');
        Route::get('customer/{customer_id}/jobs/list', 'list')->name('customer.jobs.list');
        Route::get('customer/{customer_id}/jobs/create', 'job_create')->name('customer.jobs.create');
        Route::post('customer/{customer_id}/jobs/store', 'job_store')->name('customer.jobs.store');
        Route::get('customer/{customer_id}/jobs/{customer_job_id}/show', 'job_edit')->name('customer.jobs.edit');
        Route::post('customer/{customer_id}/jobs/{customer_job_id}/update', 'job_update')->name('customer.jobs.update');
    });

    Route::controller(JobsJobController::class)->group(function() {
        Route::get('jobs', 'index')->name('jobs'); 
        Route::get('jobs/list', 'list')->name('jobs.list'); 
        Route::get('jobs/create', 'create')->name('jobs.create'); 
        Route::post('jobs/store','store')->name('jobs.store');
        Route::get('jobs/show/{job}','show')->name('jobs.show');
        Route::post('jobs/update','update')->name('jobs.update');
        Route::post('jobs/get-calendar-details','getCalendarData')->name('jobs.get.calendar.details');
        Route::post('jobs/add-to-calendar','addToCalendar')->name('jobs.add.to.calendar');

        Route::post('jobs/search-address', 'searchAddress')->name('jobs.search.address'); 
        Route::post('jobs/search-customers', 'searchCustomers')->name('jobs.search.customers'); 
        Route::post('jobs/search-customer-addresses', 'getCustomerAddresses')->name('jobs.get.customer.addresses');
    });

    Route::controller(CalendarController::class)->group(function() {
        Route::get('calendar', 'index')->name('calendars'); 
        Route::get('calendar/events', 'events')->name('calendars.events'); 
    });

    Route::controller(NumberingController::class)->group(function() {
        Route::get('settings/numbering', 'index')->name('user.settings.numbering'); 
        Route::post('settings/numbering/store', 'store')->name('user.settings.numbering.store'); 
    });

    Route::controller(ReminderEmailTemplateController::class)->group(function() {
        Route::get('settings/reminder-email-templates', 'index')->name('user.settings.reminder.templates'); 
        Route::get('settings/reminder-email-templates/create/{form}', 'create')->name('user.settings.reminder.templates.create'); 
        Route::post('settings/reminder-email-templates/store', 'store')->name('user.settings.reminder.templates.store');

        Route::delete('settings/reminder-email-templates/destroy-attachment/{attachment_id}', 'destroyAttachment')->name('user.settings.reminder.templates.destroy.attachment'); 
    });

    Route::controller(GasRateCalculator::class)->group(function() {
        Route::get('gas-rate-calculator', 'index')->name('gas.rate.calculator');
    });

    Route::controller(RecordController::class)->group(function() {
        Route::get('create-records', 'index')->name('records.create');
        Route::get('records/{record}/{job}', 'records')->name('records');

        Route::post('records/store-job-address', 'storeJobAddress')->name('records.store.job.address');
        Route::post('records/store-customer', 'storeCustomer')->name('records.store.customer');
    });
    
    Route::controller(InvoiceController::class)->group(function(){
        Route::post('records/invoice/store', 'store')->name('invoice.store');
    });
    
    Route::controller(QuoteController::class)->group(function(){
        Route::post('records/quote/store', 'store')->name('quote.store');
        Route::post('records/quote/convert-to-invoice', 'convertToInvoice')->name('quote.convert.to.invoice');
    });
    
    Route::controller(HomeOwnerGasSafetyController::class)->group(function(){
        Route::post('records/homeowner_gas_safety_record/store-appliance', 'storeAppliance')->name('records.store.appliance');
        Route::post('records/homeowner_gas_safety_record/store-satisfactory-check', 'storeSatisfactoryCheck')->name('records.store.satisfactory.check');
        Route::post('records/homeowner_gas_safety_record/store-comments', 'storeComments')->name('records.store.comments');
        Route::post('records/homeowner_gas_safety_record/store-signatures', 'storeSignatures')->name('records.store.signatures');

        Route::get('records/homeowner_gas_safety_record/show/{gsr}', 'show')->name('records.gsr.view');
        Route::post('records/homeowner_gas_safety_record/store/{gsr}', 'store')->name('records.gsr.store');
    });
    
    Route::controller(GasWarningNoticeController::class)->group(function(){
        Route::post('records/gas-warning-notice/store-appliance', 'storeAppliance')->name('records.gwn.store.appliance');
        Route::post('records/gas-warning-notice/store-signatures', 'storeSignatures')->name('records.gwn.store.signatures');

        Route::get('records/gas-warning-notice/show/{gsr}', 'show')->name('records.gas.warning.notice.show');
        Route::post('records/gas-warning-notice/store/{gsr}', 'store')->name('records.gas.warning.notice.store');
    });
    
    Route::controller(GasServiceRecordController::class)->group(function(){
        Route::post('records/gas-service-record/store-appliance', 'storeAppliance')->name('records.gsr.store.appliance');
        Route::post('records/gas-service-record/store-signatures', 'storeSignatures')->name('records.gsr.store.signatures');

        Route::get('records/gas-service-record/show/{gsr}', 'show')->name('records.gas.service.show');
        Route::post('records/gas-service-record/store/{gsr}', 'store')->name('records.gas.service.store');
    });
});


Route::controller(FileUploadController::class)->group(function() {
    Route::post('/file-upload', 'upload')->name('file.upload');
    Route::delete('/file-delete/{id}', 'delete')->name('file.delete');
});

Route::controller(ProfileController::class)->group(function() {
    Route::get('profile', 'index')->name('profile');
    Route::post('profile/update', 'update')->name('profile.update');
    Route::post('profile-draw-signature/','drawSignatureStore')->name('profile.draw-signature'); 
    Route::post('profile-signature-upload/','fileUploadStore')->name('profile.upload-signature');
});


Route::controller(UserController::class)->group(function() {
    Route::post('user/update/{user}', 'update')->name('user.update');
});


Route::view('calculator', 'app.calculator.index')->name('calculator');



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
