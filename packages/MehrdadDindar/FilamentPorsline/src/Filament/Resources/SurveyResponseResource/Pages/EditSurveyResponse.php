<?php

namespace MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource;

class EditSurveyResponse extends EditRecord
{
    protected static string $resource = SurveyResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 