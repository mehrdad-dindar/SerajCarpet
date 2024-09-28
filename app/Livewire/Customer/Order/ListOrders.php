<?php

namespace App\Livewire\Customer\Order;

use App\Models\Order;
use App\Models\OrderStatus as OrderStatusModel;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ListOrders extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $orders;

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->query(Order::whereIn('id', $this->orders->pluck('id')))
            ->columns([
                TextColumn::make('id')
                    ->label(__('Order Id'))
                    ->prefix('#'),
                TextColumn::make('items_count')
                    ->translateLabel()
                    ->label('Order Item Count')
                    ->alignCenter()
                    ->suffix(function (Order $order) {
                        $uniqueItems = $order->items
                            ->pluck('property.serviceItem.name')
                            ->unique()
                            ->join(' - ');

                        return $uniqueItems ? ' مورد '.$uniqueItems : '';
                    })
                    ->counts('items'),
                TextColumn::make('total')
                    ->numeric()
                    ->suffix(' تومان')
                    ->label(__('Order Total')),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (OrderStatusModel $state): string => $state->color)
                    ->formatStateUsing(fn (OrderStatusModel $state): string => $state->label),
                TextColumn::make('created_at')
                    ->translateLabel(),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.customer.order.list-orders');
    }
}
