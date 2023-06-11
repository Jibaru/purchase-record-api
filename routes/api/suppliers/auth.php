<?php

use App\Http\Controllers\Suppliers\GetSuppliersHandler;
use App\Http\Controllers\Suppliers\SupplierReference\GetItemsBySupplierReferenceHandler;
use Core\Auth\Domain\Entities\ValueObjects\PermissionName;
use Illuminate\Support\Facades\Route;

Route::middleware('has-permission:' . PermissionName::MANAGE_INVENTORY)->prefix('suppliers')->group(
    function () {
        Route::get('/', GetSuppliersHandler::class);
        Route::prefix('{supplierReference}')->group(
            function () {
                Route::get('items', GetItemsBySupplierReferenceHandler::class);
            }
        );
    }
);
