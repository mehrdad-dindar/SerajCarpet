<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SystemSettings extends Settings
{
    public string $sms_panel_username = '';
    public string $sms_panel_password = '';
    public array $zarinpal = [false, ''];
    public array $factory_location = [35.6892, 51.3890];
    public array $surveys = [];

    public static function group(): string
    {
        return 'system';
    }
}
