<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    public function getTabs(): array
    {
        return [
            null => Tab::make(__('All')),
            'paid' => Tab::make()
                ->label(__('invoice.status.paid'))
                ->query(fn ($query) => $query->where('status', 'paid')),
            'pending' => Tab::make()
                ->label(__('invoice.status.pending'))
                ->query(fn ($query) => $query->where('status', 'pending')),
            'canceled' => Tab::make()
                ->label(__('invoice.status.canceled'))
                ->query(fn ($query) => $query->where('status', 'canceled')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
