<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\MotherboardController;


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
});
