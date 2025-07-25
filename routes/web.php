<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\AdminController;


// Route::get('/sing', function () {
//     return view('welcome');
// });

//Users
// Route::get('/', [UserController::class, 'index'])->name('user_index');

//Authentication
Route::get('/sign_in', [AuthController::class, 'signIn'])->name('sign_in');
Route::get('/sign_up', [AuthController::class, 'signUp'])->name('sign_up');


//Admins
Route::get('/index', [AdminController::class, 'index'])->name('admin_index');

//Landing Page
Route::get('/',[LandingController::class, 'landing'])->name('landing');
Route::get('/report',[LandingController::class, 'report'])->name('report');

