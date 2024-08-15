<?php

namespace App\Livewire\Driver\Order\Steps;

use Livewire\Attributes\Layout;
use Spatie\LivewireWizard\Components\StepComponent;

#[Layout("driver.layouts.app")]
class AddItemsStepComponent extends StepComponent
{
    public $order_items = [];

    public function mount()
    {
        $this->order_items[] = ['property_id' => null];
    }

    public function addItem()
    {
        $this->order_items[] = ['property_id' => null];
    }

    public function removeItem($index)
    {
        unset($this->order_items[$index]);

        $this->order_items = array_values($this->order_items);
    }

    public function render()
    {
        return view('livewire.driver.order.steps.add-items-step-component');
    }

    public function submit()
    {
//        $this->validate();

        $this->nextStep();
    }

    public function stepInfo(): array
    {
        return [
            'label' => __("Order Items"),
            'icon' => 'list-bullet',
        ];
    }
}
