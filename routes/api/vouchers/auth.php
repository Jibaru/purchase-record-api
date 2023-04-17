<?php

use App\Http\Controllers\Vouchers\GetVouchersHandler;
use App\Http\Controllers\Vouchers\Invoices\StoreInvoiceHandler;
use Illuminate\Support\Facades\Route;

Route::prefix('vouchers')->group(
    function () {
        Route::get('/', GetVouchersHandler::class);
        Route::prefix('invoices')->group(
            function () {
                Route::post('/', StoreInvoiceHandler::class);
            }
        );
    }
);
