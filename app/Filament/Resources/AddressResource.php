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
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('مشتری')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label(__('Customer Name')),
                    ]),
                Forms\Components\Section::make('آدرس')
                    ->schema([
                        Forms\Components\Grid::make('Address')->schema([
                            Forms\Components\TextInput::make('state')
                                ->required()
                                ->columnSpan(2)
                                ->hint('')
                                ->label(__('State')),
                            Forms\Components\TextInput::make('city')
                                ->required()
                                ->columnSpan(2)
                                ->hint('')
                                ->label(__('City')),
                            Forms\Components\TextInput::make('address')
                                ->required()
                                ->columnSpan(7)
                                ->hint('')
                                ->label(__('Full Address')),
                            Forms\Components\Toggle::make('suggest')
                                ->onIcon('heroicon-s-sparkles')
                                ->offIcon('heroicon-o-star')
                                ->inline(false),
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
                                $addressResource = new AddressResource();
                                $addressResource->getFullAddress($state['lat'], $state['lng'], $set, $get);
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

    public function getFullAddress(mixed $latitude, mixed $longitude, Set $set, Get $get)
    {
        $neshan = $this->reverseGeocoding($latitude, $longitude)->getData();

        if ($get('suggest')) {
            $set('address', $neshan->formatted_address);
            $set('state', $neshan->state);
            $set('city', $neshan->city);
        } else {
            $set('address',$get('address'))->hint($neshan->formatted_address);
            $set('state', $get('state'))->hint($neshan->state);
            $set('city', $get('city'))->hint($neshan->city);
        }
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
