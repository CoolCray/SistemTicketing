<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PackagesController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\SeatsController;
use App\Http\Controllers\Api\FoodsController;
use App\Http\Controllers\Api\DrinksController;
use App\Http\Controllers\Api\AdditionalsController;
use App\Http\Controllers\Api\TransactionsController;


// Auth Routes
Route::post('/login', [AuthController::class, 'login']);


// User Routes
Route::get('/user', [UserController::class, 'index']);
Route::post('/user', [UserController::class, 'store']);
Route::post('/user/{id}', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);


// Customer Routes  
Route::get('/customer', [CustomerController::class, 'index']);
Route::post('/customer', [CustomerController::class, 'store']);
Route::post('/customer/{id}', [CustomerController::class, 'update']);
Route::delete('/customer/{id}', [CustomerController::class, 'destroy']);


// Packages Routes
Route::get('/packages', [PackagesController::class, 'index']);
Route::post('/packages', [PackagesController::class, 'store']);
Route::post('/packages/{id}', [PackagesController::class, 'update']);
Route::delete('/packages/{id}', [PackagesController::class, 'destroy']);

// Attendance Routes
Route::get('/attendance', [AttendanceController::class, 'index']);
Route::post('/attendance', [AttendanceController::class, 'store']);
Route::post('/attendance/{id}', [AttendanceController::class, 'update']);
Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy']);

//Seats Routes
Route::get('/seats', [SeatsController::class, 'index']);
Route::post('/seats', [SeatsController::class, 'store']);
Route::post('/seats/{id}', [SeatsController::class, 'update']);
Route::delete('/seats/{id}', [SeatsController::class, 'destroy']);

// Additional Routes
Route::get('/additionals', [AdditionalsController::class, 'index']);
Route::post('/additionals', [AdditionalsController::class, 'store']);
Route::post('/additionals/{id}', [AdditionalsController::class, 'update']);
Route::delete('/additionals/{id}', [AdditionalsController::class, 'destroy']);


// Food Routes
Route::get('/foods', [FoodsController::class, 'index']);
Route::post('/foods', [FoodsController::class, 'store']);
Route::post('/foods/{id}', [FoodsController::class, 'update']);
Route::delete('/foods/{id}', [FoodsController::class, 'destroy']);

// Drink Routes
Route::get('/drinks', [DrinksController::class, 'index']);
Route::post('/drinks', [DrinksController::class, 'store']);
Route::post('/drinks/{id}', [DrinksController::class, 'update']);
Route::delete('/drinks/{id}', [DrinksController::class, 'destroy']);

// Transaction Routes
Route::get('/transactions', [TransactionsController::class, 'index']);
Route::post('/transactions', [TransactionsController::class, 'store']);
Route::post('/transactions/{id}', [TransactionsController::class, 'update']);
Route::delete('/transactions/{id}', [TransactionsController::class, 'destroy']);




