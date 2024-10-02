<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\OrderStatus;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTabs(): array
    {
        return [
            null => Tab::make(__('All')),
            OrderStatus::RESERVED => Tab::make()
                ->label(__('Reserved'))
                ->query(fn ($query) => $query->whereHas(
                    'status',
                    fn ($q) => $q->where('name', OrderStatus::RESERVED)
                )),
            OrderStatus::IN_COLLECTIVE_LIST => Tab::make()
                ->label(__('In collective list'))
                ->query(fn ($query) => $query->whereHas(
                    'status',
                    fn ($q) => $q->where('name', OrderStatus::IN_COLLECTIVE_LIST)
                )),
            OrderStatus::IN_DISTRIBUTION_LIST => Tab::make()
                ->label(__('In Distribution list'))
                ->query(fn ($query) => $query->whereHas(
                    'status',
                    fn ($q) => $q->where('name', OrderStatus::IN_DISTRIBUTION_LIST)
                )),
            OrderStatus::CARPETS_RECEIVED => Tab::make()
                ->label(__('Carpets received'))
                ->query(fn ($query) => $query->whereHas(
                    'status',
                    fn ($q) => $q->where('name', OrderStatus::CARPETS_RECEIVED)
                )),
            OrderStatus::PRE_WASH_REPAIR_SERVICE => Tab::make()
                ->label(__('Pre-wash repair service'))
                ->query(fn ($query) => $query->whereHas(
                    'status',
                    fn ($q) => $q->where('name', OrderStatus::PRE_WASH_REPAIR_SERVICE)
                )),
            OrderStatus::POST_WASH_REPAIR_SERVICE => Tab::make()
                ->label(__('Post-wash repair service'))
                ->query(fn ($query) => $query->whereHas(
                    'status',
                    fn ($q) => $q->where('name', OrderStatus::POST_WASH_REPAIR_SERVICE)
                )),
            OrderStatus::SENT_TO_FACTORY_FOR_WASHING => Tab::make()
                ->label(__('Sent to factory for washing'))
                ->query(fn ($query) => $query->whereHas(
                    'status',
                    fn ($q) => $q->where('name', OrderStatus::SENT_TO_FACTORY_FOR_WASHING)
                )),
            OrderStatus::READY_FOR_DELIVERY_TO_CUSTOMER => Tab::make()
                ->label(__('Ready for delivery to customer'))
                ->query(fn ($query) => $query->whereHas(
                    'status',
                    fn ($q) => $q->where('name', OrderStatus::READY_FOR_DELIVERY_TO_CUSTOMER)
                )),
            OrderStatus::DELIVERED_AND_PAID => Tab::make()
                ->label(__('Delivered and paid'))
                ->query(fn ($query) => $query->whereHas(
                    'status',
                    fn ($q) => $q->where('name', OrderStatus::DELIVERED_AND_PAID)
                )),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
