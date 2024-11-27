<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgetPasswordController;
use PHPUnit\Event\Telemetry\System;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\AmenitiesController;
use App\Http\Controllers\DownloadAppController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\XenditController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportController;
use App\Mail\VerifyEmployee;
use App\Mail\GuestBooking;
use Illuminate\Support\Facades\Mail;
Route::get('', [LoginController::class, 'index'])->name('index');
Route::get('login', [LoginController::class, 'index']);
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('/notifications/{id}/mark-as-read', [DashboardController::class, 'markAsRead']);

Route::get('download-app', [DownloadAppController::class, 'index'])->name('download-app');

Route::get('change-password/{id}', [LoginController::class, 'changePassword'])->name('change-password');

Route::post('updatePassword', [LoginController::class, 'updatePassword'])->name('updatePassword');

Route::get('logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('admin/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('settings', [UserController::class, 'settings'])->name('settings');
});

Route::middleware('auth', 'position:System Administrator')->group(function () {


    Route::get('admin/rooms', [RoomController::class, 'index'])->name('rooms');
    Route::get('admin/rooms/add', [RoomController::class, 'addRoom'])->name('addRoom');
    Route::get('admin/rooms/update/{roomId}', [RoomController::class, 'updateRoom'])->name('updateRoom');
    Route::get('admin/rooms/view/{roomId}', [RoomController::class, 'viewRoom'])->name('viewRoom');

    Route::get('admin/user', [UserController::class, 'index'])->name('user');

    Route::get('admin/user/update/{userId}', [UserController::class, 'updateUser'])->name('updateUser');
    Route::get(('admin/system-log'), [SystemLogController::class, 'index'])->name('system-log');

    Route::get('admin/user/add', [UserController::class, 'addUser'])->name('addUser');
});




Route::get('forget-password', [ForgetPasswordController::class, 'index'])->name('forget-password');

Route::get('enter-otp', [ForgetPasswordController::class, 'enterOtp'])->name('enter-otp');
Route::post('confirm-otp', [ForgetPasswordController::class, 'confirmOtp'])->name('confirm-otp');

Route::post('request-otp', [ForgetPasswordController::class, 'requestOtp'])->name('request-otp');

Route::get('reset-password', [ForgetPasswordController::class, 'resetPassword'])->name('reset-password');

Route::post('confirm-change-password', [ForgetPasswordController::class, 'confirmChangePassword'])->name('confirm-change-password');
Route::get('password-changed', [ForgetPasswordController::class, 'passwordChanged'])->name('password-changed');

Route::middleware('auth', 'position:Receptionist')->group(function () {

    Route::get('receptionist/amenities', [AmenitiesController::class, 'index'])->name('amenities');
    Route::get('receptionist/promotions', [PromotionController::class, 'index'])->name('promotions');
    Route::get('receptionist/booking', [BookingController::class, 'index'])->name('booking');
    Route::get('receptionist/booking/create', [BookingController::class, 'create'])->name('createBooking');
    Route::get('receptionist/booking/booking-details/{ReservationId}', [BookingController::class, 'bookingDetails'])->name('bookingDetails');
    Route::get('receptionist/room', [RoomController::class, 'receptionistIndex'])->name('receptionistRoom');

    Route::get('receptionist/message', [MessageController::class, 'index'])->name('message');

    Route::get('receptionist/payment', [PaymentController::class, 'index'])->name('payment');

    Route::get('receptionist/payment/receipt', [ReceiptController::class, 'index'])->name('receipt');

    Route::get('receptionist/report', [ReportController::class, 'index'])->name('report');

    Route::get('receptionist/check-in-out', [BookingController::class, 'checkInOut'])->name('check-in-out');

});



Route::get('send-mail', [XenditController::class, 'index'])->name('send-mail');

Route::get('online-payment/{reservationId}', [XenditController::class, 'createPayment'])->name('online-payment');


Route::get('manager/promotions', [PromotionController::class, 'index'])->name('promotion');
Route::get('download/report/{id}', [ReportController::class, 'downloadReport'])->name('download-report');



Route::get('printReceipt/{id}', [ReceiptController::class, 'printReceipt'])->name('printReceipt');


Route::get('success/{reference}', [ReceiptController::class, 'success'])->name('success');
Route::get('failed/{reference}', [ReceiptController::class, 'failed'])->name('failed');




Route::get('/verify-user', [LoginController::class, 'verifyUser'])->name('verify.user');

Route::get('printTransaction',[ReceiptController::class, "printTotalTransaction"])->name("printTransaction");
