<?php

namespace App\Listeners;

use App\Events\OrderLogCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDirectionsAfterLogCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderLogCreated $event): void
    {
        dd($event->activity->properties);
//        $response = Http::get('https://external-api.com/endpoint', [
//            'data' => $event->activity->properties, // یا هر اطلاعات دیگری که نیاز دارید
//        ]);
//
//        // بررسی پاسخ و به‌روزرسانی جدول دیتابیس
//        if ($response->successful()) {
//            $data = $response->json();
//
//            // به‌روزرسانی مدل مربوطه در دیتابیس
//            YourModel::updateOrCreate(
//                ['id' => $data['id']], // شرط برای بروزرسانی یا ایجاد
//                ['column_name' => $data['value']] // داده‌های مورد نیاز
//            );
//        }
    }
}
