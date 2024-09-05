<?php

if (!function_exists('settings')){
    function settings(): \App\Settings\SystemSettings {
        return app(\App\Settings\SystemSettings::class);
    }
}
