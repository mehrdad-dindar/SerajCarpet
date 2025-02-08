<?php

namespace App\Listeners;

use App\Events\OrderLogCreated;
use App\Models\OptimizedRoute;
use App\Models\OrderStatus;
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
        if (!$event->updateDirection) {
            return;
        }
        $oldValues = $event->activity->properties['old'] ?? [];
        $newValues = $event->activity->properties['attributes'] ?? [];
        $order = $event->activity->subject;
        $statuses = [
            OrderStatus::IN_COLLECTIVE_LIST,
            OrderStatus::IN_DISTRIBUTION_LIST,
            OrderStatus::REVISITING_DRIVER
        ];

        foreach ($oldValues as $field => $oldValue) {
            if (!in_array($order->status->name, $statuses)) {
                continue;
            }

            $newValue = $newValues[$field] ?? null;
            $allUniqueDriverIds = [];
            if ($field == "driver_id") {
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
