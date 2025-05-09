<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\BoilerBrandAndManualPageController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\UserSubscriptionWebhookController;
use App\Http\Middleware\loggedin;

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
            Route::post('company', 'store')->name('company.store');
            Route::get('company/{company}/edit', 'edit')->name('company.edit');
            Route::put('company/{company}', 'update')->name('company.update');
            Route::delete('company/{company}', 'destroy')->name('company.destroy');
        });
    });
});

Route::controller(UserSubscriptionWebhookController::class)->group(function () {
    Route::post('subscription-hooks', 'stripeHooks')->name('subscription.hooks');
});