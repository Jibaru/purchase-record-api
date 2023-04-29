<?php

use App\Http\Controllers\Vouchers\GetVouchersHandler;
use App\Http\Controllers\Vouchers\Invoices\StoreInvoiceHandler;
use App\Http\Controllers\Vouchers\Voucher\GetVoucherHandler;
use Illuminate\Support\Facades\Route;

Route::prefix('vouchers')->group(
    function () {
        Route::get('/', GetVouchersHandler::class);
        Route::prefix('{voucherID}')->group(
            function () {
                Route::get('/', GetVoucherHandler::class);
            }
        );
        Route::prefix('invoices')->group(
            function () {
                Route::post('/', StoreInvoiceHandler::class);
            }
        );
    }
);
