<?php

use App\Http\Controllers\PurchaseRecords\ExportPurchaseRecordsHandler;
use App\Http\Controllers\PurchaseRecords\GetPurchaseRecordsHandler;
use Core\Auth\Domain\Entities\ValueObjects\PermissionName;
use Illuminate\Support\Facades\Route;

Route::middleware('has-permission:' . PermissionName::MANAGE_PURCHASE_RECORDS)->prefix('purchase-records')->group(
    function () {
        Route::get('/', GetPurchaseRecordsHandler::class);
        Route::get('/export', ExportPurchaseRecordsHandler::class);
    }
);
