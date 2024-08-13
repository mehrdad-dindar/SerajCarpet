<?php

namespace App\Livewire\Driver\Order\Steps;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\LivewireWizard\Components\StepComponent;

#[Layout("driver.layouts.app")]
class SelectCustomerStepComponent extends StepComponent
{
    public $customer_id;
    public $customers = [
        1 => "Mehrdad",
        2 => "Hadi"
    ];
    public function render()
    {
        return view('livewire.driver.order.steps.select-customer-step-component');
    }

    public array $rules = [
        'customer_id' => ['required','exists:customers,id']
    ];

    public function submit()
    {
        dd($this->customer_id);
        $this->validate();

        $this->nextStep();
    }

    public function stepInfo(): array
    {
        return [
            'label' => __("Customer"),
        ];
    }
}
