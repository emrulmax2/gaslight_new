<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\BoilerBrandAndManualPageController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\Customers\CustomerController;
use App\Http\Controllers\Api\Customers\CustomerJobAddressController;
use App\Http\Controllers\Api\Customers\CustomerJobsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\GlobalApiController;
use App\Http\Controllers\Api\Jobs\JobsController;
use App\Http\Controllers\Api\Pricing\PricingPackageController;
use App\Http\Controllers\Api\Records\InvoiceController;
use App\Http\Controllers\Api\Records\RecordAndDraftController;
use App\Http\Controllers\Api\Records\RecordController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Users\UserManagementController;
use App\Http\Controllers\Api\Users\UserProfileController;
use App\Http\Controllers\Api\Users\UserSubscriptionController;
use App\Http\Controllers\UserSubscriptionWebhookController;

Route::prefix('/v1')->name('api.')->group(function() {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:api');


    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/login/otp-login',[LoginController::class, 'otpLogin']);
    Route::post('/login/send-otp',[LoginController::class, 'sendOtp']);


    
    // Public route for registration
    
    // for user creation
    Route::controller(RegisteredUserController::class)->group(function() {
        Route::post('register', 'store')->name('register.store');
        Route::post('register/validate-referral', 'validateReferral')->name('register.validate.referral');
        Route::post('register/send-otp', 'generateOtp')->name('register.generate.otp');
        Route::post('register/validate-otp', 'validateOtp')->name('register.validate.otp');
        Route::post('register/validate-email', 'validateEmail')->name('register.validate.email');
        Route::post('register-new', 'registerNew')->name('register.new');
    });

    // Protected routes (require Bearer Token)
    Route::middleware('auth:api')->group(function() {

        // Example: Fetch user dashboard
        Route::get('/dashboard', [DashboardController::class,'index'])->name('user.dashboard');
        // 
        // Example: Fetch user profile
        Route::get('/profile', [UserController::class, 'profile']);

        // Example: Update user profile
        Route::put('/profile/{user}', [UserController::class, 'update']);


        // Example: get  user list of a company
        Route::get('/company-user-list', [UserController::class, 'list']);

        // Example: Logout
        Route::post('/logout', [LoginController::class, 'logout']);

        Route::controller(BoilerBrandAndManualPageController::class)->group(function() {
        
            Route::get('boiler-brands', 'index');
            Route::get('boiler-brand/{id}/manual', 'brand_manual');
            Route::get('boiler-brand/download-manual/{manual_id}', 'boilerBrandManualDownload');
    
        });


        //Route::resource('company', CompanyController::class)->except(['create']);
        // Route::controller(CompanyController::class)->group(function() {
        //     Route::get('company-list', 'list')->name('company.list'); 
        //     Route::get('initial-setup', 'initialSetup')->name('initial.setup'); 
        //     Route::post('company-restore/{id}', 'restore')->name('company.restore'); 
        //     Route::post('company-update', 'update')->name('company.update'); 
        // });

        Route::controller(CompanyController::class)->group(function() {
            Route::post('company', 'store');
            Route::get('company/{company}/details', 'getDetails');
            Route::put('company/{company}/update', 'update');
            Route::delete('company/{company}/destroy', 'destroy');
            Route::get('bank-details', 'getCompanyBankDetails');
        });

        Route::controller(UserProfileController::class)->group(function() {
            Route::put('auth/profile/update', 'updateProfile');
            Route::put('auth/profile/update/password', 'updatePassword');
            Route::put('auth/profile/update/draw-signature', 'updateDrawSignature');
            Route::put('auth/profile/update/file-signature', 'updateFileSignature');
        });

        Route::get('titles', [GlobalApiController::class, 'getTitles']);

        Route::controller(PricingPackageController::class)->group(function() {
            Route::get('pricing-packages', 'list');
        });

        Route::controller(UserSubscriptionController::class)->group(function(){
            Route::get('users/payment-history/{id}', 'paymentHistory');
            Route::get('users/plans/{id}', 'userPlanDetails');
        });

        Route::controller(UserManagementController::class)->group(function() {
            Route::get('users', 'list');
            Route::post('users/store', 'store');
            Route::get('user/{id}', 'getSingleUser');
            Route::get('user/{id}/signature', 'getSignature');
            Route::put('users/{id}/update', 'update');
            Route::delete('users/destroy/{id}', 'destroy')->name('users.destroy'); 
            // Route::post('users/restore/{id}', 'restore')->name('users.restore');
    
            // Route::post('users-draw-signature/{user_id}','drawSignatureStore')->name('users.draw-signature'); 
            // Route::post('users-signature-upload/{user_id}','fileUploadStore')->name('users.upload-signature'); 
        });

        Route::get('customer/job/priorities', [GlobalApiController::class, 'getJobPriorities']);
        Route::get('job-status', [GlobalApiController::class, 'getJobStatus']);
        Route::get('calendar-time-slots', [GlobalApiController::class, 'getCalendarTimeSlots']);
        Route::get('records-list', [GlobalApiController::class, 'getRecordsList']);
        Route::get('cancel-reasons', [GlobalApiController::class, 'getCancelReasons']);
        Route::get('get-option', [GlobalApiController::class, 'getOption']);
        Route::get('get-dropdown-list', [GlobalApiController::class, 'getDropdownsList']);

        Route::controller(CustomerController::class)->group(function() {
            Route::get('customer/{id}', 'getDetails');
            Route::get('customers', 'list');
            Route::post('customer-store', 'storeCustomer');
            Route::post('customer-property-store', 'customerPropertyStore');
            Route::get('customer/{id}/property', 'getCustomerProperty');
            Route::post('customer-contact-info-store', 'customerContactInfoStore');
            Route::get('customer/{id}/contact-info', 'getCustomerContactInfo');
            Route::put('customer-update/{id}', 'updateCustomer');
            Route::put('customer-contact-info-update/{id}', 'updateCustomerContact');
            Route::delete('customer-destroy/{id}', 'destroy');
            Route::post('customer-restore/{id}', 'restore');
            Route::get('customers-search', 'search');
        });

        Route::controller(CustomerJobAddressController::class)->group(function(){
            Route::get('customer/{customer_id}/job-addresses/list', 'list');
            Route::post('customer/{customer_id}/job-addresses/store', 'job_address_store');
            Route::put('customer-job-address/{address_id}/update', 'job_address_update');
            Route::delete('customer/job-addresses/{address_id}/delete', 'job_address_destroy');
            Route::post('customer/job-addresses/{address_id}/restore', 'job_address_restore');
            Route::get('customer-job-address/{id}', 'single_job_address');

            Route::post('job-addresses/{property_id}/occoupant-store', 'occupantStore');
            Route::get('job-address/{property_id}/occupants-list', 'occupantsList');
            Route::get('job-address/occupants-edit/{occupant_id}', 'occupantEidt');
            Route::put('job-address/occupants-update/{occupant_id}', 'occupantUpdate');
        });

        Route::controller(CustomerJobsController::class)->group(function(){
            Route::get('customer/{customer_id}/jobs/list', 'list');
            Route::post('customer/{customer_id}/jobs/store', 'job_store');
            Route::get('customer-job/{id}', 'getSingleCustomerJob');
            Route::put('customer/jobs/{customer_job_id}/update', 'job_update');
            Route::put('customer/jobs/{customer_job_id}/status-update', 'jobStatusUpdate');
            Route::put('customer/jobs/{customer_job_id}/cancel-job', 'cancelJob');
        });

        Route::controller(JobsController::class)->group(function() {
            Route::get('jobs/list', 'list'); 
            Route::post('jobs/store','storeCustomerJob');
            Route::post('job/calendar/store','storeJobCalendar');
            Route::get('job/{id}', 'getJobDetails');
            Route::put('job/update/{id}','update');
            Route::put('job/calendar/{customer_job_id}/update','updateCustomerJobCalendar');
            Route::get('job/calendar/{id}', 'getJobCalendarDetails');
            // Route::post('job/calendar/store','addToCalendar');
            Route::post('jobs/get-slot-status', 'getCalendarSlotStatus');
        });

        Route::controller(RecordController::class)->group(function(){
            Route::post('records/store', 'store');
            Route::get('records/edit/{record_id}', 'edit');

            Route::get('records/approve/{record_id}', 'approve');
            Route::get('records/approve-and-email/{record_id}', 'approveEmail');
            Route::get('records/download/{record_id}', 'download');
        });


        Route::controller(RecordAndDraftController::class)->group(function(){
            Route::get('records-and-drafts/{job_form_id?}', 'list');
            Route::get('invoice-number', 'getInvoiceNumber');
            Route::get('quote-number', 'getQuoteNumber');    
            Route::get('records/jobs/{form_id}', 'getJobs');
            Route::get('vat-status/{user_id}', 'vatStatusNumber');
        });

        Route::controller(InvoiceController::class)->group(function() {
            Route::get('invoices/list', 'list');
            Route::post('invoices/store', 'store');
            Route::get('invoices/edit/{invoice_id}', 'edit');
            Route::get('invoices/send/{invoice_id}', 'send');
            Route::get('invoices/download/{invoice_id}', 'download');
        });

    });
});

Route::controller(UserSubscriptionWebhookController::class)->group(function () {
    Route::post('subscription-hooks', 'stripeHooks')->name('subscription.hooks');
});
