<?php

namespace App\Settings;

use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;

class ShiftSettings extends Settings
{
    public array $shifts;
    public string $current;

    public static function group(): string
    {
        return 'shift';
    }

    public function getCurrentShift(): string
    {
        $currentTime = date('H:i:s');
        $shifts = self::getShiftHoursToArray(self::getDay(Carbon::today()));
        foreach ($shifts as $range) {
            [$rangeStart, $rangeEnd] = explode(' - ', $range);

            if ($currentTime >= $rangeStart && $currentTime <= $rangeEnd) {
                return $range;
            }
        }
        return "";
    }

    public function getCurrentShiftTitle()
    {
        $currentTime = date('H:i:s');
        $shifts = self::getShiftHours(self::getDay(Carbon::today()));
        foreach ($shifts as $key => $hourRanges) {
            foreach ($hourRanges as $range) {
                [$rangeStart, $rangeEnd] = explode(' - ', $range);
                if ($currentTime >= $rangeStart && $currentTime <= $rangeEnd) {
                    return $key. " ($rangeStart - $rangeEnd)";
                }
            }
        }
        return "";
    }
    public static function getDayShifts(string $date): array
    {
        return self::getShiftHours(self::getDay($date));
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

    private static function getDay(string $date): int
    {
        return verta($date)->dayOfWeek;
    }

    public static function getTodayShifts(): array
    {
        return self::getShiftHoursToArray(self::getDay(Carbon::today()));
    }

    private static function getShiftHoursToArray(int $getDay): array
    {
        $shifts = shiftSettings()->shifts;
        $allShifts = $shifts[$getDay];
        unset($allShifts['day']);
        $result = [];

        foreach ($allShifts as $shifts) {
            foreach ($shifts as $shift) {
                $startTimeKey = array_key_exists('morning_start', $shift) ? 'morning_start' : 'afternoon_start';
                $endTimeKey = array_key_exists('morning_end', $shift) ? 'morning_end' : 'afternoon_end';

                $startTime = $shift[$startTimeKey] . ':00';
                $endTime = $shift[$endTimeKey] . ':00';
                $result[$startTime] = "$startTime - $endTime";
            }
        }

        return $result;
    }
}
