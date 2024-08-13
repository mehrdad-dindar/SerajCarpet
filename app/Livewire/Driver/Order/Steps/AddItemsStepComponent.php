<?php

namespace App\Livewire\Driver\Order\Steps;

use Livewire\Attributes\Layout;
use Spatie\LivewireWizard\Components\StepComponent;

#[Layout("driver.layouts.app")]
class AddItemsStepComponent extends StepComponent
{
    public function render()
    {
        return view('livewire.driver.order.steps.add-items-step-component');
    }

    public function submit()
    {
        $this->validate();

        $this->nextStep();
    }

    public function stepInfo(): array
    {
        return [
            'label' => __("Order Items"),
        ];
    }
}
