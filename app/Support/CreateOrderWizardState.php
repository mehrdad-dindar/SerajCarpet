<?php

namespace App\Support;

use Spatie\LivewireWizard\Support\State;

class CreateOrderWizardState extends State
{
    public function orderItems(): array
    {
        return $this->forStep('select-items')['order_items'];
    }

    public function customer(): int
    {
        return $this->forStep('select-customer')['customer_id'];
    }
}
