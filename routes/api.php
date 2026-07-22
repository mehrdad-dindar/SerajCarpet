<?php

use App\Http\Controllers\Api\CallWebhookController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::post('/call-incoming', [CallWebhookController::class, 'incoming'])->middleware('auth:sanctum');
