<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PendingReservationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationStatusController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomGalleryController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserReservationController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home.index')->name('home');
Route::get('/room-list', [HomeController::class, 'roomList'])->name('room-list');
Route::get('/gallery', [HomeController::class, 'index'])->name('gallery');

Route::view('/login', 'auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/user-form', [UserReservationController::class, 'create'])->name('user-form');
Route::post('/user-form', [UserReservationController::class, 'store'])->name('user-form.store');
Route::post('/user-form/check-availability', [UserReservationController::class, 'checkAvailability'])->name('user-form.checkAvailability');
Route::get('/user-form/payment/{id}', [UserReservationController::class, 'payment'])->name('user-form.payment');
Route::post('/user-form/receipt', [PaymentController::class, 'store'])->name('payments.store');
Route::get('/thank-you', [PaymentController::class, 'thankYou'])->name('home.thankyou');
Route::post('/password/reset-link', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');


Route::middleware(['auth'])->group(function () {
    // Route::get('/reservations/check-date', [ReservationController::class, 'checkDate'])->name('reservations.check-date');
    Route::get('/reservations/update-rooms', [ReservationController::class, 'updateRooms']);
    Route::resource('/reservations', ReservationController::class);
    Route::get('/reservations/receipt/{id}', [ReservationController::class, 'showReceipt'])->name('receipt');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::get('/settings/password', [SettingController::class, 'changePassword'])->name('password.change');
    Route::post('/password/update', [SettingController::class, 'updatePassword'])->name('password.update');

    Route::patch('reservations/{reservation}/check-in', [ReservationStatusController::class, 'checkIn'])->name('reservations.check-in');
    Route::patch('reservations/{reservation}/cancel', [ReservationStatusController::class, 'cancel'])->name('reservations.cancel');
    Route::patch('reservations/{reservation}/reserve', [ReservationStatusController::class, 'reserve'])->name('reservations.reserve');

    Route::middleware(['role:admin'])->group(function () {

        Route::get('/notifications/unread-reservations', [NotificationController::class, 'unreadReservationCount'])->name('notifications.unread');

        Route::get('/settings/email/edit', [SettingController::class, 'editEmail'])->name('settings.editEmail');
        Route::put('/settings/email/edit', [SettingController::class, 'updateEmail'])->name('settings.updateEmail');

        Route::post('/reservations/{id}/apply-commission', [ReservationController::class, 'applyCommission'])->name('reservations.applyCommission');
        Route::put('/reservation/edit/{reservation}', [ReservationController::class, 'updateReservation'])->name('reservations.update.full');

        Route::resource('/pending', PendingReservationController::class);
        Route::get('/settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('/settings/edit', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('/rooms', RoomController::class)->except(['show']);
        Route::resource('/employees', EmployeeController::class)->except(['show']);
        Route::resource('/expenses', ExpenseController::class)->except(['show']);
        Route::resource('/galleries', RoomGalleryController::class);
        Route::resource('/sales-reports', SalesReportController::class);
        Route::resource('/payments', PaymentController::class)->except('store');
        Route::delete('/galleries/{roomGallery}', [RoomGalleryController::class, 'destroy'])->name('galleries.destroy');
    });
});
