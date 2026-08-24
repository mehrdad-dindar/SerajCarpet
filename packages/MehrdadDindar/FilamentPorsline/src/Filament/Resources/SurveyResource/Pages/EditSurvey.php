<?php

namespace MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResource;

class EditSurvey extends EditRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
