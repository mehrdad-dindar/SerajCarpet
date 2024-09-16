<?php

namespace App\Support;

use Spatie\LivewireWizard\Support\State;

class CreateOrderWizardState extends State
{
    public function orderItems(): array
    {
        return $this->forStep('select-items')['order_tmp_items'];
    }
    public function washingType(): array
    {
        return $this->forStep('select-items')['washing_type'];
    }
}
