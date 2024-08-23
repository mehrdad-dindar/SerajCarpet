<?php

namespace App\Filament\Resources\SmsPatternResource\Pages;

use App\Filament\Resources\SmsPatternResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmsPatterns extends ListRecords
{
    protected static string $resource = SmsPatternResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
