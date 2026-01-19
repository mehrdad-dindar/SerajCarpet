<?php

namespace App\Http\Controllers\Api;

use App\Events\IncomingCall;
use App\Http\Controllers\Controller;
use App\Http\Requests\IncomingCallRequest;
use App\Models\CallLog;
use App\Models\Customer;
use App\Services\CallService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CallWebhookController extends Controller
{
    protected CallService $callService;

    public function __construct(CallService $callService)
    {
        $this->callService = $callService;
    }
    public function incoming(IncomingCallRequest $request): JsonResponse
    {
        try {
            $callLog = $this->callService->handleIncomingCall($request->validated());

            return response()->json([
                'status' => 'processed',
                'call_log_id' => $callLog->id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
