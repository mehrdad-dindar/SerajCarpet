<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Filament\Resources\AddressResource\RelationManagers;
use App\Filament\Resources\AddressResource\RelationManagers\CustomerRelationManager;
use App\Models\Address;
use App\Traits\Neshan;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Colors\Color;

class AddressResource extends Resource
{
    use Neshan;

    protected static ?string $model = Address::class;

    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'آدرس ها';
    protected static ?string $pluralModelLabel = "آدرس ها";
    protected static ?string $modelLabel = 'آدرس';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('مشتری')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->translateLabel()
                            ->label('customer')
                            ->prefixIcon('heroicon-o-user')
                            ->relationship('customer', 'id_name')
                            ->searchable()
                            ->preload()
                            ->live()
//                            ->afterStateUpdated(fn(Set $set) => $set('address_id', null))
                            ->createOptionForm([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make("name")
                                            ->label(__("Customer Name"))
                                            ->required(),
                                        Forms\Components\TextInput::make("phone")
                                            ->label(__("Customer Phone"))
                                            ->unique()
                                            ->required(),
                                    ])
                                    ->columns()
                            ])
                            ->required(),
                    ]),
                Forms\Components\Section::make('آدرس')
                    ->schema([
                        Forms\Components\Grid::make('Address')->schema([
                            Forms\Components\TextInput::make('state')
                                ->required()
                                ->columnSpan(2)
                                ->helperText(fn(Get $get) => self::getHint('state', $get))
                                ->label(__('State')),
                            Forms\Components\TextInput::make('city')
                                ->required()
                                ->columnSpan(2)
                                ->helperText(fn(Get $get) => self::getHint('city', $get))
                                ->label(__('City')),
                            Forms\Components\TextInput::make('address')
                                ->required()
                                ->columnSpan(7)
                                ->helperText(fn(Get $get) => self::getHint('address', $get))
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
                                            $neshan = self::reverseGeocoding($latitude, $longitude)->getData();
                                            $set('address', $neshan->formatted_address);
                                            $set('state', $neshan->state);
                                            $set('city', $neshan->city);
                                            $set('municipality_zone', $neshan->municipality_zone);
                                            $set('neighbourhood', $neshan->neighbourhood);
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
                            Forms\Components\Hidden::make('municipality_zone'),
                            Forms\Components\Hidden::make('neighbourhood'),
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
                                    $data = self::getHint(field: null,get: $get, all: true);
                                    if ($data->status == "OK") {
                                        $set('state', $data->state);
                                        $set('city', $data->city);
                                        $set('address', $data->formatted_address);
                                        $set('municipality_zone', $data->municipality_zone);
                                        $set('neighbourhood', $data->neighbourhood);
                                    }
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
                    ])->columns(3),
            ]);
    }

    public static function getHint(?string $field, Get $get, bool $all = false)
    {
        $latitude = $get('latitude');
        $longitude = $get('longitude');

        if (!$latitude || !$longitude) {
            return '';
        }

        $neshan = self::reverseGeocoding($latitude, $longitude)->getData();
        if ($all) {
            return $neshan;
        }
        return self::getFieldValue($field, $neshan);
    }

    private static function getFieldValue(string $field, $neshan): string
    {
        return match ($field) {
            'address' => $neshan->formatted_address ?? '',
            'state' => $neshan->state ?? '',
            'city' => $neshan->city ?? '',
            'municipality_zone' => $neshan->municipality_zone ?? '',
            'neighbourhood' => $neshan->neighbourhood ?? '',
            default => '',
        };
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\CheckboxColumn::make('is_active')
                    ->label(__('Active'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('Customer Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('city')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('address')->toggleable()->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('googleMap')
                    ->translateLabel()
                    ->badge()
                    ->toggleable()
                    ->icon('heroicon-o-map-pin')
                    ->url(function (Model $record): string {
                        return "https://www.google.com/maps?q={$record->latitude},{$record->longitude}";
                    }),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active Address')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CustomerRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}
