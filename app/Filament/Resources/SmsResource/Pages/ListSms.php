<?php

namespace App\Filament\Resources\SmsResource\Pages;

use App\Filament\Resources\SmsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSms extends ListRecords
{
    protected static string $resource = SmsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
