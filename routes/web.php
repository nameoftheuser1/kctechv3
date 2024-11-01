<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomGalleryController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home.index')->name('home');
Route::get('/room-list', [HomeController::class, 'roomList'])->name('room-list');
Route::get('/gallery', [HomeController::class, 'index'])->name('gallery');

Route::view('/login', 'auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/reservations/check-date', [ReservationController::class, 'checkDate'])->name('reservations.check-date');
    Route::resource('/reservations', ReservationController::class);
    Route::get('/reservations/receipt/{id}', [ReservationController::class, 'showReceipt'])->name('receipt');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::get('/settings/password', [SettingController::class, 'changePassword'])->name('password.change');
    Route::post('/password/update', [SettingController::class, 'updatePassword'])->name('password.update');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('/settings/edit', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('/rooms', RoomController::class)->except(['show']);
        Route::resource('/employees', EmployeeController::class)->except(['show']);
        Route::resource('/expenses', ExpenseController::class)->except(['show']);
        Route::resource('/galleries', RoomGalleryController::class);
    });
});
