<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentScheduleAPI\Presentation\Http\Controllers\PaymentScheduleController;
use Modules\PaymentScheduleAPI\Presentation\Http\Controllers\AuthController;

Route::post('/authorize', [AuthController::class, 'authorize'])
    ->name("authorize");

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('/payment-schedule', [PaymentScheduleController::class, 'calculate'])
            ->name("v1.payment-schedule");
    });

    Route::prefix('v2')->group(function () {
        Route::get('/payment-schedule', [PaymentScheduleController::class, 'calculate'])
            ->name("v2.payment-schedule");
    });
});
