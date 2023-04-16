<?php

use App\Http\Controllers\Vouchers\Invoices\StoreInvoiceHandler;
use Illuminate\Support\Facades\Route;

Route::prefix('vouchers')->group(
    function () {
        Route::prefix('invoices')->group(
            function () {
                Route::post('/', StoreInvoiceHandler::class);
            }
        );
    }
);
