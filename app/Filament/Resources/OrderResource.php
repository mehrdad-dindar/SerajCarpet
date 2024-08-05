<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressRelationManager;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Property;
use Dotswan\MapPicker\Fields\Map;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'سفارش ها';
    protected static ?string $pluralModelLabel = "سفارش ها";
    protected static ?string $modelLabel = 'سفارش';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Name')
                    ->searchable()
                    ->translateLabel()
                    ->url(function (Model $record): string {
                        return route('filament.admin.resources.customers.edit', $record->customer_id);
                    })
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->translateLabel()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => OrderStatus::from($state)->getColor())
                    ->toggleable()
                    ->formatStateUsing(fn(string $state): string => OrderStatus::from($state)->getLabel()),
                Tables\Columns\TextColumn::make('items_count')
                    ->sortable()
                    ->translateLabel()
                    ->label('Order Item Count')
                    ->toggleable()
                    ->alignCenter()
                    ->counts('items'),
                Tables\Columns\TextColumn::make('total')
                    ->formatStateUsing(function ($state) {
                        return number_format($state, 0) . ' تومان';
                    })
                    ->badge()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('reserved_for')
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('driver.name')
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(OrderStatus::class)
                    ->label(__('Status')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('assignDriver')
                        ->label('Assign Driver')
                        ->icon("heroicon-o-truck")
                        ->translateLabel()
                        ->action(function (Order $record, array $data): void {
                            $record->update([
                                'driver_id' => $data['driver_id'],
                            ]);
                        })
                        ->form([
                            Forms\Components\Select::make('driver_id')
                                ->label('Driver')
                                ->relationship('driver', 'name') // رابطه صحیح باید استفاده شود
                                ->required(),
                        ]),
//                    Action::make('set_driver')
//                        ->label(__('Set Driver'))
//                        ->icon('heroicon-o-arrows-right-left')
//                        ->form([
//                            Forms\Components\Select::make('status')
//                                ->label(__('Order Status'))
//                                ->hiddenLabel()
//                                ->options(OrderStatus::class)
//                                ->default(OrderStatus::RESERVED)
//                                ->live()
//                                ->required(),
//                        ])
//                        ->action(function (Order $record, array $data) {
//                            $record->driver_id = $data['driver_id'];
//                            $record->save();
//                            Notification::make()
//                                ->title($record->driver_id ? __('Driver Assigned') : __('Driver Unassigned'))
//                                ->body('yesss')
//                                ->success();
//                        }),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('assignDriver')
                        ->label('Assign Driver')
                        ->icon("heroicon-o-truck")
                        ->translateLabel()
                        ->action(function (Collection $records, array $data): void {
                            $ids = $records->pluck('id');
                            Order::whereIn('id', $ids)->update(['driver_id' => $data['driver_id']]);
                        })
                        ->form([
                            Forms\Components\Select::make('driver_id')
                                ->label("Driver")
                                ->translateLabel()
                                ->relationship('driver', 'name') // رابطه صحیح باید استفاده شود
                                ->required(),
                        ]),
                    Tables\Actions\DeleteBulkAction::make(),
//                    Action::make('set_driver')
//                        ->label(__('Set Driver'))
//                        ->icon('heroicon-o-arrows-right-left')
//                        ->form([
//                            Forms\Components\Select::make('status')
//                                ->label(__('Order Status'))
//                                ->hiddenLabel()
//                                ->options(OrderStatus::class)
//                                ->default(OrderStatus::RESERVED)
//                                ->live()
//                                ->required(),
//                        ])
//                        ->action(function ($record, array $data) {
//                            $record->driver_id = $data['driver_id'];
//                            $record->save();
//                            Notification::make()
//                                ->title($record->driver_id ? __('Driver Assigned') : __('Driver Unassigned'))
//                                ->body('yesss')
//                                ->success();
//                        })
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make('Order')
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\Section::make('اطلاعات مشتری')
                            ->schema([
                                Forms\Components\Select::make('customer_id')
                                    ->translateLabel()
                                    ->label('customer')
                                    ->prefixIcon('heroicon-o-user')
                                    ->relationship('customer', 'id_name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set) => $set('address_id', null))
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
                                Forms\Components\Select::make('address_id')
                                    ->prefixIcon('heroicon-o-map-pin')
                                    ->label(__("Customer's Address"))
                                    ->translateLabel()
                                    ->options(fn(Get $get): Collection => Address::query()
                                        ->where('customer_id', $get('customer_id'))
                                        ->pluck('address', 'id'))
                                    ->createOptionForm([
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
                                                ->columnSpan(6)
                                                ->helperText(fn(Get $get) => AddressResource::getHint('address', $get))
                                                ->label(__('Full Address')),
                                            Forms\Components\Toggle::make('is_suggested')
                                                ->onIcon('heroicon-s-sparkles')
                                                ->offIcon('heroicon-o-star')
                                                ->inline(false)
                                                ->reactive()
                                                ->default(false)
                                                ->translateLabel()
                                                ->columnSpan(2)
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
                                    ])
                                    ->createOptionUsing(function (array $data, Get $get): int {
                                        $customer = Customer::findOrFail($get('customer_id'));
                                        return $customer->addresses()->create($data)->getKey();
                                    })
                                    ->searchable()
                                    ->preload(),
                            ])->columns()->icon('heroicon-o-user'),
                        Forms\Components\Section::make('موارد سفارش')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->label(__('Items'))
                                    ->translateLabel()
                                    ->relationship()
                                    ->reorderable()
                                    ->defaultItems(1)
                                    ->hiddenLabel()
                                    ->columns(12)
                                    ->schema([
                                        Forms\Components\Select::make('property_id')
                                            ->label(__('Select Service'))
                                            ->translateLabel()
                                            ->required()
                                            ->reactive()
                                            ->helperText(fn(Get $get) => Property::find($get('property_id'))->helperText ?? '')
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $set('sub_total', ((int)$get('dimensions') ?? 1) * $get('quantity') * Property::find($state)->price);
                                                $set('unit_price', Property::find($state)->price);
                                            })
                                            ->relationship('property', 'fullTitle')
                                            ->getOptionLabelFromRecordUsing(function (Property $property) {
                                                return $property->fullTitle;
                                            })
                                            ->columnSpan(3),
                                        Forms\Components\Select::make('dimensions')
                                            ->options(function ($state, Get $get) {
                                                if ($propertyId = $get('property_id')) {
                                                    $dimensions = Property::find($propertyId)->dimensions ?? [];
                                                    return array_combine($dimensions, $dimensions);
                                                }
                                                return [];
                                            })
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $set('sub_total', ((int)$get('dimensions') ?? 1) * $get('quantity') * Property::find($get('property_id'))->price);
                                                $set('unit_price', Property::find($get('property_id'))->price);
                                            })
                                            ->translateLabel()
                                            ->live()
                                            ->hidden(function ($get, $set) {
                                                $propertyId = $get('property_id');
                                                $property = $propertyId ? Property::find($propertyId) : null;

                                                if (!$propertyId || !$property || !$property->dimensions) {
                                                    $set('dimensions', 1);
                                                    return true;
                                                }
                                                return false;
                                            })
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('quantity')
                                            ->label(__("Quantity"))
                                            ->translateLabel()
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $price = Property::find($get("property_id"))->price;
                                                $set('sub_total', ((int)$get('dimensions') ?? 1) * $state * $price);
                                                $set('unit_price', $price);
                                            })
                                            ->columnSpan(2)
                                            ->required(),
                                        Forms\Components\Hidden::make('unit_price'),
                                        Forms\Components\TextInput::make('sub_total')
                                            ->label(__("Sub Total Price"))
                                            ->readOnly()
                                            ->dehydrated()
                                            ->translateLabel()
                                            ->integer()
                                            ->required()
                                            ->columnSpan(4)
                                            ->mask(RawJs::make("\$money(\$input)"))
                                            ->suffix('تومان')
                                            ->stripCharacters('.')
                                            ->mutateStateForValidationUsing(fn($state) => str_replace(',', '', $state))
                                            ->mutateDehydratedStateUsing(fn($state) => str_replace(',', '', $state)),
                                    ])
                                    ->columnSpanFull(),
                                Forms\Components\Repeater::make('other_items')
                                    ->label(__('Other Items'))
                                    ->translateLabel()
                                    ->relationship()
                                    ->defaultItems(0)
                                    ->hiddenLabel()
                                    ->columns(12)
                                    ->schema([
                                        Forms\Components\Hidden::make('is_custom')->default(1),
                                        Forms\Components\TextInput::make('title')
                                            ->columnSpan(4),
                                        Forms\Components\TextInput::make('quantity')
                                            ->label(__("Quantity"))
                                            ->translateLabel()
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                if ($price = str_replace(',', '', $get('unit_price'))) {
                                                    $set('sub_total', (int)$state * (int)$price);
                                                }
                                            })
                                            ->columnSpan(2)
                                            ->required(),
                                        Forms\Components\TextInput::make('unit_price')
                                            ->label(__("Unit Price"))
                                            ->columnSpan(3)
                                            ->mask(RawJs::make("\$money(\$input)"))
                                            ->suffix('تومان')
                                            ->stripCharacters('.')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                if ($price = str_replace(',', '', $state)) {
                                                    $set('sub_total', number_format((int)$get('quantity') * (int)$price));
                                                    $set('unit_price', number_format($price));
                                                }
                                            })
                                            ->mutateStateForValidationUsing(fn($state) => str_replace(',', '', $state))
                                            ->mutateDehydratedStateUsing(fn($state) => str_replace(',', '', $state)),
                                        Forms\Components\TextInput::make('sub_total')
                                            ->label(__("Sub Total Price"))
                                            ->readOnly()
                                            ->dehydrated()
                                            ->translateLabel()
                                            ->integer()
                                            ->required()
                                            ->columnSpan(3)
                                            ->mask(RawJs::make("\$money(\$input)"))
                                            ->suffix('تومان')
                                            ->stripCharacters('.')
                                            ->mutateStateForValidationUsing(fn($state) => str_replace(',', '', $state))
                                            ->mutateDehydratedStateUsing(fn($state) => str_replace(',', '', $state)),

                                    ])
                                    ->columnSpanFull(),
                            ])->icon('heroicon-o-list-bullet')
                    ]),
                Forms\Components\Grid::make('Order')
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Section::make('سایر خدمات')
                            ->schema([
                                Forms\Components\Select::make('options')
//                                    ->label(__('Order Options'))
                                    ->hiddenLabel()
                                    ->multiple()
                                    ->options([
                                        "1" => "آبشور",
                                        "2" => "اعلاء‌شوئی",
                                        "3" => "براق‌شویی",
                                        "4" => "رنگ‌برداری",
                                        "5" => "رفوگری",
                                        "6" => "پرداخت",
                                        "7" => "کاور",
                                    ])
                                    ->default(['1', '2', '7'])
                                    ->native()
                                    ->required(),
                            ]),
                        Forms\Components\Section::make('وضعیت سفارش')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label(__('Order Status'))
                                    ->hiddenLabel()
                                    ->options(OrderStatus::class)
                                    ->default(OrderStatus::RESERVED)
                                    ->live()
