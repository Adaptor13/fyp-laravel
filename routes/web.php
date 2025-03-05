<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::view('sign_in_1', 'sign_in_1')->name('sign_in_1');
