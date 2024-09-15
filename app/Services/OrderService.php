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

        if ($this->customer) {
            $this->orders = $this->customer->orders()->orderBy('created_at', 'desc')->get();
        } else {
            $this->orders = collect();
        }
    }

    public function getCustomerOrders()
    {
        return $this->orders;
    }

    public function getCustomer(): Customer|Authenticatable|null
    {
        return auth('customer')->user();
    }
}
