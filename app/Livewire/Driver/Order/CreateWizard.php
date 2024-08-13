<?php

namespace App\Livewire\Driver\Order;

use App\Livewire\Driver\Order\Steps\AddItemsStepComponent;
use App\Livewire\Driver\Order\Steps\SelectCustomerStepComponent;
use App\Support\CreateOrderWizardState;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Spatie\LivewireWizard\Components\StepComponent;
use Spatie\LivewireWizard\Components\WizardComponent;

#[Title("ثبت سفارش")]
#[Layout("driver.layouts.app")]
class CreateWizard extends WizardComponent
{

    /**
     * @return array
     */
    public function steps(): array
    {
        return [
            SelectCustomerStepComponent::class,
            AddItemsStepComponent::class
        ];
    }

    public function stateClass(): string
    {
        return CreateOrderWizardState::class;
    }
}
