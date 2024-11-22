<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ShiftSettings extends Settings
{
    public array $shifts;

    public static function group(): string
    {
        return 'shift';
    }

    public static function getDayShifts(string $date): array
    {
        return self::getShiftHours(self::getDay($date));
    }

    private static function getDay(string $date): int
    {
        return verta($date)->dayOfWeek;
    }

    private static function getShiftHours(int $getDay): array
    {
        $shifts = shiftSettings()->shifts;
        return self::transformShiftArray($shifts[$getDay]);
    }
    private static function transformShiftArray($shiftArray): array
    {
        $result = [];

        $shiftMapping = [
            'morning_shift_hours' => 'شیفت صبح',
            'afternoon_shift_hours' => 'شیفت عصر',
        ];

        foreach ($shiftMapping as $key => $translatedKey) {
            if (isset($shiftArray[$key])) {
                $result[$translatedKey] = [];

                foreach ($shiftArray[$key] as $shift) {
                    $startTimeKey = array_key_exists('morning_start', $shift) ? 'morning_start' : 'afternoon_start';
                    $endTimeKey = array_key_exists('morning_end', $shift) ? 'morning_end' : 'afternoon_end';

                    $startTime = $shift[$startTimeKey] . ':00';
                    $endTime = $shift[$endTimeKey];
                    $result[$translatedKey][$startTime] = "$shift[$startTimeKey] - $endTime";
                }
            }
        }

        return $result;
    }
}
