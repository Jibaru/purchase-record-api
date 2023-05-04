<?php

use Core\Shared\Infrastructure\Middlewares\Authenticated;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(Authenticated::class)->group(
    function () {
        require_once 'api/auth.php';
    }
);

require_once 'api/no-auth.php';
