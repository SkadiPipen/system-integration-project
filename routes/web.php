<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('pages.home');
});

// Registration
Route::get('/signup', [AuthController::class, 'showRegisterForm'])->name('signup.form');
Route::post('/signup', [AuthController::class, 'register'])->name('signup');

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home after login
Route::get('/home', function() {
    return view('pages.home');
})->middleware('auth');

// Dashboard after login
Route::get('/dashboard', function() {
    return view('pages.dashboard');
})->middleware('auth')->name('dashboard');

