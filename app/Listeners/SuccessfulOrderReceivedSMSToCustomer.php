<?php

namespace App\Listeners;

use App\Enums\SmsPattern;
use App\Events\OrderReceivedByDriver;
use App\Jobs\SendSmsJob;
use App\Traits\Sms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SuccessfulOrderReceivedSMSToCustomer
{
    use Sms;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderReceivedByDriver $event): void
    {
        $customer = $event->order->customer;
        SendSmsJob::dispatch($customer->phone, SmsPattern::ORDER_RECEIVED, array(
            $customer->name,
            $event->order->id,
            verta()->format("Y/m/d H:i"),
            number_format($event->order->total)
        ));
    }
}
