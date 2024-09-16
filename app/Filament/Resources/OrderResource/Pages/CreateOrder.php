<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\SmsPattern;
use App\Filament\Resources\OrderResource;
use App\Jobs\SendSmsJob;
use App\Models\Order;
use App\Traits\Sms;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Hashids\Hashids;

class CreateOrder extends CreateRecord
{
    use Sms;
    protected static string $resource = OrderResource::class;

    protected function afterCreate()
    {
        $order = $this->record;
        $custumer = $order->customer;
        try {
            $hashids = new Hashids('', 6);
            $hashedID = $hashids->encode($custumer->id);
            SendSmsJob::dispatch($custumer->phone, SmsPattern::SET_LOCATION, array($custumer->name,$hashedID))
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
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
