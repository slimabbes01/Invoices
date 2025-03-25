<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Enable Laravel's built-in auth routes with email verification
Auth::routes(['verify' => true]);
