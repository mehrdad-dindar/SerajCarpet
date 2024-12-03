<?php

namespace App\Forms\Components;

use App\Traits\Neshan;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Cache;

class AddressForm
{
    use Neshan;

    public static function schema(): array
    {
        return [
            Forms\Components\Grid::make('Address')->schema([
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->columnSpan(7)
                    ->helperText(fn (Get $get, $state) => self::getHint('address', $get) ?? $state)
                    ->label(__('Full Address'))
                    ->hintAction(
                        Action::make('is_suggested')
                            ->translateLabel()
                            ->icon('heroicon-s-sparkles')
                            ->action(function (Get $get, Set $set) {
                                $address = self::getHint(field: null, get: $get);
                                $set('address', $address['formatted_address']);
                                $set('municipality_zone', $address['municipality_zone']);
                                $set('neighbourhood', $address['neighbourhood']);
                            })
                    ),
                Forms\Components\TextInput::make('no')
                    ->required()
                    ->columnSpan(1)
                    ->label(__('No.')),
                Forms\Components\TextInput::make('floor')
                    ->columnSpan(1)
                    ->label(__('Floor')),
                Forms\Components\TextInput::make('unit')
                    ->columnSpan(1)
                    ->label(__('Unit')),
                Forms\Components\Hidden::make('latitude')
                    ->required()
                    ->label(__('Latitude')),
                Forms\Components\Hidden::make('longitude')
                    ->required()
                    ->label(__('longitude')),
                Forms\Components\Toggle::make('is_active')
                    ->inline(false)
                    ->onColor('success')
                    ->offColor('danger')
                    ->label(__('Active'))
                    ->default(true),
                Forms\Components\Hidden::make('municipality_zone'),
                Forms\Components\Hidden::make('neighbourhood'),
            ])->columns(12),
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
                })
                ->afterStateHydrated(function ($state, $record, Set $set): void {
                    is_null($record) ?: $set(
                        'location',
                        [
                            'lat' => $record->latitude,
                            'lng' => $record->longitude,
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
        ];
    }

    public static function getHint(?string $field, Get $get)
    {
        $latitude = $get('latitude');
        $longitude = $get('longitude');

        if (! $latitude || ! $longitude) {
            return '';
        }
        if ($cachedAddress = self::getAddressTemp()) {
            if ($cachedAddress['latitude'] == $latitude && $cachedAddress['longitude'] == $longitude) {
                return is_null($field) ? $cachedAddress : self::getFieldValue($field, $cachedAddress);
            }
        }

        $neshan = self::reverseGeocoding($latitude, $longitude)->getData(true);
        $neshan['latitude'] = $latitude;
        $neshan['longitude'] = $longitude;

        self::setAddressTemp($neshan);

        return is_null($field) ? $neshan : self::getFieldValue($field, $neshan);
    }

    protected static function getAddressTemp(): ?array
    {
        return Cache::get('address_temp');
    }

    private static function getFieldValue(string $field, array $neshan): string
    {
        return match ($field) {
            'address' => $neshan['formatted_address'] ?? '',
            'state' => $neshan['state'] ?? '',
            'city' => $neshan['city'] ?? '',
            'municipality_zone' => $neshan['municipality_zone'] ?? '',
            'neighbourhood' => $neshan['neighbourhood'] ?? '',
            default => '',
        };
    }

    private static function setAddressTemp(array $data): void
    {
        Cache::put('address_temp', $data, now()->addMinutes(10));
    }

    public static function mutate(array $data): array
    {
        if (abs($data['latitude'] - 35.699686301252) < 0.000000000001 &&
            abs($data['longitude'] - 51.337738037109) < 0.000000000001) {
            if ($data['address']) {
                $location = self::geocoding($data['address'].' پلاک '.$data['no']);
                if (! is_null($location)) {
                    $data['latitude'] = $location['latitude'];
                    $data['longitude'] = $location['longitude'];
                    $addressData = self::reverseGeocoding($data['latitude'], $data['longitude'])->getData(true);
                    if (isset($addressData["status"]) && $addressData["status"] == "OK") {
                        $data['municipality_zone'] = $addressData['municipality_zone'];
                        $data['neighbourhood'] = $addressData['neighbourhood'];
                    }
                }
            }
        }
        unset($data['location']);

        return $data;
    }
}
