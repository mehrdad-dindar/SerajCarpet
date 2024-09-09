<?php

namespace App\Livewire\Driver\Order;

use App\Livewire\Driver\Order\Steps\AddItemsStepComponent;
use App\Livewire\Driver\Order\Steps\ConfirmStepComponent;
use App\Livewire\Driver\Order\Steps\CustomerInfoStepComponent;
use App\Livewire\Driver\Order\Steps\SelectCustomerStepComponent;
use App\Models\Customer;
use App\Support\CreateOrderWizardState;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Spatie\LivewireWizard\Components\WizardComponent;

#[Title('ثبت سفارش')]
#[Layout('driver.layouts.app')]
class CreateWizard extends WizardComponent
{
    public Customer $customer;
    public function mount(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function initialState(): array
    {
        return [
            'customer-info' => [
                'customer' => $this->customer,
            ],
            'confirm-order' => [
                'customer' => $this->customer,
            ]
        ];
    }
    public function steps(): array
    {
        return [
            CustomerInfoStepComponent::class,
            AddItemsStepComponent::class,
            ConfirmStepComponent::class,
        ];
    }

    public function stateClass(): string
    {
        return CreateOrderWizardState::class;
    }
}
