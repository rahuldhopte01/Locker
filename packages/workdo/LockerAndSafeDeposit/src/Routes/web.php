<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Workdo\LockerAndSafeDeposit\Http\Controllers\CustomerController;
use Workdo\LockerAndSafeDeposit\Http\Controllers\LockerBookingController;
use Workdo\LockerAndSafeDeposit\Http\Controllers\LockerController;
use Workdo\LockerAndSafeDeposit\Http\Controllers\LockerKeyController;
use Workdo\LockerAndSafeDeposit\Http\Controllers\LockerMaintenanceController;
use Workdo\LockerAndSafeDeposit\Http\Controllers\LocationController;
use Workdo\LockerAndSafeDeposit\Http\Controllers\LockerMembershipController;
use Workdo\LockerAndSafeDeposit\Http\Controllers\LockerRenewalController;

Route::group(['middleware' => ['web','auth','verified','PlanModuleCheck:LockerAndSafeDeposit']], function () {
    Route::prefix('lockerandsafedeposit')->group(function () {
        
        Route::resource('locker-customer', CustomerController::class);
        Route::resource('locker-location', LocationController::class);
        Route::resource('locker', LockerController::class);

        Route::resource('locker-maintenance', LockerMaintenanceController::class);
        Route::get('locker-maintenance/description/{id}', [LockerMaintenanceController::class, 'description'])->name('locker-maintenance.description');

        Route::resource('locker-booking', LockerBookingController::class);
        Route::get('locker-booking-payment/{id}', [LockerBookingController::class, 'payment'])->name('locker-booking-payment.create');
        Route::post('locker-booking-payment-store/{id}', [LockerBookingController::class, 'addPayment'])->name('locker-booking-payment.store');
        Route::get('locker-payment-description/{id}', [LockerBookingController::class, 'description'])->name('locker-payment.description');
        Route::post('get-lockerbooking', [LockerBookingController::class, 'getBooking'])->name('get.lockerbooking');
        
        Route::resource('locker-renewal', LockerRenewalController::class);
        Route::post('get-lockercustomer', [LockerRenewalController::class, 'getCustomer'])->name('get.lockercustomer');

        Route::resource('locker-membership', LockerMembershipController::class);
        Route::post('get-locker', [LockerMembershipController::class, 'getLocker'])->name('get.locker');

        Route::resource('locker-key', LockerKeyController::class);
        Route::post('get-lockerkeycustomer', [LockerKeyController::class, 'getCustomer'])->name('get.lockerkeycustomer');
    });
});