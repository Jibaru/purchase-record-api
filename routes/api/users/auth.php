<?php

use App\Http\Controllers\Users\GetUsersHandler;
use App\Http\Controllers\Users\User\Permissions\AddPermissionToUserHandler;
use App\Http\Controllers\Users\User\Permissions\GetPermissionsOfUserHandler;
use App\Http\Controllers\Users\User\Permissions\RemovePermissionFromUserHandler;
use Core\Auth\Domain\Entities\ValueObjects\PermissionName;
use Illuminate\Support\Facades\Route;

Route::middleware('has-permission:' . PermissionName::MANAGE_USERS)->prefix('users')->group(
    function () {
        Route::get('/', GetUsersHandler::class);
        Route::prefix('{userID}')->group(
            function () {
                Route::prefix('permissions')->group(
                    function () {
                        Route::get('/', GetPermissionsOfUserHandler::class);
                        Route::post('/', AddPermissionToUserHandler::class);
                        Route::delete('/', RemovePermissionFromUserHandler::class);
                    }
                );
            }
        );
    }
);
