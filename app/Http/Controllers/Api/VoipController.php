<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CallLog;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// use App\Events\IncomingCallEvent; // در مراحل بعد برای Pusher استفاده می‌شود
use App\Models\User;    // این خط باید اضافه شود
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class VoipController extends Controller
{
    /**
     * Handle incoming call webhook from Issabel.
     */
    public function handleIncomingCall(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'caller_id' => 'required|string|max:20',
            'extension' => 'nullable|string|max:10',
            'did'       => 'nullable|string|max:20',
            'uniqueid'  => 'nullable|string|max:50', // شناسه تماس ایزابل
        ]);

        $callerId = $this->normalizePhoneNumber($validated['caller_id']);

        // جستجوی مشتری
        $customer = Customer::where('phone', $callerId)
            ->orWhere('phone2', $callerId)
            ->first();

        // ثبت لاگ اولیه تماس (وضعیت در حال زنگ خوردن)
        $callLog = CallLog::create([
            'customer_id' => $customer ? $customer->id : null,
            'caller_id'   => $callerId,
            'extension'   => $validated['extension'] ?? null,
            'did'         => $validated['did'] ?? null,
            'type'        => 'inbound',
            'uniqueid'    => $validated['uniqueid'] ?? null,
        ]);

        // آماده‌سازی نوتیفیکیشن فیلامنت
        $title = $customer ? 'تماس از مشتری: ' . $customer->name : 'تماس از شماره ناشناس';
        $body = "شماره: {$callerId} \n در حال تماس با داخلی: {$validated['extension']}";

        $notification = Notification::make()
            ->title($title)
            ->body($body)
//            ->icon('heroicon-o-phone-arrow-down-left')
            ->iconColor($customer ? 'success' : 'warning')
            ->persistent(); // تا زمانی که کاربر نبندد روی صفحه می‌ماند

        // اگر مشتری بود، دکمه مشاهده پروفایل را اضافه می‌کنیم
        if ($customer) {
            $notification->actions([
                Action::make('view')
                    ->label('مشاهده پرونده')
                    ->url(route('filament.admin.resources.customers.edit', $customer))
                    ->button(),
            ]);
        } else {
            $notification->actions([
                Action::make('create')
                    ->label('ثبت سفارش جدید')
                    ->url(route('filament.admin.resources.orders.create', ['phone' => $callerId]))
                    ->button(),
            ]);
        }

        // ارسال نوتیفیکیشن به کاربران (می‌توانید فیلتر کنید که فقط به کاربر صاحب آن داخلی ارسال شود)
        // فعلا برای تست به همه ادمین‌ها ارسال می‌کنیم
        $users = User::all();
        $notification->sendToDatabase($users); // ذخیره در دیتابیس (زنگوله بالای پنل)
        $notification->broadcast($users);      // ارسال Real-time با Pusher (پاپ‌آپ)
//dump($notification);
        return response()->json([
            'success'  => true,
            'message'  => 'Call processed and notification sent.',
            'log_id'   => $callLog->id,
        ], 200);
    }

    private function normalizePhoneNumber(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}
