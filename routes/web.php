<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Redirect dashboard to weather page
    Route::get('/dashboard', function () {
        return redirect()->route('weather');
    })->name('dashboard');
    
    // Weather routes
    Route::get('/weather', function () {
        return view('weather');
    })->name('weather');
});