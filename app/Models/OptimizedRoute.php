<?php

namespace App\Models;

use App\Services\AddressService;
use App\Traits\Neshan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class OptimizedRoute extends Model
{
    use HasFactory, Neshan;

    protected $guarded;

    protected $casts = [
        'orders' => 'array',
    ];

    public function orders()
    {
        return Order::whereIn('id', $this->orders)->get();
    }

    public function calculateRoute($allUniqueDriverIds): void
    {
        foreach ($allUniqueDriverIds as $driverId) {
            $driver = Driver::find($driverId);
            if ($driver->orders->count()) {
                $orderLocations = $this->processableOrders($driver->orders);
                $waypoints = $this->salesman($orderLocations);
                if (isset($waypoints->getData()->points)) {
                    $points = $waypoints->getData()->points;
                    $sortedOrders = $this->sortOrdersByIndex($orderLocations, $points);
                    $orderIds = $sortedOrders->pluck('id')->toArray();
                    $driver->optimizedRoutes()->create([
                        'orders' => $orderIds,
                    ]);
                }
            }
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
        if (!isset($order->address->latitude)) {
            return false;
        }

        $processableStatuses = [
            OrderStatus::IN_COLLECTIVE_LIST,
            OrderStatus::IN_DISTRIBUTION_LIST,
            OrderStatus::REVISITING_DRIVER,
        ];
        return in_array($order->status->name, $processableStatuses);
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

        if (!isset($orders[$orderIndex - 1])) {
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
