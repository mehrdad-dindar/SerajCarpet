<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public Customer $customer;
    public function __construct()
    {
        $this->customer = auth()->guard('customer')->user();
    }
    public function index()
    {
        $invoices = $this->customer->invoices;
    }
}
