<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['time_apply_status'])) {
            [$data['reservation_date'],$data['reservation_time']] = explode(' ',$data['time_apply_status']);
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['reservation_date'], $data['reservation_time'])) {
            $data['time_apply_status'] = \Carbon\Carbon::parse($data['reservation_date'] . ' ' . $data['reservation_time']);
        }
        if (isset($data['options'])) {
            $data['options'] = array_map('intval', $data['options']);
        }

        unset($data['reservation_date'], $data['reservation_time']);

        return $data;
    }
}
