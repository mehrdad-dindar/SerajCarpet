<?php

namespace App\Livewire\Driver\Order\Steps;

use App\Models\Customer;
use App\Models\Property;
use Livewire\Component;
use Spatie\LivewireWizard\Components\StepComponent;

class ConfirmStepComponent extends StepComponent
{
    protected Customer $customer;
    protected $orderItems = [];

    public function confirm()
    {
        $customer_id = $this->state()->customer();
        $address = $this->state()->orderItems();

        // here you should store the amount and address somehow

        $this->redirect(route('order-confirmed'));
    }

    public function mount()
    {
        $this->customer = Customer::find((int)$this->state()->customer());
        $this->orderItems = $this->state()->orderItems();
    }

    public function render()
    {
        $orderItems = $this->getOrderItems();
        return view('livewire.driver.order.steps.confirm-step-component', [
            'customer' => $this->customer,
            'orderItems' => $orderItems,
        ]);
    }

    public function stepInfo(): array
    {
        return [
            'label' => __("Confirm Order"),
            'icon' => 'check-badge',
        ];
    }

    private function getOrderItems()
    {
        $items = [];

        foreach ($this->orderItems as $item) {
            if ($item['property_id']) {
                $items[] = Property::find($item['property_id']);
            }
        }

        return $items;
    }
}
