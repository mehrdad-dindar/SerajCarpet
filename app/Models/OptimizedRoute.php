<?php

namespace App\Models;

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
        $statuses = [
            OrderStatus::IN_COLLECTIVE_LIST,
            OrderStatus::IN_DISTRIBUTION_LIST,
            OrderStatus::REVISITING_DRIVER
        ];

        foreach ($allUniqueDriverIds as $driverId) {
            $driver = Driver::find($driverId);
            if ($driver->orders->count()) {
                $orderLocations = $driver->orders->map(function ($order) use ($statuses) {

                    if (!isset($order->address->latitude) || !in_array($order->status->name, $statuses)) {
                        return null;
                    }

                    return [
                        'id' => $order->id,
                        'latitude' => $order->address->latitude,
                        'longitude' => $order->address->longitude,
                    ];
                })->filter();
                $waypoints = $this->salesman($orderLocations);
                if (isset($waypoints->getData()->points)) {
                    $points = $waypoints->getData()->points;
                    $sortedOrders = $this->sortOrdersByIndex($orderLocations, $points);
                    $orderIds = $sortedOrders->pluck('id')->toArray();
                    $driver->optimizedRoutes()->create([
                        "orders" => $orderIds
                    ]);
                }
            }
        }
    }

    public function sortOrdersByIndex($orders, $apiResponse)
    {
        array_shift($apiResponse);
        $sortedOrders = collect($apiResponse)->map(function ($point) use ($orders) {
            $orderIndex = $point->index;
            $order = $orders[$orderIndex + 1];
            $order = Order::find($order['id']);
            if ($order->address && isset($order->address->latitude)) {
                if ($order->address->latitude !== $point->location[0]) {
                    $this->updateOrderAddressGeo($order->address, $point->location);
                }
                return $order;
            } else {
                return null;
            }
        });

        return $sortedOrders->filter();
    }

    public function updateOrderAddressGeo(Address $address, array $points)
    {
        $address->latitude = $points[0];
        $address->longitude = $points[1];
        $address->save();
    }
}
