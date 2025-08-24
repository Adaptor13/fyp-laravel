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

        //Users Pages
        Route::get('/index', [AdminController::class, 'index'])->name('admin_index');

        Route::get('/users/admins', [UserController::class, 'admins'])->name('users.admins');

        // Admins
        Route::get('/users/admin/data', [UserController::class, 'adminData'])
            ->name('users.admin.data');

        Route::post('/users/admin', [UserController::class, 'storeAdmin'])
            ->name('users.admin.store');

        Route::put('/users/admin/{user}', [UserController::class, 'updateAdmin'])
            ->name('users.admin.update');

        Route::delete('/users/admin/{user}', [UserController::class, 'destroyAdmin'])
            ->name('users.admin.destroy');

        //Law
        Route::get('/users/law-enforcement',[UserController::class, 'lawEnforcement'])->name('users.law');

        Route::get('/users/law/data', [UserController::class, 'lawEnforcementData'])
            ->name('users.law.data');

        Route::post('/users/law', [UserController::class, 'storeLawEnforcement'])
            ->name('users.law.store');

        Route::put('/users/law/{user}', [UserController::class, 'updateLawEnforcement'])
            ->name('users.law.update');

        Route::delete('/users/law/{user}', [UserController::class, 'destroyLawEnforcement'])
            ->name('users.law.destroy');

        // Government Child Welfare Officials (CWO)
        Route::get('/users/cwo', [UserController::class, 'cwo'])->name('users.cwo');

        Route::get('/users/cwo/data', [UserController::class, 'childWelfareOfficerData'])
            ->name('users.cwo.data');

        Route::post('/users/cwo', [UserController::class, 'storeCwo'])
            ->name('users.cwo.store');

        Route::put('/users/cwo/{user}', [UserController::class, 'updateCwo'])
            ->name('users.cwo.update');

        Route::delete('/users/cwo/{user}', [UserController::class, 'destroyCwo'])
            ->name('users.cwo.destroy');


        //Public User
        Route::get('/users/public-users',  [UserController::class, 'publicUsers'])->name('users.public');

        Route::get('/users/public-users/data', [UserController::class, 'publicUsersData'])
            ->name('users.public.data');

        Route::post('/users/public-users', [UserController::class, 'storePublic'])
            ->name('users.public.store');

        Route::put('/users/public-users/{id}', [UserController::class, 'updatePublicUser'])
            ->name('users.public.update');

        Route::delete('/users/public-users/{id}', [UserController::class, 'destroyPublicUser'])
            ->name('users.public.destroy');

        // Social Worker
        Route::get('/users/social-workers',[UserController::class, 'socialWorkers'])->name('users.social');

        Route::get('/users/social-workers/data', [UserController::class, 'socialWorkersData'])
            ->name('users.social.data');

        Route::post('/users/social-workers', [UserController::class, 'storeSocialWorker'])
            ->name('users.social.store');

        
        Route::put('/users/social-workers/{user}', [UserController::class, 'updateSocialWorker'])
            ->name('users.social.update');

        Route::delete('/users/social/{user}', [UserController::class, 'destroySocialWorker'])
            ->name('users.social.destroy');

        
        //Healthcare
        Route::get('/users/healthcare',    [UserController::class, 'healthcare'])->name('users.health');

        Route::get('/users/healthcare/data', [UserController::class, 'healthcareData'])
            ->name('users.healthcare.data');

        Route::post('/users/healthcare', [UserController::class, 'storeHealthcare'])
            ->name('users.healthcare.store');

        Route::put('/users/healthcare/{user}', [UserController::class, 'updateHealthcare'])
            ->name('users.healthcare.update');

        Route::delete('/users/healthcare/{user}', [UserController::class, 'destroyHealthcare'])
            ->name('users.healthcare.destroy');



    });

    // Landing Page
    Route::get('/', [LandingController::class, 'landing'])->name('landing');
    Route::get('/report', [LandingController::class, 'report'])->name('report');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');

});
