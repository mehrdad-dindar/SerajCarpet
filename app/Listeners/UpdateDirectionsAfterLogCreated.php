<?php

namespace App\Listeners;

use App\Events\OrderLogCreated;
use App\Models\OptimizedRoute;
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
        $oldValues = $event->activity->properties['old'] ?? [];
        $newValues = $event->activity->properties['attributes'] ?? [];
        $order = $event->activity->subject;

        foreach ($oldValues as $field => $oldValue) {
            $newValue = $newValues[$field] ?? null;
            $allUniqueDriverIds = [];
            if ($field == "driver_id"){
                if (!is_null($newValue)) {
                    $allUniqueDriverIds[] = $newValue;
                }
                if (!is_null($oldValue)) {
                    $allUniqueDriverIds[] = $oldValue;
                }
            } else {
                if (!is_null($order->driver)) {
                    $allUniqueDriverIds[] = $order->driver->id;
                }
            }

            if (!empty($allUniqueDriverIds)) {
                $optimizedRoute = new OptimizedRoute();
                $optimizedRoute->calculateRoute($allUniqueDriverIds);
            }

        }
    }
}
