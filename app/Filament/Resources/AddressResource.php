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
                Forms\Components\Section::make('Customer')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label(__('Customer Name')),
                    ]),
                Forms\Components\Section::make('Location')
                    ->schema([
                        Forms\Components\TextInput::make('state')
                            ->required()
                            ->label(__('State')),
                        Forms\Components\TextInput::make('city')
                            ->required()
                            ->label(__('City')),
                        Forms\Components\TextInput::make('address')
                            ->required()
                            ->label(__('Full Address')),
                        Forms\Components\TextInput::make('no')
                            ->required()
                            ->label(__('No.')),
                        Forms\Components\TextInput::make('floor')
                            ->required()
                            ->label(__('Floor')),
                        Forms\Components\TextInput::make('unit')
                            ->label(__('Unit')),
                        Forms\Components\Hidden::make('latitude')
                            ->required()
                            ->label(__('Latitude')),
                        Forms\Components\Hidden::make('longitude')
                            ->required()
                            ->label(__('longitude')),
                        Forms\Components\Checkbox::make('is_active')
                            ->label(__('Active'))
                            ->default(true),
                        Map::make('location')
                            ->hint('با کشیدن و اسکرول ')
                            ->label('Location')
                            ->columnSpanFull()
                            ->default([
                                'lat' => 35.699741844984004,
                                'lng' => 51.33805990219117
                            ])
                            ->afterStateUpdated(function (Get $get, Set $set, string|array|null $old, ?array $state): void {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                                $addressResource = new AddressResource();
                                $addressResource->getFullAddress($state['lat'], $state['lng'], $set);
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

    public function getFullAddress(mixed $latitude, mixed $longitude, Set $set)
    {
        $neshan = $this->reverseGeocoding($latitude, $longitude)->getData();
        $set('address', $neshan->formatted_address);
        $set('state', $neshan->state);
        $set('city', $neshan->city);
//        dd($latitude,$longitude);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\CheckboxColumn::make('is_active')->sortable(),
                Tables\Columns\TextColumn::make('customer.name')->searchable(),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('address')->toggleable()->searchable(),
                Tables\Columns\TextColumn::make('googleMap')
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
