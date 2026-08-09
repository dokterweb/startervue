<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');


Route::get('/users', function () {
    return Inertia::render('Placeholder', [
        'title' => 'Users',
    ]);
});

Route::get('/roles', function () {
    return Inertia::render('Placeholder', [
        'title' => 'Roles',
    ]);
});

Route::get('/permissions', function () {
    return Inertia::render('Placeholder', [
        'title' => 'Permissions',
    ]);
});

Route::get('/transaksi', function () {
    return Inertia::render('Placeholder', [
        'title' => 'Transaksi',
    ]);
});

Route::get('/pembayaran', function () {
    return Inertia::render('Placeholder', [
        'title' => 'Pembayaran',
    ]);
});

Route::get('/laporan', function () {
    return Inertia::render('Placeholder', [
        'title' => 'Laporan',
    ]);
});

Route::get('/settings', function () {
    return Inertia::render('Placeholder', [
        'title' => 'Settings',
    ]);
});