<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OverviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard (AUTH + VERIFIED)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [OverviewController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})
->middleware(['auth', 'verified'])
->name('form-elements');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED AREA
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROFILE
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
    | GROUP PROTECTED ROUTE
    |--------------------------------------------------------------------------
    */

    Route::get('/admin', function () {
        return 'Admin Area';
    })->middleware('group:admin');

    /*
    |--------------------------------------------------------------------------
    | PERMISSION PROTECTED ROUTE
    |--------------------------------------------------------------------------
    */

    Route::get('/users', function () {
        return 'Users List';
    })->middleware('permission:users.view');

    Route::get('/users/create', function () {
        return 'Create User';
    })->middleware('permission:users.create');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';