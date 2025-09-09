<?php

namespace MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource;

class ViewSurveyResponse extends ViewRecord
{
    protected static string $resource = SurveyResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
