<?php

namespace App\Listeners;

use App\Events\BulkOrderUpdated;
use App\Models\OrderStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Activitylog\Facades\LogBatch;
use Spatie\Activitylog\Models\Activity;

class LogOrdersStatusChange
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
    public function handle(BulkOrderUpdated $event): void
    {
        if (is_null($event->statusId)) {
            return;
        }

        $status = OrderStatus::findOrFail($event->statusId);

        LogBatch::startBatch();

        foreach ($event->orders as $order) {
            activity()
                ->causedBy($event->user)
                ->performedOn($order)
                ->withProperties([
                    'status_id' => $status->id
                ])
                ->log('Status changed to ' . $status->label);
        }
        LogBatch::endBatch();
    }
}
