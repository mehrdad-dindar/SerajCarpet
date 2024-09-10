<?php

namespace App\Livewire\Driver\Order\Steps;

use App\Enums\SmsPattern;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Property;
use App\Traits\Sms;
use Exception;
use Hashids\Hashids;
use Spatie\LivewireWizard\Components\StepComponent;
use WireUi\Traits\WireUiActions;

class ConfirmStepComponent extends StepComponent
{
    use Sms, WireUiActions;

    public Customer $customer;

    public Order $order;

    public $tmp_order_items = [];

    public $orderItems = [];

    public $totalPrice;

    public $washing_type;

    public function mount()
    {
        $this->tmp_order_items = $this->state()->orderItems();
        $this->orderItems = $this->getOrderItems();
        $this->totalPrice = $this->calculateTotal();
        $this->washing_type = $this->state()->washingType();
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
                $dimensions = (int) $detail[$item->id]['dimensions'] ?? 1;
            }
            $quantity = (int) $detail[$item->id]['quantity'] ?? 0;
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
                    'dimensions' => $item['dimensions'],
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
        try {
            $this->updateOrder();
            dd('test');
            try {
                $hashids = new Hashids('', 6);
                $hashedID = $hashids->encode($this->customer->id);
                $this->sendPattern(
                    $this->customer->phone,
                    SmsPattern::SET_LOCATION,
                    [$this->customer->name, $hashedID]
                );
            } catch (\Exception $e) {
                info($e->getMessage());
            }

            return redirect()->route('driver.panel.orders');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function updateOrder()
    {
        try {
            $orderItems = $this->getOrderItems();
            $order = $this->order->update([
                'total' => $this->totalPrice,
                'options' => $this->washing_type,
            ]);
            $this->order->updateOrderStatus(OrderStatus::CARPETS_RECEIVED);
            $this->updateOrderItems();
            foreach ($this->tmp_order_items as $item) {
                $property = $orderItems->firstWhere('id', $item['property_id']);
                $dimensions = (int) $item['dimensions'] ?? 1;
                $order->items->update([
                    [
                        'property_id' => $property->id,
                    ], [
                        'dimensions' => $dimensions,
                        'quantity' => (int) $item['count'],
                        'unit_price' => $property->price,
                        'sub_total' => (int) $item['count'] * $dimensions * $property->price,
                    ],
                ]);
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function updateOrderItems()
    {
        // 1. بدست آوردن لیست property_id های جدید از $tmp_order_items
        $newPropertyIds = collect($this->tmp_order_items)->pluck('property_id')->toArray();

        // 2. حذف آیتم‌های موجود در سفارش که در لیست جدید نیستند
        $this->order->items()->whereNotIn('property_id', $newPropertyIds)->delete();

        // 3. حلقه برای ایجاد یا به‌روزرسانی آیتم‌ها
        foreach ($this->tmp_order_items as $item) {
            // بدست آوردن یا ساختن آیتم در سفارش (با استفاده از firstOrNew)
            $orderItem = $this->order->items()->firstOrNew(['property_id' => $item['property_id']]);

            // تنظیم مقادیر آیتم (ساخت یا بروزرسانی)
            $dimensions = (int) $item['dimensions'] ?? 1;

            $orderItem->fill([
                'dimensions' => $dimensions,
                'quantity' => (int) $item['count'],
                'unit_price' => $orderItem->property->price ?? $item['price'], // اگر property به عنوان رابطه وجود ندارد
                'sub_total' => (int) $item['count'] * $dimensions * ($orderItem->property->price ?? $item['price']),
            ]);

            // ذخیره آیتم (در صورتی که جدید باشد یا بروزرسانی شده باشد)
            $orderItem->save();
        }
        dd("Done !");
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
            'label' => __('Confirm Order'),
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
