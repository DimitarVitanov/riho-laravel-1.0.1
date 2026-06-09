<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes (Riho base — auth, verification, dashboard redirect)
|--------------------------------------------------------------------------
| All VillaBit-specific routes are in routes/web.php.
| This file only handles authentication scaffolding and the legacy
| admin.default_dashboard redirect.
*/

Auth::routes(['register' => true, 'verify' => true]);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::post('/email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::group(['middleware' => ['auth'], 'as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::get('default-dashboard', function () {
        return redirect()->route('dashboard');
    })->name('default_dashboard');

    Route::get('dashboard', function () {
        return redirect()->route('dashboard');
    })->name('dashboard');
});

