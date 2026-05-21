<?php

use App\Http\Controllers\Admin\SalesPerformanceController;
use App\Http\Controllers\Admin\BranchPerformanceController;
use App\Http\Controllers\Admin\ProspectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD (AUTH + VERIFIED)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [OverviewController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| AUTHENTICATED AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROFILE (Breeze default)
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT (GROUP PROTECTED)
    | Only: superadmin, owner, hrd
    |--------------------------------------------------------------------------
    */

    Route::middleware(['group:superadmin,owner,hrd'])->group(function () {
        Route::resource('users', UserController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | SALES / ADMIN FEATURES
    |--------------------------------------------------------------------------
    */

    Route::get('/sales-performance', [SalesPerformanceController::class, 'index'])
        ->middleware(['verified'])
        ->name('sales-performance');


         Route::get('/branch-performance', [BranchPerformanceController::class, 'index'])
        ->middleware(['verified'])
        ->name('branch-performance');

    /*
    |--------------------------------------------------------------------------
    | PROSPECT MANAGEMENT (ADMIN CRM)
    |--------------------------------------------------------------------------
    */

   Route::resource('/prospects', ProspectController::class);

    /*
    |--------------------------------------------------------------------------
    | FORM ELEMENTS (UI TEST)
    |--------------------------------------------------------------------------
    */

    Route::get('/form-elements', function () {
        return view('pages.form.form-elements', ['title' => 'Form Elements']);
    })->name('form-elements');

});