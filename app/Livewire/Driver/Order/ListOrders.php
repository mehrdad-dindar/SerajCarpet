<?php

namespace App\Livewire\Driver\Order;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Traits\Neshan;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ListOrders extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use Neshan;
    public $orders;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::whereIn('id', $this->orders)
//                ->whereHas(
//                    'status',
//                    fn ($q) => $q->where('name', OrderStatus::RESERVED)
//
//                )
                ->orderByRaw('FIELD(id, ' . implode(',', $this->orders) . ')'))
            ->columns([
                TextColumn::make('id')
                    ->prefix('#')
                    ->searchable()
                    ->label(__('Order Id')),
                TextColumn::make('customer.name')
                    ->searchable()
                    ->label(__('Customer Name')),
                TextColumn::make('status')
                    ->label('Status')
                    ->translateLabel()
                    ->sortable()
                    ->badge()
                    ->color(fn (OrderStatus $state): string => $state->color)
                    ->toggleable()
                    ->formatStateUsing(fn (OrderStatus $state): string => $state->label),
                TextColumn::make('items_count')
                    ->translateLabel()
                    ->label('Order Item Count')
                    ->toggleable()
                    ->alignCenter()
                    ->suffix(function (Order $order) {
                        $uniqueItems = $order->items
                            ->pluck('property.serviceItem.name')
                            ->unique()
                            ->join(' - ');

                        return $uniqueItems ? ' مورد '.$uniqueItems : '';
                    })
                    ->counts('items'),
                TextColumn::make('area')
                    ->label(__("Customer's Address"))
                    ->badge()
                    ->color(fn ($state, $record): string => $record->address ? 'info' : 'danger')
                    ->getStateUsing(function ($record) {
                        $area = 'فاقد آدرس';
                        if ($record->address) {
                            $area = 'منطقه '.$record->address->municipality_zone;
                            $area .= ' - محله '.$record->address->neighbourhood;
                        }

                        return $area;
                    })
                    ->description(fn (Order $record): string => $record->address->getFullAddress(), position: 'above')
                    ->wrap()
                    ->toggleable(),
                TextColumn::make('Comment')
                    ->getStateUsing(function (Order $order) {
                        $comment = $order->address->customerComments()->orderBy('created_at', 'desc')->first();
                        if ($comment) {
                            return $comment->body;
                        }
                        return null;
                    })
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make('Edit')
                        ->label(__('Edit Order'))
                        ->url(fn (Order $record) => route('driver.orders.edit', $record)),
                    Action::make('change_status')
                        ->translateLabel()
                        ->icon('heroicon-o-tag')
                        ->color('danger')
                        ->action(function (Order $record, array $data): void {
                            $record->update([
                                'status_id' => $data['status_id'],
                            ]);
                        })
                        ->form([
                            Select::make('status_id')
                                ->options(OrderStatus::all()->pluck('label', 'id'))
                        ]),
                    Action::make('Call')
                        ->icon('heroicon-o-phone-arrow-up-right')
                        ->translateLabel()
                        ->url(function (Order $order): string {
                            return sprintf(
                                'tel:+98%d',
                                $order->customer->phone
                            );
                        })
                        ->color('success'),
                    Action::make('Directions')
                        ->icon('heroicon-o-map-pin')
                        ->disabled(fn (Order $order): bool => is_null($order->address->latitude))
                        ->translateLabel()
                        ->color('info')
                        ->url(function (Order $order): string {
                            return sprintf(
                                'https://nshn.ir/?lat=%s&lng=%s',
                                $order->address->latitude,
                                $order->address->longitude
                            );
                        })
                        ->openUrlInNewTab(),
                ])
                    ->button()
                    ->label('Actions')
                    ->translateLabel(),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.driver.order.list-orders');
    }
}
