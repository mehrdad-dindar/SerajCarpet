<?php

use App\Settings\ShiftSettings;
use App\Settings\SystemSettings;

if (!function_exists('settings')) {
    function settings(): SystemSettings
    {
        return app(SystemSettings::class);
    }

    function shiftSettings()
    {
        return app(ShiftSettings::class);
    }
}
