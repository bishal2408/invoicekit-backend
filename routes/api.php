<?php

use App\Http\Controllers\User\AuthController;
use Illuminate\Support\Facades\Route;

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
