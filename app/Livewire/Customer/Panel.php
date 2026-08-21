<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Settings\ShiftSettings;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("پیشخوان مشتری - قالیشویی سراج")]
#[Layout('customer.layouts.app')]
class Panel extends Component
{
    public $customer;
    public ?Order $activeOrder = null;
    public $pendingInvoicesCount = 0;
    public $totalOrdersCount = 0;
    public $recentOrders;

    public function mount(): void
    {
        $this->customer = auth()->guard('customer')->user();

        $this->totalOrdersCount = $this->customer->orders()->count();

        // فاکتورهای در انتظار پرداخت
        $this->pendingInvoicesCount = $this->customer->invoices()
            ->where('status', 'pending')
            ->count();

        // دریافت آخرین سفارش در حال پردازش (فعال)
        $deliveredStatusId = OrderStatus::where('name', OrderStatusEnum::DELIVERED_AND_PAID->value)->value('id');

        $this->activeOrder = $this->customer->orders()
            ->with(['status', 'items.property.serviceItem', 'driver'])
            ->where('status_id', '!=', $deliveredStatusId)
            ->latest()
            ->first();

        // ۳ سفارش اخیر
        $this->recentOrders = $this->customer->orders()
            ->with('status')
            ->latest()
            ->take(3)
            ->get();
    }

    /**
     * محاسبه درصد پیشرفت سفارش فعال برای Progress Bar
     */
    public function getOrderProgressPercentage(): int
    {
        if (!$this->activeOrder || !$this->activeOrder->status) {
            return 0;
        }

        return match ($this->activeOrder->status->name) {
            OrderStatus::RESERVED, OrderStatus::IN_COLLECTIVE_LIST => 15,
            OrderStatus::CARPETS_RECEIVED => 35,
            OrderStatus::SENT_TO_FACTORY_FOR_WASHING, OrderStatus::PRE_WASH_REPAIR_SERVICE => 60,
            OrderStatus::POST_WASH_REPAIR_SERVICE, OrderStatus::READY_FOR_DELIVERY => 85,
            OrderStatus::DELIVERED_AND_PAID => 100,
            default => 20,
        };
    }

    public function render()
    {
        return view('livewire.customer.panel', [
            'progressPercentage' => $this->getOrderProgressPercentage(),
        ]);
    }
}
