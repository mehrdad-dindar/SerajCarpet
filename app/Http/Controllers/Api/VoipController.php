<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// use App\Events\IncomingCallEvent; // در مراحل بعد برای Pusher استفاده می‌شود

class VoipController extends Controller
{
    /**
     * Handle incoming call webhook from Issabel.
     */
    public function handleIncomingCall(Request $request): JsonResponse
    {
        // اعتبارسنجی داده‌های دریافتی از ایزابل
        $validated = $request->validate([
            'caller_id' => 'required|string|max:20',
            'extension' => 'nullable|string|max:10', // داخلی پاسخگو
            'did'       => 'nullable|string|max:20', // شماره خط شرکت
        ]);

        $callerId = $this->normalizePhoneNumber($validated['caller_id']);

        // جستجوی مشتری در فیلدهای phone و phone2
        $customer = Customer::where('phone', $callerId)
            ->orWhere('phone2', $callerId)
            ->first();

        if ($customer) {
            $status = 'found';
            $message = 'Customer found.';
        } else {
            $status = 'not_found';
            $message = 'Customer not found. Potential new customer.';

            // در صورت نیاز می‌توانید در اینجا یک رکورد موقت به عنوان "مشتری بالقوه" بسازید
            // $customer = Customer::create(['phone' => $callerId, 'is_potential' => true]);
        }

        // لاگ کردن تماس برای دیباگ
        Log::info("Incoming Call: {$callerId} to Extension: {$validated['extension']} - Status: {$status}");

        // TODO: Dispatch Event for Filament Real-time Notification
        // IncomingCallEvent::dispatch($callerId, $customer, $validated['extension']);

        return response()->json([
            'success'  => true,
            'status'   => $status,
            'customer' => $customer,
            'message'  => $message,
        ], 200);
    }

    /**
     * Normalize phone number (e.g., remove prefixes, spaces, etc.)
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // حذف کاراکترهای اضافی
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // در صورت نیاز به تبدیل فرمت شماره (مثلا تبدیل 98 به 0) کدهای مربوطه اینجا قرار می‌گیرد
        // مثال:
        // if (str_starts_with($phone, '98')) {
        //     $phone = '0' . substr($phone, 2);
        // }

        return $phone;
    }
}
