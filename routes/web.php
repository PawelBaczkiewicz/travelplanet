<?php

use Illuminate\Support\Facades\Route;
use Modules\ProductManagement\Presentation\Http\Controllers\ProductController;
use Ramsey\Uuid\Validator\GenericValidator;

Route::resource('products', ProductController::class)
    ->where(['product' => (new GenericValidator())->getPattern()]);

Route::prefix('product-management')->group(function () {
    Route::get('/send-to-payment-schedule', [ProductController::class, 'sendProductToPaymentScheduleAPI'])
        ->name('product-management.send-to-payment-schedule');
});


Route::get('/', function () {
    return redirect()->route('products.index');
});
