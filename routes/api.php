<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\MotherboardController;
use App\Http\Controllers\ProcessorController;
use App\Http\Controllers\PowerSupplyController;
use App\Http\Controllers\StorageDeviceController;
use App\Http\Controllers\RamMemoryController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\GpuController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MaintenanceHistoryController;
use App\Http\Controllers\UserTestHistoryController;

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

    Route::post('logout', [AuthController::class, 'revokeAccessTokens']);

    Route::controller(ComputerController::class)->group(function () {
        Route::get('computers', 'index');
        Route::get('computer/{id}', 'show');
        Route::post('computer', 'store');
        Route::delete('computer/{id}', 'destroy');

        Route::put('computer/{id}/sorting-step', 'sortingUpdate');
        Route::put('computer/{id}/hardware-tests-step', 'hardwareTestsUpdate');
        Route::put('computer/{id}/maintenance-step', 'maintenanceUpdate');
        Route::put('computer/{id}/network-and-peripherals-step', 'networkAndPeripheralsUpdate');
        Route::put('computer/{id}/user-tests-step', 'userTestsUpdate');
        Route::put('computer/{id}/reset-steps', 'resetSteps');
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

    Route::controller(StorageDeviceController::class)->group(function () {
        Route::get('storage-devices', 'index');
        Route::get('storage-device/{id}', 'show');
        Route::post('storage-device', 'store');
        Route::put('storage-device/{id}', 'update');
        Route::delete('storage-device/{id}', 'destroy');
    });

    Route::controller(RamMemoryController::class)->group(function () {
        Route::get('ram-memories', 'index');
        Route::get('ram-memory/{id}', 'show');
        Route::post('ram-memory', 'store');
        Route::put('ram-memory/{id}', 'update');
        Route::delete('ram-memory/{id}', 'destroy');
    });

    Route::controller(MonitorController::class)->group(function () {
        Route::get('monitors', 'index');
        Route::get('monitor/{id}', 'show');
        Route::post('monitor', 'store');
        Route::put('monitor/{id}', 'update');
        Route::delete('monitor/{id}', 'destroy');
    });

    Route::controller(GpuController::class)->group(function () {
        Route::get('gpus', 'index');
        Route::get('gpu/{id}', 'show');
        Route::post('gpu', 'store');
        Route::put('gpu/{id}', 'update');
        Route::delete('gpu/{id}', 'destroy');
    });

    Route::controller(BorrowerController::class)->group(function () {
        Route::get('borrowers', 'index');
        Route::get('borrower/{id}', 'show');
        Route::post('borrower', 'store');
        Route::put('borrower/{id}', 'update');
        Route::delete('borrower/{id}', 'destroy');
    });

    Route::controller(LoanController::class)->group(function () {
        Route::get('loans', 'index');
        Route::get('loan/{id}', 'show');
        Route::post('loan', 'store');
        Route::put('loan/{id}', 'update');
        Route::delete('loan/{id}', 'destroy');
    });

    Route::controller(MaintenanceHistoryController::class)->group(function () {
        Route::get('computer/{computer_id}/maintenance-histories', 'index');
        Route::post('maintenance-history', 'store');
    });

    Route::controller(UserTestHistoryController::class)->group(function () {
        Route::get('computer/{computer_id}/user-test-histories', 'index');
        Route::post('user-test-history', 'store');
    });
});
