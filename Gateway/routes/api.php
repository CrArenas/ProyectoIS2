<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FlaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api', 'role:admin|user')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:api', 'role:admin')->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy']);
    Route::get('/adminReport', [TransactionController::class, 'adminReport']);
    Route::get('/adminReport/{user}', [TransactionController::class, 'adminUserReport']);
});

Route::middleware('auth:api', 'role:user')->group(function () {
    #Route::get('/send-sms', [NotificationController::class,'enviar']);
    Route::post('/predict', [FlaskController::class,'predecirFraude']);
    Route::get('/userstransactions', [TransactionController::class, 'show']);
    Route::get('/userReport', [TransactionController::class, 'userReport']);
    #Route::post('/transactions', [TransactionController::class, 'store']);
});
