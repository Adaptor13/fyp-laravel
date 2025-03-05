<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/sing', function () {
    return view('welcome');
});

Route::get('/sign_in', [AuthController::class, 'signIn'])->name('sign_in');

Route::get('/sign_up', [AuthController::class, 'signUp'])->name('sign_up');

