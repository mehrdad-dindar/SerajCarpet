<?php

namespace App\Settings;

use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;

class ShiftSettings extends Settings
{
    public array $shifts = [];

    public static function group(): string
    {
        return 'shift';
    }

    public function getCurrentShift(): string
    {
        $currentTime = date('H:i:s');
        $shifts = self::getShiftHoursToArray(self::getDay(Carbon::today()));
        foreach ($shifts as $range) {
            if (!str_contains($range, ' - ')) {
                continue;
            }
            [$rangeStart, $rangeEnd] = explode(' - ', $range);

            if ($currentTime >= $rangeStart && $currentTime <= $rangeEnd) {
                return $range;
            }
        }
        return array_values($shifts)[0] ?? '08:00:00 - 14:00:00';
    }

    public function getCurrentShiftTitle(): string
    {
        $currentTime = date('H:i:s');
        $shifts = self::getShiftHours(self::getDay(Carbon::today()));
        foreach ($shifts as $key => $hourRanges) {
            foreach ($hourRanges as $range) {
                if (!str_contains($range, ' - ')) {
                    continue;
                }
                [$rangeStart, $rangeEnd] = explode(' - ', $range);
                if ($currentTime >= $rangeStart && $currentTime <= $rangeEnd) {
                    return $key . " ($rangeStart - $rangeEnd)";
                }
            }
        }
        return "شیفت روزانه";
    }

    public static function getDayShifts(string $date): array
    {
        return self::getShiftHours(self::getDay($date));
    }

    private static function getShiftHours(int $getDay): array
    {
        try {
            $shifts = shiftSettings()->shifts ?? [];
            if (empty($shifts) || !isset($shifts[$getDay])) {
                return ['شیفت عادی' => ['08:00:00' => '08:00 - 14:00']];
            }
            return self::transformShiftArray($shifts[$getDay]);
        } catch (\Throwable) {
            return ['شیفت عادی' => ['08:00:00' => '08:00 - 14:00']];
        }
    }

    private static function transformShiftArray($shiftArray): array
    {
        $result = [];
        $shiftMapping = [
            'morning_shift_hours' => 'شیفت صبح',
            'afternoon_shift_hours' => 'شیفت عصر',
        ];

        foreach ($shiftMapping as $key => $translatedKey) {
            if (isset($shiftArray[$key]) && is_array($shiftArray[$key])) {
                $result[$translatedKey] = [];

                foreach ($shiftArray[$key] as $shift) {
                    $startTimeKey = array_key_exists('morning_start', $shift) ? 'morning_start' : 'afternoon_start';
                    $endTimeKey = array_key_exists('morning_end', $shift) ? 'morning_end' : 'afternoon_end';

                    if (isset($shift[$startTimeKey], $shift[$endTimeKey])) {
                        $startTime = $shift[$startTimeKey] . ':00';
                        $endTime = $shift[$endTimeKey];
                        $result[$translatedKey][$startTime] = "$shift[$startTimeKey] - $endTime";
                    }
                }
            }
        }

        return $result;
    }

    private static function getDay(string|Carbon $date): int
    {
        return verta($date)->dayOfWeek;
    }

    public static function getTodayShifts(): array
    {
        return self::getShiftHoursToArray(self::getDay(Carbon::today()));
    }

    public static function getTomorrowShifts(): array
    {
        return self::getShiftHoursToArray(self::getDay(Carbon::tomorrow()));
    }

    private static function getShiftHoursToArray(int $getDay): array
    {
        try {
            $shifts = shiftSettings()->shifts ?? [];
            if (empty($shifts) || !isset($shifts[$getDay])) {
                return [
                    '08:00:00' => '08:00:00 - 14:00:00',
                    '14:00:00' => '14:00:00 - 20:00:00',
                ];
            }

            $allShifts = $shifts[$getDay];
            unset($allShifts['day']);
            $result = [];

            foreach ($allShifts as $shiftsGroup) {
                if (!is_array($shiftsGroup)) {
                    continue;
                }
                foreach ($shiftsGroup as $shift) {
                    $startTimeKey = array_key_exists('morning_start', $shift) ? 'morning_start' : 'afternoon_start';
                    $endTimeKey = array_key_exists('morning_end', $shift) ? 'morning_end' : 'afternoon_end';

                    if (isset($shift[$startTimeKey], $shift[$endTimeKey])) {
                        $startTime = $shift[$startTimeKey] . ':00';
                        $endTime = $shift[$endTimeKey] . ':00';
                        $result[$startTime] = "$startTime - $endTime";
                    }
                }
            }

            return !empty($result) ? $result : [
                '08:00:00' => '08:00:00 - 14:00:00',
            ];
        } catch (\Throwable) {
            return [
                '08:00:00' => '08:00:00 - 14:00:00',
            ];
        }
    }
}
