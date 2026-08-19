<?php

use App\Http\Controllers\Api\VoipController;
use App\Http\Middleware\VerifyVoipRequest;
use Illuminate\Support\Facades\Route;

Route::middleware([VerifyVoipRequest::class])->prefix('voip')->group(function () {
    Route::post('/incoming-call', [VoipController::class, 'handleIncomingCall']);
    Route::post('/hangup', [VoipController::class, 'handleHangup']);
});
