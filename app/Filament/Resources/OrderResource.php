<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Property;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make('Order')
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\Section::make('Customer')
                            ->schema([
                                Forms\Components\Select::make('customer_id')
                                    ->prefixIcon('heroicon-o-user')
                                    ->relationship('customer', 'id_name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(fn(Set $set) => $set('address_id', null))
                                    ->required(),
                                Forms\Components\Select::make('address_id')
                                    ->prefixIcon('heroicon-o-map-pin')
                                    ->label(__("Customer's Address"))
                                    ->options(fn(Get $get): Collection => Address::query()
                                        ->where('customer_id', $get('customer_id'))
                                        ->pluck('address', 'id'))
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('state')
                                            ->required()
                                            ->label(__('State')),
                                        Forms\Components\TextInput::make('city')
                                            ->required()
                                            ->label(__('City')),
                                        Forms\Components\TextInput::make('address')
                                            ->required()
                                            ->label(__('Full Address')),
                                        Forms\Components\TextInput::make('lat')
                                            ->required()
                                            ->label(__('Latitude')),
                                        Forms\Components\TextInput::make('lng')
                                            ->required()
                                            ->label(__('longitude')),
                                        Forms\Components\Checkbox::make('is_active')
                                            ->label(__('Active'))
                                            ->default(true),
                                    ])
                                    ->createOptionUsing(function (array $data, Get $get): int {
                                        $customer = Customer::findOrFail($get('customer_id'));
                                        return $customer->addresses()->create($data)->getKey();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->requiredWithout(['address.state', 'address.city', 'address.address', 'address.lat', 'address.lng', 'address.is_active']),
                            ])->columns()->icon('heroicon-o-user'),
                        Forms\Components\Section::make('Order Items')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->relationship()
                                    ->reorderable()
                                    ->defaultItems(1)
                                    ->hiddenLabel()
                                    ->columns(12)
                                    ->schema([
                                        Forms\Components\Select::make('property_id')
                                            ->label(__('Property'))
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
                        Forms\Components\Section::make('Order Status')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label(__('Order Status'))
                                    ->options([
                                        "pending" => "در انتظار پرداخت",
                                        "paid" => "پرداخت شده",
                                        "cancel" => "لغو شده",
                                        "reject" => "رد شده",
                                        "processing" => "در حال انجام",
                                        "on_delivery" => "در حال ارسال",
                                        "delivered" => "تحویل شده",
                                    ])
                                    ->native()
                                    ->required(),
                            ]),
                        Forms\Components\Section::make('Total Price')
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
                //
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
            //
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
