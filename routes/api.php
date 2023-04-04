<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\MotherboardController;
use App\Http\Controllers\ProcessorController;
use App\Http\Controllers\PowerSupplyController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('resend-email-verification', [EmailVerificationController::class, 'resendVerificationEmail']);
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

Route::middleware('auth:sanctum', 'verified', 'active')->group(function () {

    Route::controller(ComputerController::class)->group(function () {
        Route::get('computers', 'index');
        Route::get('computer/{id}', 'show');
        Route::post('computer', 'store');
        Route::delete('computer/{id}', 'destroy');
    });

    Route::controller(MotherboardController::class)->group(function () {
        Route::get('motherboards', 'index');
        Route::get('motherboard/{id}', 'show');
        Route::post('motherboard', 'store');
        Route::put('motherboard/{id}', 'update');
        Route::delete('motherboard/{id}', 'destroy');
    });

    Route::controller(ProcessorController::class)->group(function () {
        Route::get('processors', 'index');
        Route::get('processor/{id}', 'show');
        Route::post('processor', 'store');
        Route::put('processor/{id}', 'update');
        Route::delete('processor/{id}', 'destroy');
    });

    Route::controller(PowerSupplyController::class)->group(function () {
        Route::get('power-supplies', 'index');
        Route::get('power-supply/{id}', 'show');
        Route::post('power-supply', 'store');
        Route::put('power-supply/{id}', 'update');
        Route::delete('power-supply/{id}', 'destroy');
    });
});
