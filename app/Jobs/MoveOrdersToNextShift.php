<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Settings\ShiftSettings;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class MoveOrdersToNextShift implements ShouldQueue
{
    use Queueable;
//    public $tries = 5;

    protected string $endOfShift;

    protected array $timeSlots;

    public function failed(Exception $exception): void
    {
        Log::warning($exception->getMessage());
    }


    /**
     * Create a new job instance.
     */
    public function __construct($endOfShift)
    {
        $this->endOfShift = $endOfShift;
        $this->timeSlots = ShiftSettings::getTodayShifts();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orders = $this->getPendingOrders();

        if ($orders->count()) {
            $this->moveOrdersToNextShift($orders);
        }
    }

    private function getPendingOrders()
    {
        return Order::whereDate('time_apply_status', Carbon::today())
            ->whereTime('time_apply_status', '<', $this->endOfShift)
            ->whereIn('status_id', OrderStatus::whereIn(
                'name',
                [
                    OrderStatus::IN_COLLECTIVE_LIST,
                    OrderStatus::REVISITING_DRIVER,
                ]
            )->pluck('id'))
            ->whereNull('collected_at')
            ->get();
    }

    private function moveOrdersToNextShift($orders): void
    {
        $nextShiftStartAt = $this->getNextShiftStartAt();
        foreach ($orders as $order) {
            $order->time_apply_status = $nextShiftStartAt ?? $order->time_apply_status;
            $order->save();
        }
    }

    private function getNextShiftStartAt()
    {
        $nextKey = null;

        foreach ($this->timeSlots as $key => $value) {
            if (strpos($value, $this->endOfShift) !== false) {
                next($this->timeSlots);
                $nextKey = key($this->timeSlots);
                break;
            }
            next($this->timeSlots);
        }

        if ($nextKey !== null) {
            $nextShift = Carbon::today()->setTime(explode(':', $nextKey)[0], explode(':', $nextKey)[1]);

            return $nextShift;
        } else {
            $tomorrowShiftStart = array_key_first(ShiftSettings::getTomorrowShifts());
            $nextShift = Carbon::tomorrow()->setTime(
                explode(':', $tomorrowShiftStart)[0],
                explode(':', $tomorrowShiftStart)[1]
            );

            return $nextShift;
        }
    }
}
