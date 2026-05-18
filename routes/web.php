<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})
->middleware(['auth', 'verified'])
->name('dashboard');

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