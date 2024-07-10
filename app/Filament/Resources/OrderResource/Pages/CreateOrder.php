<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\SmsPattern;
use App\Filament\Resources\OrderResource;
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
            $hashids = new Hashids('',6);
            $hashedID = $hashids->encode($custumer->id);
            $this->sendPattern($custumer->phone,SmsPattern::SET_LOCATION,array($custumer->name,$hashedID));
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
