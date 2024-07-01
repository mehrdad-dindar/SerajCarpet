<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressRelationManager;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Property;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Resources\Resource;
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
                                                ->label(__('State')),
                                            Forms\Components\TextInput::make('city')
                                                ->required()
                                                ->columnSpan(2)
                                                ->label(__('City')),
                                            Forms\Components\TextInput::make('address')
                                                ->required()
                                                ->columnSpan(8)
                                                ->label(__('Full Address')),
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
                                    ])
                                    ->createOptionUsing(function (array $data, Get $get): int {
                                        $customer = Customer::findOrFail($get('customer_id'));
                                        return $customer->addresses()->create($data)->getKey();
                                    })
                                    ->searchable()
                                    ->preload()
//                                    ->requiredWithout(['address.state', 'address.city', 'address.address', 'address.lat', 'address.lng', 'address.is_active']),
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
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $set('sub_total', $get('quantity') * Property::find($state)->price);
                                                $set('unit_price', Property::find($state)->price);
                                            })
                                            ->relationship('property', 'fullTitle')
                                            ->getOptionLabelFromRecordUsing(function (Property $property) {
                                                return $property->fullTitle;
                                            })
                                            ->columnSpan(5),
                                        Forms\Components\TextInput::make('quantity')
                                            ->label(__("Quantity"))
                                            ->translateLabel()
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $set('sub_total', $state * Property::find($get("property_id"))->price);
                                                $set('unit_price', Property::find($get("property_id"))->price);
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
                                            ->columnSpan(5)
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
                                    ->default(['1', '7'])
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
                                    ->native()
                                    ->live()
                                    ->afterStateUpdated(
                                        fn ($state, callable $set) => $state == OrderStatus::RESERVED ? $set('created_at', null) : $set('created_at', 'hidden')
                                    )
                                    ->required(),
                                Forms\Components\DateTimePicker::make('created_at')
                                    ->label('Reservation Time')
                                    ->translateLabel()
                                    ->displayFormat('H:i Y-m-d')
                                    ->seconds(false)
                                    ->firstDayOfWeek(4)
                                    ->default(now())
                                    ->live()
                                    ->hidden(
                                        fn (Get $get): bool => $get('status') == "reserved"
                                    )
                                    ->jalali(),
                            ]),
                        Forms\Components\Section::make('قیمت کل')
                            ->schema([
                                Forms\Components\Hidden::make('total')
                                    ->mutateDehydratedStateUsing(fn(Get $get) => self::calculateTotal($get('items'))),
                                Forms\Components\Placeholder::make('order_total')
                                    ->label(__('Order Total'))
                                    ->reactive()
                                    ->content(fn(Get $get): ?string => number_format(self::calculateTotal($get('items')), 0) . ' تومان')
                            ])
                    ])
            ])->columns(3);
    }

    private static function calculateTotal($items): int
    {
        $total = 0;
        foreach ($items as $item) {
            $quantity = (int)$item['quantity'] ?? 0;
            $unitPrice = (int)$item['unit_price'] ?? 0;
            $total += $quantity * $unitPrice;
        }
        return $total;
    }

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
                    ->toggleable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->sortable()
                    ->translateLabel()
                    ->label('Item Count')
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
                    ->toggleable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        "pending" => "در انتظار پرداخت",
                        "paid" => "پرداخت شده",
                        "cancel" => "لغو شده",
                        "reject" => "رد شده",
                        "processing" => "در حال انجام",
                        "on_delivery" => "در حال ارسال",
                        "delivered" => "تحویل شده",
                    ])
                    ->label(__('Status')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
