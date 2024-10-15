<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReservationApiController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home.index')->name('home');

Route::view('/login', 'auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/reservations/check-date', [ReservationController::class, 'checkDate'])->name('reservations.check-date');
    Route::resource('/reservations', ReservationController::class);
    Route::get('/settings', [SettingController::class, 'index'] )->name('settings');
    Route::get('/settings/password', [SettingController::class, 'changePassword'])->name('password.change');
    Route::post('/password/update', [SettingController::class, 'updatePassword'])->name('password.update');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('/rooms', RoomController::class)->except(['show']);
        Route::resource('/employees', EmployeeController::class)->except(['show']);
        Route::resource('/expenses', ExpenseController::class)->except(['show']);
    });
});
