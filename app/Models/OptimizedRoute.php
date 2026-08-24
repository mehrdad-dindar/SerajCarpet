<?php

namespace App\Models;

use App\Settings\ShiftSettings;
use App\Traits\Neshan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class OptimizedRoute extends Model
{
    use HasFactory, Neshan;

    protected $guarded = [];

    protected $casts = [
        'orders' => 'array',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * واکشی سفارشات با حفظ ترتیب دقیق بهینه‌سازی‌شده
     */
    public function getSortedOrdersAttribute(): Collection
    {
        if (empty($this->orders)) {
            return collect();
        }

        return Order::query()
            ->with(['customer', 'address', 'status', 'items.property'])
            ->whereIn('id', $this->orders)
            ->orderByRaw('FIELD(id, ' . implode(',', array_map('intval', $this->orders)) . ')')
            ->get();
    }

    /**
     * محاسبه و بروزرسانی مسیر بهینه برای رانندگان
     */
    public function calculateRoute(array $driverIds): void
    {
        foreach ($driverIds as $driverId) {
            $driver = Driver::find($driverId);
            if (!$driver) {
                continue;
            }

            // واکشی تمام شیفت‌های فعال روز
            $shifts = ShiftSettings::getTodayShifts();
            if (empty($shifts)) {
                $shifts = ['08:00 - 20:00' => '08:00 - 20:00'];
            }

            foreach ($shifts as $shiftKey => $shiftRange) {
                $this->processDriverOrdersForShift($driver, is_string($shiftKey) ? $shiftKey : $shiftRange);
            }
        }
    }

    private function processDriverOrdersForShift(Driver $driver, string $shift): void
    {
        $orders = $driver->orders()
            ->with('address')
            ->whereHas('status', fn ($q) => $q->whereIn('name', [
                OrderStatus::RESERVED,
                OrderStatus::IN_COLLECTIVE_LIST,
                OrderStatus::IN_DISTRIBUTION_LIST,
                OrderStatus::REVISITING_DRIVER,
            ]))
            ->get();

        if ($orders->isEmpty()) {
            $driver->optimizedRoutes()->where('shift', $shift)->delete();
            return;
        }

        $processableOrders = $orders->filter(fn ($o) => !empty($o->address?->latitude) && !empty($o->address?->longitude))->values();

        // در صورتی که سفارشات کمتر از ۲ تا باشند یا مختصات نداشته باشند، ترتیب عادی ذخیره می‌شود
        if ($processableOrders->count() < 2) {
            $driver->optimizedRoutes()->updateOrCreate(
                ['driver_id' => $driver->id, 'shift' => $shift],
                ['orders' => $orders->pluck('id')->toArray()]
            );
            return;
        }

        // ارسال به وب‌سرویس TSP نشان
        try {
            $points = $processableOrders->map(fn ($o) => [
                'id'        => $o->id,
                'latitude'  => $o->address->latitude,
                'longitude' => $o->address->longitude,
            ]);

            $response = $this->salesman($points)->getData(true);

            if (isset($response['points']) && is_array($response['points'])) {
                // استخراج ایندکس‌های مرتب‌شده (بدون نقطه مبدا کارخانه)
                $sortedOrderIds = collect($response['points'])
                    ->slice(1) // حذف نقطه شروع
                    ->map(fn ($pt) => $points[$pt['index'] - 1]['id'] ?? null)
                    ->filter()
                    ->toArray();

                // اضافه کردن سفارش‌های بدون لوکیشن به انتهای لیست
                $unlocatedIds = $orders->pluck('id')->diff($sortedOrderIds)->toArray();
                $finalOrderIds = array_merge($sortedOrderIds, $unlocatedIds);

                $driver->optimizedRoutes()->updateOrCreate(
                    ['driver_id' => $driver->id, 'shift' => $shift],
                    ['orders' => $finalOrderIds]
                );
                return;
            }
        } catch (\Exception $e) {
            Log::warning('Neshan TSP Route Optimization failed: ' . $e->getMessage());
        }

        // Fallback در صورت عدم پاسخ نشان: ذخیره ترتیب معمولی
        $driver->optimizedRoutes()->updateOrCreate(
            ['driver_id' => $driver->id, 'shift' => $shift],
            ['orders' => $orders->pluck('id')->toArray()]
        );
    }
}
