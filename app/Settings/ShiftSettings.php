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
}
