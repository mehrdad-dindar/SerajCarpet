<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
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
use Illuminate\Database\Eloquent\Model;

class AddressResource extends Resource
{
    use Neshan;

    protected static ?string $model = Address::class;

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'آدرس ها';

    protected static ?string $pluralModelLabel = 'آدرس ها';

    protected static ?string $modelLabel = 'آدرس';

    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
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
                Tables\Columns\TextColumn::make('area')
                    ->badge()->color(fn ($state, $record): string => $record ? 'info' : 'danger')
                    ->getStateUsing(fn ($record) => $record ? 'منطقه '.$record->municipality_zone : 'X')
                    ->description(fn ($record) => $record ? 'محله '.$record->neighbourhood : 'فاقد آدرس')
                    ->sortable()
                    ->toggleable()
                    ->alignCenter()
                    ->searchable()
                    ->translateLabel(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active Address')),
            ])
            ->actions([
                Tables\Actions\Action::make('location')
                    ->translateLabel()
                    ->color('info')
                    ->form([
                        Forms\Components\Fieldset::make('Address')
                            ->translateLabel()
                            ->schema([
                                Forms\Components\Placeholder::make('State / City')
                                    ->translateLabel()
                                    ->content(fn ($record) => $record->state.' / '.$record->city),
                                Forms\Components\Placeholder::make('Area / Neighbourhood')
                                    ->translateLabel()
                                    ->content(fn ($record) => $record->municipality_zone.' / '.$record->neighbourhood),
                                Forms\Components\Placeholder::make('fullـaddress')
                                    ->label(__('Full Address'))
                                    ->translateLabel()
                                    ->content(function ($record) {
                                        $address = [
                                            $record->address,
                                            $record->no ? 'پلاک '.$record->no : null,
                                            $record->floor ? 'طبقه '.$record->floor : null,
                                            $record->unit ? 'واحد '.$record->unit : null,
                                        ];

                                        return implode(' - ', $address);
                                    }),
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('Directions')
                                        ->translateLabel()
                                        ->icon('heroicon-o-arrow-top-right-on-square')
                                        ->url(function (Model $record): string {
                                            return sprintf(
                                                'https://nshn.ir/?lat=%s&lng=%s',
                                                $record->latitude,
                                                $record->longitude
                                            );
                                        }),
                                ]),
                            ]),
                        Map::make('location')
                            ->label(__('Location'))
                            ->columnSpanFull()
                            ->default(function ($record) {
                                return [
                                    'lat' => $record->latitude,
                                    'lng' => $record->longitude,
                                ];
                            })
                            ->extraStyles([
                                'min-height: 50vh',
                                'border-radius: 16px',
                            ])
                            ->showMarker()
                            ->markerColor('#e45757')
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable(false)
                            ->detectRetina()
                            ->showMyLocationButton()
                            ->zoom(15)
                            ->tilesUrl('http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}'),
                    ])
                    ->disabledForm()
                    ->icon('heroicon-o-map-pin'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

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
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('Customer Name'))
                                            ->required(),
                                        Forms\Components\TextInput::make('phone')
                                            ->label(__('Customer Phone'))
                                            ->unique()
                                            ->required(),
                                    ])
                                    ->columns(),
                            ])
                            ->required(),
                    ]),
                Forms\Components\Section::make('آدرس')
                    ->schema([
                        Forms\Components\Grid::make('Address')->schema([
                            Forms\Components\TextInput::make('state')
                                ->required()
                                ->columnSpan(2)
                                ->helperText(fn (Get $get) => self::getHint('state', $get))
                                ->label(__('State')),
                            Forms\Components\TextInput::make('city')
                                ->required()
                                ->columnSpan(2)
                                ->helperText(fn (Get $get) => self::getHint('city', $get))
                                ->label(__('City')),
                            Forms\Components\TextInput::make('address')
                                ->required()
                                ->columnSpan(7)
                                ->helperText(fn (Get $get) => self::getHint('address', $get))
                                ->label(__('Full Address')),
                            Forms\Components\Toggle::make('is_suggested')
                                ->translateLabel()
                                ->onIcon('heroicon-s-sparkles')
                                ->offIcon('heroicon-o-star')
                                ->inline(false)
                                ->reactive()
                                ->default(false)
                                ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                    if ($state) {
                                        $neshan = self::getHint(field: null, get: $get, all: true);

                                        $set('address', $neshan->formatted_address);
                                        $set('state', $neshan->state);
                                        $set('city', $neshan->city);
                                        $set('municipality_zone', $neshan->municipality_zone);
                                        $set('neighbourhood', $neshan->neighbourhood);
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
                                'lng' => 51.33805990219117,
                            ])
                            ->afterStateUpdated(function (Get $get, Set $set, $old, $state): void {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                                if ($get('is_suggested')) {
                                    $data = self::getHint(field: null, get: $get, all: true);
                                    if ($data->status == 'OK') {
                                        $set('state', $data->state);
                                        $set('city', $data->city);
                                        $set('address', $data->formatted_address);
                                        $set('municipality_zone', $data->municipality_zone);
                                        $set('neighbourhood', $data->neighbourhood);
                                    }
                                }
                            })
                            ->afterStateHydrated(function ($state, $record, Set $set): void {
                                is_null($record) ?: $set(
                                    'location',
                                    [
                                        'lat' => $record->latitude,
                                        'lng' => $record->longitude
                                    ]
                                );
                            })
                            ->extraStyles([
                                'min-height: 50vh',
                                'border-radius: 16px',
                            ])
                            ->liveLocation()
                            ->showMarker()
                            ->markerColor('#40E0D0')
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable()
                            ->detectRetina()
                            ->showMyLocationButton()
                            ->zoom(11)
                            ->tilesUrl('http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}'),
                    ])->columns(3),
            ]);
    }

    public static function getHint(?string $field, Get $get, bool $all = false)
    {
        $latitude = $get('latitude');
        $longitude = $get('longitude');

        if (! $latitude || ! $longitude) {
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

    public static function getRelations(): array
    {
        return [
            CustomerRelationManager::class,
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
