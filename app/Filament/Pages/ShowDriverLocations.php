<?php

namespace App\Filament\Pages;

use App\Models\DriverLocation;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class ShowDriverLocations extends Page implements HasTable
{
    use InteractsWithTable;
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

    public function table(Table $table): Table
    {
        return $table
            ->query(DriverLocation::query())
            ->columns([
                TextColumn::make('driver.name')
                    ->translateLabel(),
                TextColumn::make('location')
                    ->translateLabel()
                    ->url(function (Model $record): string {
                        return sprintf(
                            'https://nshn.ir/?lat=%s&lng=%s',
                            $record->latitude,
                            $record->longitude
                        );
                    }, true)
                    ->getStateUsing(fn ($record) => $record->latitude.','.$record->longitude),
                TextColumn::make('updated_at')
                    ->jalaliDateTime('l - d F Y')
                    ->label('آخرین بروزرسانی'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
