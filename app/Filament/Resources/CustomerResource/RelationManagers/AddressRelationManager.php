<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\AddressResource;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';
    protected static ?string $label = 'آدرس';
    protected static ?string $title = 'آدرس ها';



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make('Address')->schema([
                    Forms\Components\TextInput::make('state')
                        ->required()
                        ->columnSpan(2)
                        ->helperText(fn(Get $get) => AddressResource::getHint('state', $get))
                        ->label(__('State')),
                    Forms\Components\TextInput::make('city')
                        ->required()
                        ->columnSpan(2)
                        ->helperText(fn(Get $get) => AddressResource::getHint('city', $get))
                        ->label(__('City')),
                    Forms\Components\TextInput::make('address')
                        ->required()
                        ->columnSpan(7)
                        ->helperText(fn(Get $get) => AddressResource::getHint('address', $get))
                        ->label(__('Full Address')),
                    Forms\Components\Toggle::make('is_suggested')
                        ->onIcon('heroicon-s-sparkles')
                        ->offIcon('heroicon-o-star')
                        ->inline(false)
                        ->reactive()
                        ->default(false)
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if ($state) {
                                $latitude = $get('latitude');
                                $longitude = $get('longitude');
                                if ($latitude && $longitude) {
                                    $neshan = AddressResource::reverseGeocoding($latitude, $longitude)->getData();
                                    $set('address', $neshan->formatted_address);
                                    $set('state', $neshan->state);
                                    $set('city', $neshan->city);
                                }
                            }
                        }),
                    Forms\Components\TextInput::make('no')
                        ->required()
                        ->columnSpan(2)
                        ->label(__('No.')),
                    Forms\Components\TextInput::make('floor')
                        ->required()
                        ->columnSpan(2)
                        ->label(__('Floor')),
                    Forms\Components\TextInput::make('unit')
                        ->columnSpan(2)
                        ->label(__('Unit')),
                    Forms\Components\Hidden::make('latitude')
                        ->required()
                        ->label(__('Latitude')),
                    Forms\Components\Hidden::make('longitude')
                        ->required()
                        ->label(__('longitude')),
                ])->columns(12),

                Forms\Components\Checkbox::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
                Map::make('location')
                    ->hint('با کشیدن و اسکرول موقعیت مورد نظر را انتخاب کنید')
                    ->label(__('Location'))
                    ->columnSpanFull()
                    ->default([
                        'lat' => 35.699741844984004,
                        'lng' => 51.33805990219117
                    ])
                    ->afterStateUpdated(function (Get $get, Set $set, string|array|null $old, ?array $state): void {
                        $set('latitude', $state['lat']);
                        $set('longitude', $state['lng']);
                        if ($get('is_suggested')) {
                            $set('state', AddressResource::getHint('state', $get));
                            $set('city', AddressResource::getHint('city', $get));
                            $set('address', AddressResource::getHint('address', $get));
                        }
                    })
                    ->afterStateHydrated(function ($state, $record, Set $set): void {
                        is_null($record) ?: $set('location', ['lat' => $record->latitude, 'lng' => $record->longitude]);
                    })
                    ->extraStyles([
                        'min-height: 50vh',
                        'border-radius: 16px'
                    ])
                    ->liveLocation()
                    ->showMarker()
                    ->markerColor("#40E0D0")
                    ->showFullscreenControl()
                    ->showZoomControl()
                    ->draggable()
                    ->detectRetina()
                    ->showMyLocationButton()
                    ->zoom(11)
                    ->tilesUrl("http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}")
            ])->columns(3);

    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('city')
            ->columns([
                Tables\Columns\CheckboxColumn::make('is_active')->sortable(),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('googleMap')
                    ->badge()
                    ->toggleable()
                    ->icon('heroicon-o-map-pin')
                    ->url(function (Model $record): string {
                        return "https://www.google.com/maps?q={$record->latitude},{$record->longitude}";
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
