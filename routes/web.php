<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReservationApiController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home.index')->name('home');

Route::view('/login', 'auth.login')->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::resource('/rooms', RoomController::class);
Route::resource('/employees', EmployeeController::class);
Route::resource('/expenses', ExpenseController::class);
Route::resource('/reservations', ReservationController::class);
