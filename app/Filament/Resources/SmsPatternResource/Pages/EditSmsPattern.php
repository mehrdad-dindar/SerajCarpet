<?php

namespace App\Filament\Resources\SmsPatternResource\Pages;

use App\Filament\Resources\SmsPatternResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmsPattern extends EditRecord
{
    protected static string $resource = SmsPatternResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
