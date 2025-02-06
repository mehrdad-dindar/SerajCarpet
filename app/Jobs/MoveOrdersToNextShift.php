<?php

namespace App\Jobs;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class MoveOrdersToNextShift implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orders = $this->getPendingOrders();
//        $this->moveOrdersToNextShift($orders);
    }

    private function getPendingOrders()
    {
//        return Order::where('day', $this->currentDay)
//            ->where('shift', $this->shiftType)
//            ->whereNull('collected_at')
//            ->get();
    }

//    private function moveOrdersToNextShift(null $orders)
//    {
//        return $orders;
//    }
}
