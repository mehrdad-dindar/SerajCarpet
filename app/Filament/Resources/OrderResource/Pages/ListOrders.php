<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
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
            'reserved' => Tab::make('رزرو شده')->query(fn($query) => $query->where('status', OrderStatus::RESERVED)),
            'in_waiting_list' => Tab::make('در لیست جمعی قرار دارد')->query(fn($query) => $query->where('status', OrderStatus::IN_WAITING_LIST)),
            'carpets_received' => Tab::make('فرش ها تحویل گرفته شده')->query(fn($query) => $query->where('status', OrderStatus::CARPETS_RECEIVED)),
            'pre_wash_repair_service' => Tab::make('خدمات ترمیم پیش از شستشو دارد')->query(fn($query) => $query->where('status', OrderStatus::PRE_WASH_REPAIR_SERVICE)),
            'sent_to_factory_for_washing' => Tab::make('جهت شستشو به کارخانه ارسال گردیده')->query(fn($query) => $query->where('status', OrderStatus::SENT_TO_FACTORY_FOR_WASHING)),
            'post_wash_repair_service' => Tab::make('خدمات ترمیم پس از شستشو دارد')->query(fn($query) => $query->where('status', OrderStatus::POST_WASH_REPAIR_SERVICE)),
            'ready_for_delivery_to_customer' => Tab::make('اماده تحویل به مشتری')->query(fn($query) => $query->where('status', OrderStatus::READY_FOR_DELIVERY_TO_CUSTOMER)),
            'delivered_and_paid' => Tab::make('تحویل و تسویه شده')->query(fn($query) => $query->where('status', OrderStatus::DELIVERED_AND_PAID)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
