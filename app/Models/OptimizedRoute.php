<?php

namespace App\Models;

use App\Traits\Neshan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptimizedRoute extends Model
{
    use HasFactory, Neshan;
    protected $guarded;

    public function calculateRoute(Order $orders)
    {
        $waypoints = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'latitude' => $order->address->latitude,
                'longitude' => $order->address->longitude,
            ];
        });
        $directions = $this->salesman($waypoints);
        $points = $directions->getData()->points;
        dd($points);

    }
}
