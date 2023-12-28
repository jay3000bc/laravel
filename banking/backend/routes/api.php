<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAccountController;

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


Route::group([
 
    'middleware' => 'api',
    // 'prefix' => 'auth'
 
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');

    Route::post('/verify-otp', [AuthController::class, 'verifyOTP'])->name('verifyOTP');
    Route::post('/send-otp', [AuthController::class, 'sendOTP'])->name('sendOTP');


    Route::post('/initiate-transaction', [UserAccountController::class, 'initiateTransaction'])->name('initiateTransaction');
    Route::post('/process-transaction', [UserAccountController::class, 'processTransaction'])->name('processTransaction');

    Route::post('/statements', [UserAccountController::class, 'statementsData'])->name('statements');

});