<?php

use App\Http\Controllers\Vouchers\GetVouchersHandler;
use App\Http\Controllers\Vouchers\Invoices\StoreInvoiceHandler;
use App\Http\Controllers\Vouchers\Voucher\GetVoucherHandler;
use Core\Auth\Domain\Entities\ValueObjects\PermissionName;
use Illuminate\Support\Facades\Route;

Route::middleware('has-permission:' . PermissionName::MANAGE_VOUCHERS)->prefix('vouchers')->group(
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
