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
use App\Http\Controllers\Api\Records\GasBoilerSystemCommissioningChecklistController;
use App\Http\Controllers\Api\Records\GasBreakdownRecordController;
use App\Http\Controllers\Api\Records\GasCommissionDecommissionRecordController;
use App\Http\Controllers\Api\Records\GasJobSheetController;
use App\Http\Controllers\Api\Records\GasPowerFlushRecordController;
use App\Http\Controllers\Api\Records\GasServiceRecordController;
use App\Http\Controllers\Api\Records\GasUnventedHotWaterCylinderRecordController;
use App\Http\Controllers\Api\Records\GasWarningNoticeController;
use App\Http\Controllers\Api\Records\HomeOwnerGasSafetyController;
use App\Http\Controllers\Api\Records\InvoiceController;
use App\Http\Controllers\Api\Records\LandlordGasSafetyController;
use App\Http\Controllers\Api\Records\QuoteController;
use App\Http\Controllers\Api\Records\RecordAndDraftController;
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
  
    // Public route for registration

    Route::controller(RegisteredUserController::class)->group(function() {
        Route::post('register', 'store')->name('register.store');
        Route::post('register/validate-referral', 'validateReferral')->name('register.validate.referral');
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
        
            Route::get('boiler-manuals', 'index')->name('boiler-manuals');
            Route::get('boiler-manuals/{id}', 'boilerBrandManualByBoilerBrandId')->name('boiler-manuals.show');
    
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
            Route::get('company/{company}/edit', 'edit');
            Route::put('company/{company}', 'update');
            Route::delete('company/{company}', 'destroy');
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
        });

        Route::controller(CustomerJobsController::class)->group(function(){
            Route::get('customer/{customer_id}/jobs/list', 'list');
            Route::post('customer/{customer_id}/jobs/store', 'job_store');
            Route::get('customer-job/{id}', 'getSingleCustomerJob');
            Route::put('customer/jobs/{customer_job_id}/update', 'job_update');
            Route::put('customer/jobs/{customer_job_id}/status-update', 'jobStatusUpdate');
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
        });

        Route::controller(QuoteController::class)->group(function(){
            Route::get('records/quote/{quote_id}/details', 'getDetails');
            Route::post('records/quote/store', 'store');
            Route::get('records/quote/{quote_id}/approve', 'approve');
            Route::get('records/quote/{quote_id}/approve-and-email', 'approve_email');
            Route::get('records/quote/{quote_id}/download', 'download');
            Route::get('records/quote/{quote_id}/convert-to-invoice', 'convertToInvoice');
        });


        Route::controller(InvoiceController::class)->group(function(){
            Route::get('records/invoice/{invoice_id}/details', 'getDetails');
            Route::post('records/invoice/store', 'store');
            Route::get('records/invoice/{invoice_id}/approve', 'approve');
            Route::get('records/invoice/{invoice_id}/approve-and-email', 'approve_email');
            Route::get('records/invoice/{invoice_id}/download', 'download');
        });
        
        Route::controller(HomeOwnerGasSafetyController::class)->group(function(){
            Route::get('records/homeowner-gas-safety-records/{gas_safety_id}/details', 'getDetails');
            Route::post('records/homeowner-gas-safety-records/store', 'store');
            Route::get('records/homeowner-gas-safety-record/{gsr_id}/approve', 'approve');
            Route::get('records/homeowner-gas-safety-record/{gsr_id}/approve-and-email', 'approve_email');
            Route::get('records/homeowner-gas-safety-record/{gsr_id}/download', 'download');
        });
        
        Route::controller(LandlordGasSafetyController::class)->group(function(){
            Route::get('records/landlord-gas-safety-records/{gas_safety_id}/details', 'getDetails');
            Route::post('records/landlord-gas-safety-records/store', 'store');
            Route::get('records/landlord-gas-safety-record/{gsr_id}/approve', 'approve');
            Route::get('records/landlord-gas-safety-record/{gsr_id}/approve-and-email', 'approve_email');
            Route::get('records/landlord-gas-safety-record/{gsr_id}/download', 'download');
        });
        
        Route::controller(GasWarningNoticeController::class)->group(function(){
            Route::get('records/gas-warning-notice/{notice_id}/details', 'getDetails');
            Route::post('records/gas-warning-notice/store', 'store');
            Route::get('records/gas-warning-notice/{gwn_id}/approve', 'approve');
            Route::get('records/gas-warning-notice/{gwn_id}/approve-and-email', 'approve_email');
            Route::get('records/gas-warning-notice/{gwn_id}/download', 'download');
        });
        
        Route::controller(GasServiceRecordController::class)->group(function(){
            Route::get('records/gas-service-record/{record_id}/details', 'getDetails');
            Route::post('records/gas-service-record/store', 'store');
            Route::get('records/gas-service-record/{record_id}/approve', 'approve');
            Route::get('records/gas-service-record/{record_id}/approve-and-email', 'approve_email');
            Route::get('records/gas-service-record/{record_id}/download', 'download');
        });
        
        Route::controller(GasBreakdownRecordController::class)->group(function(){
            Route::get('records/gas-breakdown-record/{record_id}/details', 'getDetails');
            Route::post('records/gas-breakdown-record/store', 'store');
            Route::get('records/gas-breakdown-record/{record_id}/approve', 'approve');
            Route::get('records/gas-breakdown-record/{record_id}/approve-and-email', 'approve_email');
            Route::get('records/gas-breakdown-record/{record_id}/download', 'download');
        });
        
        Route::controller(GasBoilerSystemCommissioningChecklistController::class)->group(function(){
            Route::get('records/gas-boiler-system-commissioning-checklist/{checklist_id}/details', 'getDetails');
            Route::post('records/gas-boiler-system-commissioning-checklist/store', 'store');
            Route::get('records/gas-boiler-system-commissioning-checklist/{checklist_id}/approve', 'approve');
            Route::get('records/gas-boiler-system-commissioning-checklist/{checklist_id}/approve-and-email', 'approve_email');
            Route::get('records/gas-boiler-system-commissioning-checklist/{checklist_id}/download', 'download');
        });

        Route::controller(GasPowerFlushRecordController::class)->group(function(){
            Route::get('records/powerflush-certificate/{record_id}/details', 'getDetails');
            Route::post('records/powerflush-certificate/store', 'store');
            Route::get('records/powerflush-certificate/{record_id}/approve', 'approve');
            Route::get('records/powerflush-certificate/{record_id}/approve-and-email', 'approve_email');
            Route::get('records/powerflush-certificate/{record_id}/download', 'download');
        });

        Route::controller(GasCommissionDecommissionRecordController::class)->group(function(){
            Route::get('records/installation-commissioning-decommissioning-record/{record_id}/details', 'getDetails');
            Route::post('records/installation-commissioning-decommissioning-record/store', 'store');
            Route::get('records/installation-commissioning-decommissioning-record/{record_id}/approve', 'approve');
            Route::get('records/installation-commissioning-decommissioning-record/{record_id}/approve-and-email', 'approve_email');
            Route::get('records/installation-commissioning-decommissioning-record/{record_id}/download', 'download');
        });

        Route::controller(GasUnventedHotWaterCylinderRecordController::class)->group(function(){
            Route::get('records/unvented-hot-water-cylinders/{record_id}/details', 'getDetails');
            Route::post('records/unvented-hot-water-cylinders/store', 'store');
            Route::get('records/unvented-hot-water-cylinders/{record_id}/approve', 'approve');
            Route::get('records/unvented-hot-water-cylinders/{record_id}/approve-and-email', 'approve_email');
            Route::get('records/unvented-hot-water-cylinders/{record_id}/download', 'download');
        });
        Route::controller(GasJobSheetController::class)->group(function(){
            Route::get('records/job-sheet/{sheet_id}/details', 'getDetails');
            Route::post('records/job-sheet/store', 'store');
            Route::get('records/job-sheet/{record_id}/approve', 'approve');
            Route::get('records/job-sheet/{record_id}/approve-and-email', 'approve_email');
            Route::get('records/job-sheet/{record_id}/download', 'download');
        });

        Route::controller(RecordAndDraftController::class)->group(function(){
            Route::get('records-and-drafts', 'list');
            Route::get('invoice-number', 'getInvoiceNumber');
            Route::get('quote-number', 'getQuoteNumber');
            Route::get('records/jobs/{form_id}', 'getJobs');
            Route::get('vat-status', 'vatStatusNumber');
        });

    });
});

Route::controller(UserSubscriptionWebhookController::class)->group(function () {
    Route::post('subscription-hooks', 'stripeHooks')->name('subscription.hooks');
});