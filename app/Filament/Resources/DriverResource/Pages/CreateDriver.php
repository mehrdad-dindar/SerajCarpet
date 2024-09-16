<?php

namespace App\Filament\Resources\DriverResource\Pages;

use App\Enums\SmsPattern;
use App\Filament\Resources\DriverResource;
use App\Jobs\SendSmsJob;
use App\Traits\Sms;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDriver extends CreateRecord
{
    use Sms;
    protected static string $resource = DriverResource::class;

    protected function afterCreate()
    {
        $driver = $this->record;
        SendSmsJob::dispatch($driver->phone, SmsPattern::DRIVER_WELCOME, array($driver->name,$driver->phone));
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
