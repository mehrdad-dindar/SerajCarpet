<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CallService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoipController extends Controller
{
    public function __construct(protected CallService $callService) {}

    public function handleIncomingCall(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'caller_id' => 'required|string|max:30',
            'extension' => 'nullable|string|max:10',
            'did'       => 'nullable|string|max:30',
            'uniqueid'  => 'nullable|string|max:60',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $callLog = $this->callService->handleIncomingCall($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Incoming call received.',
            'call_id' => $callLog->id,
        ]);
    }

    public function handleHangup(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'uniqueid'       => 'required|string|max:60',
            'duration'       => 'nullable|numeric',
            'recording_file' => 'nullable|string|max:255',
            'caller_id'      => 'nullable|string|max:30',
            'extension'      => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $callLog = $this->callService->handleCallHangup($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Hangup logged.',
            'call_id' => $callLog?->id,
        ]);
    }
}
