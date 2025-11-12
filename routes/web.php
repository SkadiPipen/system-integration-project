<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

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

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Notification
    Route::get('/notification', [NotificationController::class, 'index'])->name('notification');
    Route::post('/notification/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notification.mark-as-read');
    Route::delete('/notification', [NotificationController::class, 'destroy'])->name('notification.destroy');
    
    // Settings
    Route::get('/settings', [SettingsController::class, 'show'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Role-specific Pages
    Route::get('/requisition', function () {
        return view('pages.requisition');
    })->name('requisition')
    ->middleware('position:requestor,owner');

    Route::get('/purchase', function () {
        return view('pages.purchase');
    })->name('purchase')
    ->middleware('position:purchasor,owner');

    Route::get('/inventory', function () {
        return view('pages.inventory');
    })->name('inventory')
    ->middleware('position:owner');
});