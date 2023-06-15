<?php

use App\Http\Controllers\Items\GetItemsHandler;
use App\Http\Controllers\Items\ItemReference\GetSuppliersByItemReferenceHandler;
use Core\Auth\Domain\Entities\ValueObjects\PermissionName;
use Illuminate\Support\Facades\Route;

Route::middleware('has-permission:' . PermissionName::MANAGE_INVENTORY)->prefix('items')->group(
    function () {
        Route::get('/', GetItemsHandler::class);
        Route::prefix('{itemReference}')->group(
            function () {
                Route::get('suppliers', GetSuppliersByItemReferenceHandler::class);
            }
        );
    }
);
