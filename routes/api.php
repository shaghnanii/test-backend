<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Car\CarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DropdownController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/dropdown-data', [DropdownController::class, 'index']);

    Route::get('/logout', [AuthController::class, 'logout']);
    Route::apiResource('dashboard', DashboardController::class)->only(['index']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('cars', CarController::class);
});

