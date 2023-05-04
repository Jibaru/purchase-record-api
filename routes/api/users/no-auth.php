<?php

use App\Http\Controllers\Users\LogInUserHandler;
use App\Http\Controllers\Users\SignUpUserHandler;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(
    function () {
        Route::post('/signup', SignUpUserHandler::class);
        Route::post('/login', LogInUserHandler::class);
    }
);
