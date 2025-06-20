<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\GuestLoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DamageReportController;


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/guest-login', [GuestLoginController::class, 'login'])->name('guest.login');
Route::get('/', fn() => redirect()->route('login.form'));

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login.form');
    Route::post('/login', 'login')->name('login');

    Route::post('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth',])->group(function () {
    // Dashboard & List
    Route::get('/dashboard', [RoomController::class, 'dashboard'])->name('dashboard');



    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');

    Route::get('/list', [RoomController::class, 'list'])->name('room.list');


    Route::get('/profile', [AuthController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [AuthController::class, 'update'])->name('profile.update');
});



Route::get('/report', [DamageReportController::class, 'index'])->name('report.index');
Route::post('/report', [DamageReportController::class, 'store'])->name('report.store');
Route::get('/room-usage-data/{filter}', [DamageReportController::class, 'roomUsageData']);
Route::post('/damage-reports/{id}/status', [DamageReportController::class, 'updateStatus']);
Route::delete('/damage-reports/{id}', [DamageReportController::class, 'destroy'])->name('damageReports.destroy');



Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.get');
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');

/*
|--------------------------------------------------------------------------
| Employee-Only Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', "employee_or_admin"])->group(function () {
    // Room Booking Application

    Route::get('/apply', [RoomController::class, 'apply'])->name('room.apply');
    Route::post('/apply', [BookingController::class, 'store'])->name('booking.store');

    // User Booking Status
    Route::get('/borrow-status', [BookingController::class, 'myStatus'])->name('booking.myStatus');
    Route::delete('/myborrowings/{id}', [BookingController::class, 'destroyFromMyBorrow'])->name('bookings.destroy2');
});



/*
|--------------------------------------------------------------------------
| Admin-Only Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->group(function () {

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Room Management
    Route::resource('rooms', RoomController::class)->except(['update', 'destroy']);
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');

    Route::get('/bookings/{id}/edit', [BookingController::class, 'edit'])->name('bookings.edit');

    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');


    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});
