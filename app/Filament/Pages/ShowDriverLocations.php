<?php

namespace App\Filament\Pages;

use App\Models\DriverLocation;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ShowDriverLocations extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $activeNavigationIcon = 'heroicon-s-map';
    protected static string $view = 'filament.pages.show-driver-locations';
    protected static ?string $navigationLabel = "موقعیت رانندگان";

    public function getDriverLocations()
    {
        return DriverLocation::where('updated_at', '>=', now()->subMinutes(30))->get();
    }
    public static function getNavigationBadge(): ?string
    {
        return "زنده";
    }
    protected static ?string $navigationBadgeTooltip = 'آخرین موقعیت راننده ها در ۳۰ دقیقه گذشته';
}
