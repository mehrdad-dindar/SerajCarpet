<?php

namespace App\Http\Controllers\Api;

use App\Events\IncomingCall;
use App\Http\Controllers\Controller;
use App\Models\CallLog;
use App\Models\Customer;
use Illuminate\Http\Request;

class CallWebhookController extends Controller
{
    public function incoming(Request $request)
    {
        $callerId = $this->normalizePhoneNumber($request->input('caller_id'));


        if (!$callerId) {
            return response()->json(['error' => 'caller_id required'], 400);
        }

        $customer = Customer::where('phone', $callerId)->first();

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $callLog = CallLog::create([
            'customer_id' => $customer->id,
            'caller_id' => $callerId,
            'call_type' => 'incoming',
            'timestamp' => now(),
        ]);

        broadcast(new IncomingCall($customer));

        return response()->json(['status' => 'processed', 'call_log_id' => $callLog->id]);
    }

    protected function normalizePhoneNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);
        if (strlen($number) === 10) {
            $number = '0' . $number;
        }
        return $number;
    }
}
