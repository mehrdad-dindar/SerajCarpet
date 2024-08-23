<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('system.username','9363432406');
        /*$this->migrator->add('general.site_active', true);*/
    }
};
