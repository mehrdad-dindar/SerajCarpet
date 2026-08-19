<?php

use App\Http\Controllers\Api\CallWebhookController;
use App\Http\Controllers\Api\VoipController;
use App\Http\Middleware\VerifyVoipRequest;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

//Route::post('/call-incoming', [CallWebhookController::class, 'incoming'])->middleware('auth:sanctum');

Route::middleware([VerifyVoipRequest::class])->prefix('voip')->group(function () {
    // Endpoint: https://yourdomain.com/api/voip/incoming-call
    Route::post('/incoming-call', [VoipController::class, 'handleIncomingCall']);
});
