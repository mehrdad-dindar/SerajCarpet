<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SystemSettings extends Settings
{
    public string $sms_panel_username;
    public string $sms_panel_password;
    public string $location_latitude;
    public string $location_longitude;
    public static function group(): string
    {
        return 'system';
    }
}
