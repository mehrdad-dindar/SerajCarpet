<?php

namespace App\Listeners;

use App\Events\BulkOrderUpdated;
use App\Http\Controllers\OptimizedRouteController;
use App\Models\OptimizedRoute;
use App\Traits\Neshan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDirectionsAfterBulkUpdated
{
    use Neshan;

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
    public function handle(BulkOrderUpdated $event): void
    {
        $oldOrders = $event->oldOrders;
        $orders = $event->orders;
        $uniqueOldDriverIds = $oldOrders->pluck('driver_id')->filter()->unique();
        $uniqueDriverIds = $orders->pluck('driver_id')->filter()->unique();

        $allUniqueDriverIds = $uniqueOldDriverIds->merge($uniqueDriverIds)->unique()->toArray();
        $optimizedRoute = new OptimizedRoute();
        $optimizedRoute->calculateRoute($allUniqueDriverIds);
    }
}
