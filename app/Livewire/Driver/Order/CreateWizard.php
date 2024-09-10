<?php

namespace App\Livewire\Driver\Order;

use App\Livewire\Driver\Order\Steps\AddItemsStepComponent;
use App\Livewire\Driver\Order\Steps\ConfirmStepComponent;
use App\Livewire\Driver\Order\Steps\CustomerInfoStepComponent;
use App\Livewire\Driver\Order\Steps\SelectCustomerStepComponent;
use App\Models\Customer;
use App\Models\Order;
use App\Support\CreateOrderWizardState;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Spatie\LivewireWizard\Components\WizardComponent;

#[Title('ثبت سفارش')]
#[Layout('driver.layouts.app')]
class CreateWizard extends WizardComponent
{
    public Customer $customer;
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order;
        $this->customer = $this->getCustomer();
    }

    public function initialState(): array
    {
        return [
            'customer-info' => [
                'customer' => $this->customer,
                'order' => $this->order,
            ],
            'confirm-order' => [
                'customer' => $this->customer,
                'order' => $this->order,
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

    private function getCustomer()
    {
        return $this->order->customer;
    }
    public function finish()
    {
        session()->flash('message', 'جزئیات سفارش با موفقیت بروز شد.');
        $this->dispatch('closeModal');
    }
}