//                                    ->before(fn($record, $get) => dd($record))
                                    ->required(),
                                Forms\Components\Fieldset::make('تنظیم تاریخ رزرو')
//                                    ->hidden(fn(Get $get): bool => $get('status') != OrderStatus::RESERVED)
                                    ->visible(
                                        fn(Get $get): bool => (is_object($get('status')) ? $get('status')->value : $get('status')) == OrderStatus::RESERVED->value
                                    )
                                    ->schema([
                                        Forms\Components\DateTimePicker::make('reserved_for')
                                            ->prefixIcon('heroicon-o-calendar-days')
                                            ->label('Reservation Time')
                                            ->translateLabel()
                                            ->displayFormat('H:i Y-m-d')
                                            ->seconds(false)
                                            ->firstDayOfWeek(4)
                                            ->default(null)
                                            ->columnSpanFull()
                                            ->jalali(),
                                    ]),

                            ]),
                        Forms\Components\Section::make('قیمت کل')
                            ->schema([
                                Forms\Components\Hidden::make('total')
                                    ->mutateDehydratedStateUsing(fn(Get $get) => self::calculateTotal($get)),
                                Forms\Components\Placeholder::make('order_total')
                                    ->label(__('Order Total'))
                                    ->reactive()
                                    ->content(fn(Get $get): ?string => number_format(self::calculateTotal($get), 0) . ' تومان')
                            ])
                    ])
            ])->columns(3);
    }

    private static function calculateTotal($data): int
    {
        $items = array_merge($data('items'), $data('other_items'));
        $total = 0;
        foreach ($items as $item) {
            $dimensions = 1;
            if (isset($item['dimensions'])) {
                $dimensions = (int)$item['dimensions'] ?? 1;
            }
            $quantity = (int)$item['quantity'] ?? 0;
            $unitPrice = (int)str_replace(',', '', $item['unit_price']);
            $total += $dimensions * $quantity * $unitPrice;
        }
        if ($total) {
            return $total;
        } else {
            return 1;
        }
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
