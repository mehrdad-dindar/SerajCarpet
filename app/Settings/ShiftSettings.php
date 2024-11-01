<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ShiftSettings extends Settings
{
    public array $shift_hours;

    public static function group(): string
    {
        return 'shift';
    }
}
