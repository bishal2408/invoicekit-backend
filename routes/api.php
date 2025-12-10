<?php

use App\Http\Controllers\ApiKey\ApiKeyController;
use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// login routes
Route::group([], function () {
    Route::controller(AuthController::class)->group(function () {
        // login route
        Route::post('login', 'login')->name('user.login');

        // logout route
        Route::post('logout', 'logout')->middleware('auth:sanctum')
            ->name('user.logout');
    });
});

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        // api key routes
        Route::controller(ApiKeyController::class)->group(function () {
            // store key
            Route::post('key', 'store')->name('key.store');

            // regenerated key
            Route::post('key/{api_key}/regenerate', 'regenerate')->name('key.regenerate');
        });
    });
