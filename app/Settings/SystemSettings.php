<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SystemSettings extends Settings
{
    public string $username;

    public static function group(): string
    {
        return 'system';
    }
}
