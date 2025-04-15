<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);
        parse_str(parse_url(url()->previous(), PHP_URL_QUERY) ?? '', $filters);
        session()->put('orders_filters', $filters);
    }
    protected function getFooterWidgets(): array
    {
        $order = $this->record;
        return [
            OrderResource\Widgets\OrderStatusHistoryWidget::make(['order' => $order]),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index', session()->get('orders_filters', []));
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['time_apply_status'])) {
            [$data['reservation_date'],$data['reservation_time']] = explode(' ', $data['time_apply_status']);
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['reservation_date'], $data['reservation_time'])) {
            $data['time_apply_status'] = Carbon::parse($data['reservation_date'] . ' ' . $data['reservation_time']);
        }
        if (isset($data['options'])) {
            $data['options'] = array_map('intval', $data['options']);
        }

        unset($data['reservation_date'], $data['reservation_time']);

        return $data;
    }
}
