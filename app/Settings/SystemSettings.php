<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;
use Illuminate\Support\Facades\Config;

class SystemSettings extends Settings
{
    public string $sms_panel_username;
    public string $sms_panel_password;
    public array $zarinpal;
    public array $factory_location;
    public static function group(): string
    {
        return 'system';
    }
}
