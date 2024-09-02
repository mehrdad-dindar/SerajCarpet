<?php

namespace App\Listeners;

use App\Events\BulkOrderUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateDirectionsAfterBulkUpdated
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
        //
    }
}
