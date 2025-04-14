<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\BoilerBrandAndManualPageController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\loggedin;

Route::prefix('/v1')->group(function() {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:api');


    Route::post('/login', [LoginController::class, 'login']);
  
    // Public route for registration
    Route::controller(RegisteredUserController::class)->group(function() {
        Route::post('register', 'store')->name('register');
    });

    

    // Protected routes (require Bearer Token)
    Route::middleware('auth:api')->group(function() {

        // Example: Fetch user dashboard
        Route::get('/dashboard', [DashboardController::class,'index']);
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
    });
});