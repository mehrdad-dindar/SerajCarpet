<?php

namespace App\Filament\Pages;

use App\Settings\SystemSettings;
use App\Traits\Neshan;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Dotswan\MapPicker\Fields\Map;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\SettingsPage;
use Livewire\Attributes\Title;

class SystemManager extends SettingsPage
{
    use HasPageShield,Neshan;

    protected static ?string $navigationLabel = 'مدیریت سیستم';
    protected static ?string $title = "مدیریت سیستم";

    protected static string $settings = SystemSettings::class;
    protected static ?string $navigationGroup = 'System Setting';
    protected static ?int $navigationSort = 1;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Sms Panel'))
                    ->schema([
                        TextInput::make('sms_panel_username')
                            ->label(__("User Name"))
                            ->hint(__("Sms Panel User Name"))
                            ->required(),
                        TextInput::make('sms_panel_password')
                            ->label(__("Password"))
                            ->hint(__("Sms Panel Password"))
                            ->required(),
                    ])->columns(),
                Forms\Components\Section::make(__('Factory Location'))
                    ->schema([
                        Map::make('location')
                            ->hint('با کشیدن و اسکرول موقعیت مورد نظر را انتخاب کنید')
                            ->label(__('Location'))
                            ->default([
                                'lat' => 35.68920000000000,
                                'lng' => 51.38900000000000
                            ])
                            ->afterStateUpdated(function (Set $set, ?array $state): void {
                                $set('factory_location.0', $state['lat']);
                                $set('factory_location.1', $state['lng']);
                            })
                            ->afterStateHydrated(function (Get $get, Set $set): void {
                                $set('location', ['lat' => $get('factory_location.0'), 'lng' => $get('factory_location.1')]);
                            })
                            ->extraStyles([
                                'min-height: 50vh',
                                'border-radius: 16px'
                            ])
                            ->liveLocation()
                            ->showMarker()
                            ->markerColor("#22c55eff")
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable()
                            ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                            ->zoom(15)
                            ->detectRetina()
                            ->showMyLocationButton()
                            ->extraTileControl([])
                            ->extraControl([
                                'zoomDelta'           => 1,
                                'zoomSnap'            => 2,
                            ]),
                        Forms\Components\Fieldset::make('تنظیم ')
                            ->schema([
                                Forms\Components\Placeholder::make('lat')
                                    ->translateLabel()
                                    ->content( fn(Get $get) => $get('location.lat')),
                                Forms\Components\Placeholder::make('lng')
                                    ->translateLabel()
                                    ->content(fn(Get $get) => $get('location.lng')),
                                Forms\Components\Placeholder::make('address')
                                    ->translateLabel()
                                    ->columnSpanFull()
                                    ->content(fn(Get $get) => self::reverseGeocoding($get('location.lat'),$get('location.lng'))->getData(true)['formatted_address']),
                                Forms\Components\Hidden::make('factory_location.0'),
                                Forms\Components\Hidden::make('factory_location.1')
                            ])->columnSpan(1),
                    ])->columns(),
            ]);
    }
}
