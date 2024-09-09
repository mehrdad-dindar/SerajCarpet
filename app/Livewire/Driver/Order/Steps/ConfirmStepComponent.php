<?php

namespace App\Livewire\Driver\Order\Steps;

use App\Enums\OrderStatus;
use App\Enums\SmsPattern;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Property;
use App\Traits\Sms;
use Hashids\Hashids;
use Livewire\Component;
use Spatie\LivewireWizard\Components\StepComponent;
use WireUi\Traits\WireUiActions;

class ConfirmStepComponent extends StepComponent
{
    use WireUiActions,Sms;
    public Customer $customer;
    public $tmp_order_items = [];
    public $orderItems = [];
    public $totalPrice;

    public $washing_type;

    public function mount()
    {
        $this->tmp_order_items = $this->state()->orderItems();
        $this->orderItems = $this->getOrderItems();
        $this->totalPrice = $this->calculateTotal();
        $this->washing_type = $this->state()->whashingType();
    }

    public function getOrderItems()
    {
        $items = collect();
        foreach ($this->tmp_order_items as $item) {
            if ($item['property_id']) {
                $items->push(Property::find($item['property_id']));
            }
        }

        return $items;
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
            $unitPrice = $item->price;
            $total += $dimensions * $quantity * $unitPrice;
        }
        if ($total) {
            return $total;
        } else {
            return 1;
        }
    }

    private function getDetails()
    {
        $details = [];
        foreach ($this->tmp_order_items as $item) {
            if ($item['property_id']) {
                $details[$item['property_id']] = [
                    'quantity' => $item['count'],
                    'dimensions' => $item['dimensions']
                ];
            }
        }
        return $details;
    }

    public function render()
    {
        $orderItems = $this->getOrderItems();
        return view('livewire.driver.order.steps.confirm-step-component', [
            'customer' => $this->customer,
            'orderItems' => $orderItems,
            'details' => $this->getDetails(),
            'totalPrice' => $this->totalPrice,
            'washing_type' => $this->washing_type,
        ]);
    }

    public function submit()
    {
        $this->submitOrder();
    }

    public function submitOrder()
    {
        $customer = Customer::find((int)$this->state()->customer());
        $orderItems = $this->getOrderItems();
        $washing_type = $this->state()->whashingType();

        try {
            $order = Order::create([
                'customer_id' => $this->customer->id,
                'total' => $this->totalPrice,
                'options' => $this->washing_type,
                'driver_id' => auth('driver')->user()->id,
                'status' => OrderStatus::IN_WAITING_LIST //TODO: check Again
            ]);
            foreach ($this->tmp_order_items as $item) {
                $property = $orderItems->firstWhere("id",$item['property_id']);
                $dimensions = (int)$item['dimensions'] ?? 1;
                $order->items()->create([
                    'property_id' => $property->id,
                    'dimensions' => $dimensions,
                    'quantity' => (int)$item['count'],
                    'unit_price' => $property->price,
                    'sub_total' => (int)$item['count'] * $dimensions * $property->price,
                ]);
            }


            try {
                $hashids = new Hashids('',6);
                $hashedID = $hashids->encode($this->customer->id);
                $this->sendPattern($this->customer->phone,SmsPattern::SET_LOCATION,array($this->customer->name,$hashedID));
            } catch (\Exception $e) {
                info($e->getMessage());
            }

            return redirect()->route('driver.panel.orders');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function confirm()
    {
        $customer_id = $this->state()->customer();
        $orderItems = $this->state()->orderItems();

        // here you should store the amount and address somehow

        $this->redirect(route('order-confirmed'));
    }

    public function stepInfo(): array
    {
        return [
            'label' => __("Confirm Order"),
            'icon' => 'check-badge',
        ];
    }

    public function successNotification(): void

    {

        $this->notification()->send([

            'icon' => 'success',

            'title' => 'Success Notification!',

            'description' => 'This is a description.',

        ]);

    }
}
