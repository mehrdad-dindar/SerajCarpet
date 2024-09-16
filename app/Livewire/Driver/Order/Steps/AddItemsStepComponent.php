<?php

namespace App\Livewire\Driver\Order\Steps;

use App\Models\Customer;
use App\Models\Option;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Spatie\LivewireWizard\Components\StepComponent;

#[Layout("driver.layouts.app")]
class AddItemsStepComponent extends StepComponent
{
    public Customer $customer;

    public Order $order;
    public $order_tmp_items = [];
    public $washing_type = [];
    public $washingOptions = [];

    public function mount()
    {
        $this->initOrderItems();

        $this->washingOptions = Option::pluck('name', 'id')
            ->toArray();

        $this->washing_type = Option::where('is_default', true)
            ->pluck('name')
            ->toArray();
    }
    public function addItem()
    {
        $this->order_tmp_items[] = [
            'property_id' => null,
            'dimensions' => null,
            'count' => 1
        ];
    }

    public function removeItem($index)
    {
        unset($this->order_tmp_items[$index]);

        $this->order_tmp_items = array_values($this->order_tmp_items);
    }

    public function render()
    {
        return view('livewire.driver.order.steps.add-items-step-component');
    }

    public function submit()
    {
        $this->nextStep();
    }

    public function stepInfo(): array
    {
        return [
            'label' => __("Order Items"),
            'icon' => 'list-bullet',
        ];
    }

    private function initOrderItems()
    {
        if ($this->order->items()->count()) {
            foreach ($this->order->items as $item) {
                $this->order_tmp_items[] = [
                    'property_id' => $item->property_id,
                    'dimensions' => $item->dimensions,
                    'count' => $item->quantity
                ];
            }
        } else {
            $this->order_tmp_items[] = [
                'property_id' => null,
                'dimensions' => null,
                'count' => 1
            ];
        }
    }
}
