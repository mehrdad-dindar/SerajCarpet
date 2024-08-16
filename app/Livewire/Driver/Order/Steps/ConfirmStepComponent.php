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
    protected $totalPrice;

    public function confirm()
    {
        $customer_id = $this->state()->customer();
        $orderItems = $this->state()->orderItems();

        // here you should store the amount and address somehow

        $this->redirect(route('order-confirmed'));
    }

    public function mount()
    {
        $this->customer = Customer::find((int)$this->state()->customer());
        $this->orderItems = $this->state()->orderItems();
        $this->totalPrice = $this->calculateTotal();
    }

    public function render()
    {
        $orderItems = $this->getOrderItems();
        return view('livewire.driver.order.steps.confirm-step-component', [
            'customer' => $this->customer,
            'orderItems' => $orderItems,
            'details' => $this->getDetails(),
            'totalPrice' => $this->totalPrice,
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

    private function getDetails()
    {
        $details = [];
        foreach ($this->orderItems as $item) {
            if ($item['property_id']) {
                $details[$item['property_id']] = [
                    'quantity' => $item['count'],
                    'dimensions' => $item['dimensions']
                ];
            }
        }
        return $details;
    }

    private function calculateTotal()
    {
        $detail = $this->getDetails();
        $total = 0;
        foreach ($this->getOrderItems() as $item) {
            $dimensions = 1;
            if (isset($detail[$item->id]['dimensions'])) {
                $dimensions = (int)$detail[$item->id]['dimensions'] ?? 1;
            }
            $quantity = (int)$detail[$item->id]['quantity'] ?? 0;
            $unitPrice = (int)$item['price'];
            $total += $dimensions * $quantity * $unitPrice;
        }
        if ($total) {
            return $total;
        } else {
            return 1;
        }
    }
}
