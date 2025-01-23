<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SystemSettings extends Settings
{
    public string $sms_panel_username;
    public string $sms_panel_password;
    public array $factory_location;
    public static function group(): string
    {
        return 'system';
    }
}
