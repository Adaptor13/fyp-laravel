<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;


Route::middleware('web')->group(function () {

    // Authentication
    Route::get('/sign_in', [AuthController::class, 'signIn'])->name('sign_in');
    Route::get('/sign_up', [AuthController::class, 'signUp'])->name('sign_up');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/login', function () {
        return redirect()->route('sign_in');
    });
    
     // Password reset
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->middleware('guest')
        ->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->middleware('guest')
        ->name('password.update');

    // Auth-required
    Route::middleware('auth')->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    // Admin-only
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/index', [AdminController::class, 'index'])->name('admin_index');
        // add more admin routes here later
    });

    // Landing Page
    Route::get('/', [LandingController::class, 'landing'])->name('landing');
    Route::get('/report', [LandingController::class, 'report'])->name('report');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');

});
