<?php

namespace App\Models;

use App\Settings\ShiftSettings;
use App\Traits\Neshan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OptimizedRoute extends Model
{
    use HasFactory, Neshan;

    const MORNING_SHIFT = 1;

    const AFTERNOON_SHIFT = 2;

    protected $guarded;

    protected $casts = [
        'orders' => 'array',
    ];

    public static function getOrdersCount($shift)
    {
        $driver = auth('driver')->user();
        $optimizedRoute = $driver->optimizedRoutes()
            ->whereShift($shift)
            ->first();
        if ($optimizedRoute) {
            return $optimizedRoute->orders()->count();
        }
        return 0;
    }

    public function orders()
    {
        return Order::whereIn('id', $this->orders)
            ->orderByRaw('FIELD(id, ' . implode(',', $this->orders) . ')')
            ->whereDate('time_apply_status', Carbon::today())
            ->whereHas(
                'status',
                fn ($q) => $q->whereIn('name', [
                    OrderStatus::RESERVED,
                    OrderStatus::IN_COLLECTIVE_LIST,
                    OrderStatus::IN_DISTRIBUTION_LIST,
                    OrderStatus::REVISITING_DRIVER,
                ])
            )
            ->get();
    }

    public function calculateRoute(array $driverIds): void
    {
        foreach ($driverIds as $driverId) {
            $driver = Driver::find($driverId);
            if (! $driver) {
                continue;
            }
            foreach (ShiftSettings::getTodayShifts() as $shift) {
                $this->processDriverOrdersForShift($driver, $shift);
            }
        }
    }

    private function processDriverOrdersForShift(Driver $driver, string $shift): void
    {
        $orders = $this->getOrders($driver, $shift);
        $this->updateOptimizedRoutes($driver, $orders, $shift);
    }

    private function getOrders(Driver $driver, $shift)
    {
        $shiftTimeFrame = explode(' - ', $shift);
        $orders = $driver->orders()
            ->whereDate('time_apply_status', Carbon::today());

        return $orders
            ->whereTime('time_apply_status', '>=', $shiftTimeFrame[0])
            ->whereTime('time_apply_status', '<', $shiftTimeFrame[1])
            ->get();
    }

    private function updateOptimizedRoutes(Driver $driver, $orders, $shift): void
    {
        if ($orders->count()) {
            $orderLocations = $this->processableOrders($orders);
            $waypoints = $this->salesman($orderLocations);
            if (isset($waypoints->getData()->points)) {
                $points = $waypoints->getData()->points;
                $sortedOrders = $this->sortOrdersByIndex($orderLocations, $points);
                $orderIds = $sortedOrders->pluck('id')->toArray();
                $driver->optimizedRoutes()->updateOrCreate(
                    [
                        'driver_id' => $driver->id,
                        'shift' => $shift,
                    ],
                    [
                        'orders' => $orderIds,
                    ]
                );
            }
        } else {
            $driver->optimizedRoutes()
                ->whereShift($shift)
                ->delete();
        }
    }

    private function processableOrders(Collection $orders)
    {
        return $orders->filter(fn ($order) => $this->isProcessable($order))
            ->values()
            ->map(fn ($order) => $this->transformOrder($order));
    }

    private function isProcessable($order): bool
    {
        if (! isset($order->address->latitude)) {
            return false;
        }

        return true;
    }

    private function transformOrder($order): array
    {
        return [
            'id' => $order->id,
            'latitude' => $order->address->latitude,
            'longitude' => $order->address->longitude,
        ];
    }

    public function sortOrdersByIndex($orders, $apiResponse)
    {
        array_shift($apiResponse);

        return collect($apiResponse)
            ->map(fn ($point) => $this->mapOrderToPoint($orders, $point))
            ->filter();
    }

    private function mapOrderToPoint($orders, $point)
    {
        $orderIndex = $point->index;

        if (! isset($orders[$orderIndex - 1])) {
            return null;
        }

        $order = Order::find($orders[$orderIndex - 1]['id']);

        $this->updateOrderAddressIfNeeded($order->address, $point->location);

        return $order;
    }

    private function updateOrderAddressIfNeeded(Address $address, $location): void
    {
        if ($address->latitude !== $location[0]) {
            $address->updateAddressGeo($location);
        }
    }
}
