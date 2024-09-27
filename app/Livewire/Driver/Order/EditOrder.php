<?php

namespace App\Livewire\Driver\Order;

use App\Models\Option;
use App\Models\Order;
use App\Models\Property;
use App\Traits\Neshan;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Component;
use WireUi\Components\Badge\Mini;

class EditOrder extends Component implements HasForms
{
    use InteractsWithForms;
    use Neshan;

    public Order $order;

    public ?array $data = [];

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->form->fill($order->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->order)
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Customer')
                        ->icon('heroicon-o-user')
                        ->translateLabel()
                        ->description(__('Check customer information'))
                        ->schema([
                            Placeholder::make('customer.name')
                                ->label(__('Customer Name'))
                                ->content(fn (Order $order): string => $order->customer->name),
                            Placeholder::make('customer.phone')
                                ->label(__('Customer Phone'))
                                ->content(fn (Order $order): string => $order->customer->phone),
                            Placeholder::make('customer_id')
                                ->label(__('Customer ID'))
                                ->content(fn (Order $order): string => '#'.$order->customer->id),
                        ])->columns(),
                    Wizard\Step::make('Address')
                        ->icon('heroicon-o-map-pin')
                        ->translateLabel()
                        ->description(__('Check or edit customer address'))
                        ->schema([
                            Fieldset::make('customer_address')
                                ->schema([
                                    Placeholder::make('Full Address')
                                        ->content(fn (Order $order): string => $order->address->getFullAddress())
                                        ->label(__('Full Address')),
                                    Map::make('current_location')
                                        ->label(__('Location'))
                                        ->defaultLocation(latitude: 40.4168, longitude: -3.7038)
                                        ->afterStateHydrated(function ($state, Order $order, Set $set): void {
                                            $set('current_location', [
                                                'lat' => $order->address->latitude,
                                                'lng' => $order->address->longitude,
                                            ]);
                                        })
                                        ->extraStyles([
                                            'min-height: 100px',
                                            'max-height: 100px',
                                            'border-radius: 12px',
                                        ])
                                        ->showMarker()
                                        ->showFullscreenControl(false)
                                        ->markerColor('#FBBC04')
                                        ->showZoomControl()
                                        ->tilesUrl('http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}')
                                        ->zoom(15)
                                        ->draggable(false),
                                ])
                                ->columns()
                                ->label(__("Customer's Address"))
                                ->translateLabel(),
                            Toggle::make('edit_address')
                                ->live()
                                ->label(__('Need to edit?')),
                            Fieldset::make('Address')->schema([
                                TextInput::make('state')
                                    ->formatStateUsing(fn (Order $order) => $order->address->state)
                                    ->required()
                                    ->helperText(fn (Get $get) => self::getHint('state', $get))
                                    ->label(__('State')),
                                TextInput::make('city')
                                    ->formatStateUsing(fn (Order $order) => $order->address->city)
                                    ->required()
                                    ->helperText(fn (Get $get) => self::getHint('city', $get))
                                    ->label(__('City')),
                                Textarea::make('address')
                                    ->formatStateUsing(fn (Order $order) => $order->address->address)
                                    ->autosize()
                                    ->required()
                                    ->columnSpanFull()
                                    ->helperText(fn (Get $get) => self::getHint('address', $get))
                                    ->label(__('Full Address')),
                                TextInput::make('no')
                                    ->formatStateUsing(fn (Order $order) => $order->address->no)
                                    ->required()
                                    ->label(__('No.')),
                                TextInput::make('floor')
                                    ->formatStateUsing(fn (Order $order) => $order->address->floor)
                                    ->required()
                                    ->label(__('Floor')),
                                TextInput::make('unit')
                                    ->formatStateUsing(fn (Order $order) => $order->address->unit)
                                    ->label(__('Unit')),
                                Hidden::make('latitude')
                                    ->required()
                                    ->label(__('Latitude')),
                                Hidden::make('longitude')
                                    ->required()
                                    ->label(__('longitude')),
                                Hidden::make('municipality_zone'),
                                Hidden::make('neighbourhood'),
                                Map::make('location')
                                    ->hint('با کشیدن و اسکرول موقعیت مورد نظر را انتخاب کنید')
                                    ->label(__('Location'))
                                    ->columnSpanFull()
                                    ->default([
                                        'lat' => 35.699741844984004,
                                        'lng' => 51.33805990219117,
                                    ])
                                    ->afterStateUpdated(function (Get $get, Set $set, $old, ?array $state): void {
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
                                    ->afterStateHydrated(function ($state, Order $order, Set $set): void {
                                        $set('location', [
                                            'lat' => $order->address->latitude,
                                            'lng' => $order->address->longitude,
                                        ]);
                                    })
                                    ->extraStyles([
                                        'min-height: 50vh',
                                        'border-radius: 16px',
                                    ])
                                    ->showFullscreenControl()
                                    ->liveLocation(true, true, 5000)
                                    ->showMyLocationButton(true)
                                    ->showMarker()
                                    ->markerColor('#FBBC04')
                                    ->showZoomControl()
                                    ->draggable()
                                    ->detectRetina()
                                    ->zoom(11)
                                    ->tilesUrl('http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}'),
                                Toggle::make('is_suggested')
                                    ->translateLabel()
                                    ->onIcon('heroicon-s-sparkles')
                                    ->offIcon('heroicon-o-star')
                                    ->helperText('با استفاده از این گزینه آدرس از نقشه خوانده می‌شود')
                                    ->reactive()
                                    ->default(false)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
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
                                    }),
                            ])
                                ->columns()
                                ->reactive()
                                ->visible(fn ($state) => $state['edit_address']),
                        ]),
                    Wizard\Step::make('Order Items')
                        ->icon('heroicon-o-list-bullet')
                        ->translateLabel()
                        ->description(__('Check or edit order items'))
                        ->schema([
                            Repeater::make('items')
                                ->label(__('Items'))
                                ->translateLabel()
                                ->relationship('items')
                                ->defaultItems(1)
                                ->columns(12)
                                ->schema([
                                    Select::make('property_id')
                                        ->label(__('Select Service'))
                                        ->translateLabel()
                                        ->required()
                                        ->reactive()
                                        ->helperText(
                                            fn (Get $get) => Property::find($get('property_id'))->helperText ?? ''
                                        )
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $dimensions = (int) $get('dimensions') ?? 1;
                                            $quantity = $get('quantity');
                                            $price = Property::find($state)->price;

                                            $set('sub_total', $dimensions * $quantity * $price);
                                            $set('unit_price', $price);
                                        })
                                        ->relationship('property', 'fullTitle')
                                        ->getOptionLabelFromRecordUsing(function (Property $property) {
                                            return $property->fullTitle;
                                        })
                                        ->columnSpan(3),
                                    Select::make('dimensions')
                                        ->options(function ($state, Get $get) {
                                            if ($propertyId = $get('property_id')) {
                                                $dimensions = Property::find($propertyId)->dimensions ?? [];

                                                return array_combine($dimensions, $dimensions);
                                            }

                                            return [];
                                        })
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $dimensions = (int) $state ?? 1;
                                            $quantity = $get('quantity');
                                            $price = Property::find($get('property_id'))->price;

                                            $set('sub_total', $dimensions * $quantity * $price);
                                            $set('unit_price', $price);
                                        })
                                        ->translateLabel()
                                        ->live()
                                        ->hidden(function ($get, $set) {
                                            $propertyId = $get('property_id');
                                            $property = $propertyId ? Property::find($propertyId) : null;

                                            if (! $propertyId || ! $property || ! $property->dimensions) {
                                                $set('dimensions', 1);

                                                return true;
                                            }

                                            return false;
                                        })
                                        ->columnSpan(2),
                                    TextInput::make('quantity')
                                        ->label(__('Quantity'))
                                        ->translateLabel()
                                        ->numeric()
                                        ->default(1)
                                        ->minValue(1)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            if ($get('property_id') !== null) {
                                                $dimensions = (int) $get('dimensions') ?? 1;
                                                $price = Property::find($get('property_id'))->price;

                                                $set('sub_total', $dimensions * $state * $price);
                                                $set('unit_price', $price);
                                            }
                                        })
                                        ->columnSpan(2)
                                        ->required(),
                                    Hidden::make('unit_price'),
                                    TextInput::make('sub_total')
                                        ->label(__('Sub Total Price'))
                                        ->readOnly()
                                        ->dehydrated()
                                        ->translateLabel()
                                        ->integer()
                                        ->required()
                                        ->columnSpan(4)
                                        ->mask(RawJs::make('$money($input)'))
                                        ->suffix('تومان')
                                        ->stripCharacters('.')
                                        ->mutateStateForValidationUsing(fn ($state) => str_replace(',', '', $state))
                                        ->mutateDehydratedStateUsing(fn ($state) => str_replace(',', '', $state)),
                                ])
                                ->columnSpanFull(),
                            Repeater::make('other_items')
                                ->label(__('Other Items'))
                                ->translateLabel()
                                ->relationship()
                                ->defaultItems(0)
                                ->hiddenLabel()
                                ->columns(12)
                                ->schema([
                                    Hidden::make('is_custom')->default(1),
                                    TextInput::make('title')
                                        ->columnSpan(4),
                                    TextInput::make('quantity')
                                        ->label(__('Quantity'))
                                        ->translateLabel()
                                        ->numeric()
                                        ->default(1)
                                        ->minValue(1)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            if ($price = str_replace(',', '', $get('unit_price'))) {
                                                $set('sub_total', (int) $state * (int) $price);
                                            }
                                        })
                                        ->columnSpan(2)
                                        ->required(),
                                    TextInput::make('unit_price')
                                        ->label(__('Unit Price'))
                                        ->columnSpan(3)
                                        ->mask(RawJs::make('$money($input)'))
                                        ->suffix('تومان')
                                        ->stripCharacters('.')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            if ($price = str_replace(',', '', $state)) {
                                                $set('sub_total', number_format((int) $get('quantity') * (int) $price));
                                                $set('unit_price', number_format($price));
                                            }
                                        })
                                        ->mutateStateForValidationUsing(fn ($state) => str_replace(',', '', $state))
                                        ->mutateDehydratedStateUsing(fn ($state) => str_replace(',', '', $state)),
                                    TextInput::make('sub_total')
                                        ->label(__('Sub Total Price'))
                                        ->readOnly()
                                        ->dehydrated()
                                        ->translateLabel()
                                        ->integer()
                                        ->required()
                                        ->columnSpan(3)
                                        ->mask(RawJs::make('$money($input)'))
                                        ->suffix('تومان')
                                        ->stripCharacters('.')
                                        ->mutateStateForValidationUsing(fn ($state) => str_replace(',', '', $state))
                                        ->mutateDehydratedStateUsing(fn ($state) => str_replace(',', '', $state)),

                                ])
                                ->columnSpanFull(),
                        ]),
                    Wizard\Step::make('Special services')
                        ->icon('heroicon-o-sparkles')
                        ->columns()
                        ->translateLabel()
                        ->description(__('Application of ancillary services'))
                        ->schema([
                            Select::make('options')
                                ->hiddenLabel()
                                ->multiple()
                                ->options(Option::pluck('name', 'id'))
                                ->default(Option::where('is_default', true)->pluck('id')->toArray())
                                ->native()
                                ->required(),
                        ]),
                ])
                    ->submitAction(new HtmlString(Blade::render(
                        <<<'BLADE'
    <x-srj-button :label="__('Submit Order')" icon="rocket-launch" type="submit" class="bg-gradient-fuchsia"/>
BLADE
                    ))),
            ])
            ->statePath('data');
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

    private function getFinalAddress(Get $get): string
    {
        $address = $this->order->address->getFullAddress();

        if ($get('edit_address')) {
            $address = $this->getAddressFromState($get);
        }

        return $address;
    }

    private function getAddressFromState(Get $get): string
    {
        $fullAddress = [
            $get('state'),
            $get('city'),
            $get('address'),
            $get('no') ? 'پلاک '.$get('no') : null,
            $get('floor') ? 'طبقه '.$get('floor') : null,
            $get('unit') ? 'واحد '.$get('unit') : null,
        ];

        return implode(' - ', array_filter($fullAddress));
    }

    #[Layout('driver.layouts.app')]
    public function render()
    {
        return view('livewire.driver.order.edit-order');
    }

    public function save()
    {
        $this->order->update($this->updateDataBeforeSaving());
        session()->flash('message', 'Order updated successfully!');

        return redirect()->route('driver.orders');
    }

    private function updateDataBeforeSaving()
    {
        $data = $this->form->getState();
        unset($data['current_location'], $data['edit_address']);
        return $data;
    }
}
