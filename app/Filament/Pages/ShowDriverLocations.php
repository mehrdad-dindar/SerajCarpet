<?php

namespace App\Filament\Pages;

use App\Models\DriverLocation;
use Filament\Pages\Page;

class ShowDriverLocations extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $activeNavigationIcon = 'heroicon-s-map';
    protected static string $view = 'filament.pages.show-driver-locations';
    protected static ?string $navigationLabel = "نمایش رانندگان روی نقشه";

    public function getDriverLocations()
    {
        $res = DriverLocation::where('updated_at', '>=', now()->subMinutes(30))->get();
        return $res;
    }
}
