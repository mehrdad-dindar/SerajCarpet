<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system.sms_panel_username','9363432406');
        $this->migrator->add('system.sms_panel_password','d5c81d78-1a21-4aa7-ac42-e1bdbe5a144c');
        $this->migrator->add('system.location_latitude','35.748132734876');
        $this->migrator->add('system.location_longitude','51.51760628422');
        $this->migrator->add('shift.shift_hours',[]);
        /*$this->migrator->add('general.site_active', true);*/
    }
};
