<?php

namespace MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResponseResource;

class ListSurveyResponses extends ListRecords
{
    protected static string $resource = SurveyResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 