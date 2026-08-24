<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTabs(): array
    {
        $tabs = [
            null => Tab::make('همه سفارش‌ها')
                ->icon('heroicon-m-list-bullet'),
        ];

        foreach (OrderStatusEnum::cases() as $statusEnum) {
            $tabs[$statusEnum->value] = Tab::make($statusEnum->getLabel())
                ->icon($statusEnum->getIcon())
                ->badge(fn () => \App\Models\Order::whereHas('status', fn ($q) => $q->where('name', $statusEnum->value))->count())
                ->badgeColor($statusEnum->getColor())
                ->query(fn ($query) => $query->whereHas('status', fn ($q) => $q->where('name', $statusEnum->value)));
        }

        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('ثبت سفارش جدید')->icon('heroicon-m-plus'),
        ];
    }
}
