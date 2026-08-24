<?php

namespace MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;
use MehrdadDindar\FilamentPorsline\Filament\Resources\SurveyResource;
use MehrdadDindar\FilamentPorsline\Services\PorslineService;

class ListSurveys extends ListRecords
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Sync Survey')
                ->translateLabel()
                ->icon('heroicon-o-arrow-path')
                ->color(Color::Fuchsia)
                ->action(fn () => (new PorslineService())->syncSurvey()),
        ];
    }
}
