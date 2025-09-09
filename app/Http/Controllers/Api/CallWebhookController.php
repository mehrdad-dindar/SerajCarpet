<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CallLog;
use App\Models\Customer;
use Illuminate\Http\Request;

class CallWebhookController extends Controller
{
    public function incoming(Request $request)
    {
        $callerId = $request->input('caller_id'); // شماره تماس‌گیرنده

        $customer = Customer::where('phone', $callerId)->first();

        if ($customer) {
            CallLog::create([
                'customer_id' => $customer->id,
                'caller_id' => $callerId,
                'call_type' => 'incoming',
                'timestamp' => now(),
            ]);

            // پخش رویداد برای نوتیفیکیشن بلادرنگ
//            broadcast(new \App\Events\IncomingCall($customer));
        }

        return response()->json(['status' => 'processed']);
    }
}
