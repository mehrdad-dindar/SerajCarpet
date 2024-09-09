<?php

namespace App\Livewire\Driver\Order\Steps;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\LivewireWizard\Components\StepComponent;

#[Layout("driver.layouts.app")]
class CustomerInfoStepComponent extends StepComponent
{
    public $customer;
    public $icon = "home";

    public function render()
    {
        return view('livewire.driver.order.steps.customer-info-step-component');
    }

    public function submit()
    {
        $this->nextStep();
    }
    public function stepInfo(): array
    {
        return [
            'label' => __("Customer"),
            'icon' => 'user',
        ];
    }

    public function hideMiddleDigits(string $phoneNumber): string
    {
        $length = strlen($phoneNumber);

        if ($length < 10) {
            return $phoneNumber;
        }
        return substr_replace($phoneNumber, '****', $length - 6, 4);
    }
}
