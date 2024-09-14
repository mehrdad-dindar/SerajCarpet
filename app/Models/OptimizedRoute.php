<?php

namespace App\Models;

use App\Traits\Neshan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OptimizedRoute extends Model
{
    use HasFactory, Neshan;

    protected $guarded;

    protected $casts = [
        'orders' => 'array',
    ];

    public static function getRouteTypes()
    {
        $statuses = [
            OrderStatus::IN_COLLECTIVE_LIST,
            OrderStatus::IN_DISTRIBUTION_LIST,
            OrderStatus::REVISITING_DRIVER,
        ];

        return OrderStatus::whereIn('name', $statuses)->get();
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function calculateRoute($allUniqueDriverIds): void
    {
        $statuses = [
            OrderStatus::IN_COLLECTIVE_LIST,
            OrderStatus::IN_DISTRIBUTION_LIST,
            OrderStatus::REVISITING_DRIVER,
        ];

        foreach ($allUniqueDriverIds as $driverId) {
            $driver = Driver::find($driverId);
            foreach ($statuses as $status) {
                $orders = $driver->orders()
                    ->whereHas('status', function ($query) use ($status) {
                        $query->where('name', $status);
                    })
                    ->where('time_apply_status', '>=', now())
                    ->get();
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
                                'order_status_id' => (OrderStatus::whereName($status)->first())->id
                            ],
                            [
                            'orders' => $orderIds,
                            ]
                        );
                    }
                } else {
                    $driver->optimizedRoutes()
                        ->whereHas('status', function ($query) use ($status) {
                            $query->where('name', $status);
                        })
                        ->delete();
                }
            }
        }
    }

    public function orders()
    {
        return Order::whereIn('id', $this->orders)->get();
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

    public static function getOrdersCount(OrderStatus $type)
    {
        $driver = auth('driver')->user();
        $optimizedRoute = $driver->optimizedRoutes()
            ->where('order_status_id', $type->id)
            ->first();
        if ($optimizedRoute) {
            return $optimizedRoute->orders()->count();
        }
        return 0;
    }
}
