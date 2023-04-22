<?php

use App\Http\Controllers\PurchaseRecords\GetPurchaseRecordsHandler;
use Illuminate\Support\Facades\Route;

Route::prefix('purchase-records')->group(
    function () {
        Route::get('/', GetPurchaseRecordsHandler::class);
    }
);
