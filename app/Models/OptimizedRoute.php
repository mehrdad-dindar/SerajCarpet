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

    public function calculateRoute($allUniqueDriverIds): void
    {
        foreach ($allUniqueDriverIds as $driverId) {
            $driver = Driver::find($driverId);
            if ($driver->orders->count()) {
                $orderLocations = $driver->orders->map(function ($order) {
                    if (!isset($order->address->latitude)) {
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
                    $sortedOrders = $this->sortOrdersByIndex($driver->orders, $points);
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
        // تبدیل پاسخ API به یک مجموعه و مرتب کردن بر اساس index
        $sortedOrders = collect($apiResponse)->map(function ($point) use ($orders) {
            $orderIndex = $point->index;
            $order = $orders[$orderIndex];

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
