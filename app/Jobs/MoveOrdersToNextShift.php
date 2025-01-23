<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        // TODO: Will Complete
        $user = User::find(1);
        $user->name = "asdfghjkl";
        $user->save();
        info('Shifts order Moved');
        return 1;
//        return Order::whereTime('day', $this->currentDay)
//            ->where('shift', $this->shiftType)
//            ->whereNull('collected_at')
//            ->get();
    }

//    private function moveOrdersToNextShift(null $orders)
//    {
//        return $orders;
//    }
}
