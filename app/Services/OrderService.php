<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Contracts\Auth\Authenticatable;

class OrderService
{
    public $customer;
    public $orders;

    public function __construct()
    {
        $this->customer = $this->getCustomer();

        // اگر مشتری وجود دارد، سفارشات را بارگیری کنید
        if ($this->customer) {
            // گرفتن سفارشات مشتری
            $this->orders = $this->customer->orders()->get(); // بارگیری سفارشات به صورت Collection
        } else {
            $this->orders = collect(); // در صورتی که مشتری یافت نشد، یک Collection خالی
        }
    }

    // برگرداندن سفارشات مشتری
    public function getCustomerOrders()
    {
        return $this->orders;
    }

    // گرفتن مشتری از طریق سیستم احراز هویت
    public function getCustomer(): Customer|Authenticatable|null
    {
        return auth('customer')->user();
    }
}
