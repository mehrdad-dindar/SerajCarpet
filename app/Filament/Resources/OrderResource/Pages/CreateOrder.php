<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\SmsPattern;
use App\Filament\Resources\OrderResource;
use App\Jobs\SendSmsJob;
use App\Models\Order;
use App\Traits\Sms;
use Filament\Resources\Pages\CreateRecord;
use Hashids\Hashids;

class CreateOrder extends CreateRecord
{
    use Sms;

    protected static string $resource = OrderResource::class;

    /**
     * After creating the order, send an SMS to the customer
     */
    protected function afterCreate()
    {
        $this->sendConfirmationSms($this->record);
    }

    /**
     * Send confirmation SMS with a hashed customer ID.
     *
     * @param Order $order
     */
    private function sendConfirmationSms(Order $order): void
    {
        $customer = $order->customer;

        if ($customer) {
            try {
                $hashedID = $customer->getHasheId();
                SendSmsJob::dispatch($customer->phone, SmsPattern::SET_LOCATION, [$customer->name, $hashedID]);
            } catch (\Exception $e) {
                info($e->getMessage()); // Log the error if SMS fails
            }
        }
    }

    /**
     * Get the redirect URL after the order creation.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Mutate the form data before creating an order.
     *
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Combine date and time into a single Carbon instance
        if (isset($data['reservation_date'], $data['reservation_time'])) {
            $data['time_apply_status'] = \Carbon\Carbon::parse("{$data['reservation_date']} {$data['reservation_time']}");
        }

        // Convert options to integers
        if (isset($data['options'])) {
            $data['options'] = array_map('intval', $data['options']);
        }

        // Remove unnecessary fields from data
        unset($data['reservation_date'], $data['reservation_time']);

        return $data;
    }
}
