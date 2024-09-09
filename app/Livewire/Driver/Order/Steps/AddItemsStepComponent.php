<?php

namespace App\Livewire\Driver\Order\Steps;

use Livewire\Attributes\Layout;
use Spatie\LivewireWizard\Components\StepComponent;

#[Layout("driver.layouts.app")]
class AddItemsStepComponent extends StepComponent
{
    public $order_items = [];
    public $washing_type = [];

    public function mount()
    {
        $this->order_items[] = [
            'property_id' => null,
            'dimensions' => null,
            'count' => 1
        ];

        $this->washing_type = [
            "آبشور",
            "اعلاء‌شوئی",
            "کاور",
        ];
    }

    public function addItem()
    {
        $this->order_items[] = [
            'property_id' => null,
            'dimensions' => null,
            'count' => 1
        ];
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
