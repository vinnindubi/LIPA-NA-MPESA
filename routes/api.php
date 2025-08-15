<?php

use App\Http\Controllers\API\MpesaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/access',[MpesaController::class,'generateAccessToken']);
Route::get('/access/push',[MpesaController::class,'stkPush']);
Route::post('/mpesa/callback', [MpesaController::class, 'mpesaCallback']);
Route::get('/mpesa/callback', [MpesaController::class, 'mpesaCallback']);