<?php

namespace App\Forms\Components;

use App\Enums\SmsPattern;
use App\Jobs\SendSmsJob;
use App\Models\Customer;
use App\Traits\Neshan;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class AddressForm
{
    use Neshan;

    const AUTO = 0;

    const DRIVER = 1;

    const CUSTOMER = 2;

    const MANUAL = 3;

    public static function schema(): array
    {
        return [
            Forms\Components\ToggleButtons::make('location_type')
                ->label(__('Location registration type'))
                ->options([
                    self::AUTO => __('Auto'),
                    self::DRIVER => __('Driver'),
                    self::CUSTOMER => __('Customer'),
                    self::MANUAL => __('Manual'),
                ])
                ->icons([
                    self::AUTO => 'heroicon-o-sparkles',
                    self::DRIVER => 'heroicon-o-truck',
                    self::CUSTOMER => 'heroicon-o-user',
                    self::MANUAL => 'heroicon-o-map-pin',
                ])
                ->helperText(function ($state) {
                    return match ((int) $state) {
                        self::AUTO => __('Record customer location based on entered address'),
                        self::DRIVER => __("Recording the driver's location at the customer's location"),
                        self::CUSTOMER => __('Location registration by the customer'),
                        self::MANUAL => __('Manually record customer location'),
                    };
                })
                ->live()
                ->default(self::AUTO)
                ->grouped(),
            Forms\Components\Grid::make('AddressGrid')
                ->visible(fn (Get $get) => $get('location_type') != self::CUSTOMER)
                ->schema([
                    Forms\Components\TextInput::make('address')
                        ->required(fn (Get $get) => $get('location_type') != self::CUSTOMER)
                        ->columnSpan(5)
                        ->helperText(function (Get $get, $state) {
                            if ($get('location_type') == self::MANUAL) {
                                $address = self::getHint($get) ?? $state;
                                return $address['formatted_address'];
                            }

                            return null;
                        })
                        ->label(__('Full Address'))
                        ->hintAction(
                            Action::make('is_suggested')
                                ->translateLabel()
                                ->visible(fn (Get $get) => $get('location_type') == self::MANUAL)
                                ->icon('heroicon-s-sparkles')
                                ->action(function (Get $get, Set $set) {
                                    $address = self::getHint($get);
                                    if (! empty($address)) {
                                        $set('address', $address['formatted_address']);
                                        $set('municipality_zone', $address['municipality_zone']);
                                        $set('neighbourhood', $address['neighbourhood']);
                                        $set('latitude', $address['latitude']);
                                        $set('longitude', $address['longitude']);
                                    }
                                }),
                        ),
                    Forms\Components\TextInput::make('no')
                        ->required(fn (Get $get) => $get('location_type') != self::CUSTOMER)
                        ->columnSpan(1)
                        ->label(__('No.')),
                    Forms\Components\TextInput::make('floor')
                        ->columnSpan(1)
                        ->label(__('Floor')),
                    Forms\Components\TextInput::make('unit')
                        ->columnSpan(1)
                        ->label(__('Unit')),
                    Forms\Components\Hidden::make('latitude')
                        ->default(35.69974184)
                        ->label(__('Latitude')),
                    Forms\Components\Hidden::make('longitude')
                        ->default(51.33805990)
                        ->label(__('longitude')),
                    Forms\Components\Toggle::make('is_active')
                        ->inline(false)
                        ->onColor('success')
                        ->offColor('danger')
                        ->label(__('Active'))
                        ->default(true),
                    Forms\Components\Hidden::make('municipality_zone'),
                    Forms\Components\Hidden::make('neighbourhood'),
                ])->columns(9),
            Map::make('location')
                ->visible(fn (Get $get) => $get('location_type') == self::MANUAL)
                ->hint('با کشیدن و اسکرول موقعیت مورد نظر را انتخاب کنید')
                ->label(__('Location'))
                ->columnSpanFull()
                ->defaultLocation(latitude: 35.69974184, longitude: 51.33805990)
                ->afterStateUpdated(function (Set $set, $state): void {
                    $set('latitude', $state['lat']);
                    $set('longitude', $state['lng']);
                })
                ->live(debounce: 5000)
                ->afterStateHydrated(function ($state, $record, Set $set): void {
                    $set('location', ['lat' => $record?->latitude, 'lng' => $record?->longitude]);
                })
                ->extraStyles([
                    'min-height: 50vh',
                    'border-radius: 16px',
                ])
                ->liveLocation(false)
                ->showMarker()
                ->markerColor('#40E0D0')
                ->showFullscreenControl()
                ->showZoomControl()
                ->draggable()
                ->detectRetina()
                ->showMyLocationButton()
                ->zoom(11)
                ->tilesUrl('https://tile.openstreetmap.org/{z}/{x}/{y}.png'),
        ];
    }

    public static function getHint($get)
    {
        $latitude = $get('latitude');
        $longitude = $get('longitude');

        if (! $latitude || ! $longitude) {
            return '';
        }
        if ($cachedAddress = self::getAddressTemp()) {
            if ($cachedAddress['latitude'] == $latitude && $cachedAddress['longitude'] == $longitude) {
                return $cachedAddress;
            }
        }

        $neshan = self::reverseGeocoding($latitude, $longitude)->getData(true);

        if (is_array($neshan) && $neshan !== [] && ($neshan['status'] ?? null) === 'OK') {
            $neshan['latitude'] = $latitude;
            $neshan['longitude'] = $longitude;

            return self::renderAddress($neshan);
        }

        return [];
    }

    protected static function getAddressTemp(): ?array
    {
        return Cache::get('address_temp');
    }

    private static function setAddressTemp(array $data): array
    {
        Cache::put('address_temp', $data, now()->addMinutes(10));

        return $data;
    }

    private static function renderAddress(array $data): array
    {
        if (isset($data['formatted_address']) && str_starts_with($data['formatted_address'], 'تهران')) {
            $tehranPrefix = "تهران،";
            if (str_starts_with($data['formatted_address'], $tehranPrefix)) {
                $data['formatted_address'] = trim(substr($data['formatted_address'], strlen($tehranPrefix)));
            }
        }

        self::setAddressTemp($data);

        return $data;
    }

    public static function mutate(array $data): array
    {
        $data = match ((int) $data['location_type']) {
            self::CUSTOMER => self::getAddressFromCustomer($data),
            self::MANUAL => self::getAddressInfo($data, ['latitude' => $data['latitude'], 'longitude' => $data['longitude']]),
            default => self::getAddressLocation($data),
        };

        unset($data['location']);

        return $data;
    }

    private static function getAddressFromCustomer(array $data): array
    {
        $data['address'] = '** منتظر ثبت توسط مشتری **';
        $customer = Customer::find($data['customer_id']);
        SendSmsJob::dispatch(
            $customer->phone,
            SmsPattern::SET_LOCATION,
            [
                $customer->name,
                $customer->getHashedId(),
            ]
        );

        return $data;
    }

    private static function getAddressInfo($data, array $location): array
    {
        $data['latitude'] = $location['latitude'];
        $data['longitude'] = $location['longitude'];
        $addressData = self::reverseGeocoding($data['latitude'], $data['longitude'])->getData(true);

        if (isset($addressData['status']) && $addressData['status'] == 'OK') {
            $data['municipality_zone'] = $addressData['municipality_zone'];
            $data['neighbourhood'] = $addressData['neighbourhood'];
        } else {
            Notification::make()
                ->title('منطقه و محله شناسایی نشد!')
                ->danger()
                ->send();
        }

        return $data;
    }

    private static function getAddressLocation(array $data): array
    {
        $location = self::geocoding('تهران '.$data['address'].' پلاک '.$data['no']);
        if (empty($location)) {
            Notification::make()
                ->title('سیستم قادر به شناسایی آدرس نشد!')
                ->danger()
                ->send();

            return $data;
        }

        return self::getAddressInfo($data, $location);
    }
}
