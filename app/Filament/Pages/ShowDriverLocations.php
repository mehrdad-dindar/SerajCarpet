<?php

namespace App\Filament\Pages;

use App\Models\DriverLocation;
use Filament\Pages\Page;

class ShowDriverLocations extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static string $view = 'filament.pages.show-driver-locations';

    public function getDriverLocations()
    {
        return DriverLocation::where('updated_at', '>=', now()->subMinutes(30))->get();
    }
}
