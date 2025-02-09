<?php

namespace App\Services;

use App\Jobs\MoveOrdersToNextShift;

class ShiftSchedulerService
{
    protected array $shifts;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $shifts = shiftSettings()->shifts ?? [];
        $shifts[] = array_shift($shifts);
        $this->shifts = $shifts ?? [];
    }

    /**
     * Register all shift schedules dynamically.
     */
    public function registerSchedules($schedule): void
    {
        foreach ($this->shifts as $key => $shift) {
            if (is_null($shift)) {
                continue;
            }
            $this->scheduleShift($schedule, $shift, $key);
        }
    }

    /**
     * Schedule a single shift.
     */
    private function scheduleShift($schedule, array $shift, $key): void
    {
        $shiftHours = array_merge($shift['morning_shift_hours'], $shift['afternoon_shift_hours']);
        foreach ($shiftHours as $hour) {
            $endOfShift = $this->getEndOfShift($hour);

            if ($endOfShift) {
                $schedule
                    ->job(new MoveOrdersToNextShift($endOfShift))
                    ->days($key)
                    ->at($endOfShift);
            }
        }
    }

    /**
     * Extract the end time from shift hours.
     */
    private function getEndOfShift(array $shiftHours): ?string
    {
        $endKeys = array_filter($shiftHours, function ($value, $key) {
            return str_ends_with($key, 'end');
        }, ARRAY_FILTER_USE_BOTH);

        return $endKeys ? array_values($endKeys)[0] : null;
    }
}
