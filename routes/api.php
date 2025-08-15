<?php

use App\Http\Controllers\API\MpesaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/users',UserController::class);

Route::get('/access',[MpesaController::class,'generateAccessToken']);
Route::get('/access/push',[MpesaController::class,'stkPush']);
Route::post('/mpesa/callback', [MpesaController::class, 'mpesaCallback'])->name('mpesa.callback');
Route::get('/mpesa/callback', [MpesaController::class, 'mpesaCallback']);