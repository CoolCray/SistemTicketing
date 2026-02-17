<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('login');


Route::view('/{any}', 'welcome')->where('any', '.*');
