<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


/*
|--------------------------------------------------------------------------
| Authenticated
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */

    Route::get('/users', function () {
        return Inertia::render('Placeholder', [
            'title' => 'Users',
        ]);
    })->name('users.index');


    Route::get('/roles', function () {
        return Inertia::render('Placeholder', [
            'title' => 'Roles',
        ]);
    })->name('roles.index');


    Route::get('/permissions', function () {
        return Inertia::render('Placeholder', [
            'title' => 'Permissions',
        ]);
    })->name('permissions.index');


    /*
    |--------------------------------------------------------------------------
    | Transaksi
    |--------------------------------------------------------------------------
    */

    Route::get('/transaksi', function () {
        return Inertia::render('Placeholder', [
            'title' => 'Transaksi',
        ]);
    })->name('transaksi.index');


    Route::get('/pembayaran', function () {
        return Inertia::render('Placeholder', [
            'title' => 'Pembayaran',
        ]);
    })->name('pembayaran.index');


    Route::get('/laporan', function () {
        return Inertia::render('Placeholder', [
            'title' => 'Laporan',
        ]);
    })->name('laporan.index');


    /*
    |--------------------------------------------------------------------------
    | System
    |--------------------------------------------------------------------------
    */

    Route::get('/settings', function () {
        return Inertia::render('Placeholder', [
            'title' => 'Settings',
        ]);
    })->name('settings.index');

});


/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
|
| Route profile tetap menggunakan Breeze.
|
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

Route::middleware('auth')->group(function () {

    // Dashboard
    // ...

    Route::get('/users', [UserController::class, 'index'])
        ->middleware('permission:users.view')
        ->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->middleware('permission:users.create')
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->middleware('permission:users.create')
        ->name('users.store');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->middleware('permission:users.edit')
        ->name('users.edit');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->middleware('permission:users.edit')
        ->name('users.update');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:users.delete')
        ->name('users.destroy');

});
/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';