<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Minimal named login route to satisfy auth middleware redirects in non-API flows.
Route::get('/login', function () {
    return response('Login required', 401);
})->name('login');
