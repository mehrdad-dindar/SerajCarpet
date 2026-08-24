<?php

namespace Tests\Unit\Services;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Models\Customer;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Property;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Services\CarpetPricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarpetPricingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CarpetPricingService $pricingService;
    protected Property $machineCarpetProperty;
    protected Property $blanketProperty;
    protected OrderStatus $defaultStatus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pricingService = new CarpetPricingService();

        // ایجاد وضعیت پیش‌فرض سفارش برای تست‌ها
        $this->defaultStatus = OrderStatus::create([
            'name' => OrderStatusEnum::RESERVED->value,
            'label' => 'رزرو اولیه',
            'color' => 'gray',
        ]);

        $service = Service::create(['name' => 'شستشو']);
        $serviceItem = ServiceItem::create([
            'service_id' => $service->id,
            'name' => 'فرش ماشینی',
        ]);

        // قیمت متری 50,000 تومان
        $this->machineCarpetProperty = Property::create([
            'service_item_id' => $serviceItem->id,
            'name' => 'شستشوی معمولی',
            'unit' => 'meter',
            'price' => 50000,
        ]);

        // قیمت عددی (پتو) 80,000 تومان
        $this->blanketProperty = Property::create([
            'service_item_id' => $serviceItem->id,
            'name' => 'شستشوی پتو',
            'unit' => 'item',
            'price' => 80000,
        ]);
    }

    public function test_it_calculates_subtotal_for_meter_based_carpet(): void
    {
        // فرش 12 متری (dimensions = 12), تعداد = 2 -> 12 * 2 * 50,000 = 1,200,000
        $result = $this->pricingService->calculateItemSubTotal(
            property: $this->machineCarpetProperty,
            dimensions: 12.0,
            quantity: 2
        );

        $this->assertEquals(1200000, $result['sub_total']);
        $this->assertEquals(50000, $result['unit_price']);
    }

    public function test_it_calculates_subtotal_for_item_based_products_correctly(): void
    {
        // 3 تخته پتو به قیمت عددی 80,000 -> 3 * 80,000 = 240,000
        $result = $this->pricingService->calculateItemSubTotal(
            property: $this->blanketProperty,
            dimensions: 1.0,
            quantity: 3
        );

        $this->assertEquals(240000, $result['sub_total']);
    }

    public function test_it_calculates_total_with_ancillary_options_and_discounts(): void
    {
        $nanoOption = Option::create([
            'name' => 'نانوشویی',
            'is_default' => false,
        ]);

        $customer = Customer::create(['name' => 'علی رضایی', 'phone' => '09123456789']);
        $order = Order::create([
            'customer_id' => $customer->id,
            'status_id'   => $this->defaultStatus->id,
            'discount'    => 50000,
        ]);

        // فرش 6 متری: 6 * 1 * 50,000 = 300,000
        OrderItem::create([
            'order_id' => $order->id,
            'property_id' => $this->machineCarpetProperty->id,
            'dimensions' => 6,
            'quantity' => 1,
            'unit_price' => 50000,
            'sub_total' => 300000,
            'options' => [$nanoOption->id],
            'is_custom' => false,
        ]);

        // قلم کالای سفارشی (متفرقه): 1 عدد به قیمت 100,000
        OrderItem::create([
            'order_id' => $order->id,
            'title' => 'شستشوی روفرشی دستی',
            'quantity' => 1,
            'unit_price' => 100000,
            'sub_total' => 100000,
            'is_custom' => true,
        ]);

        $totals = $this->pricingService->calculateOrderTotals($order);

        // SubTotal = 300,000 + 100,000 = 400,000
        // GrandTotal = 400,000 - 50,000 (تخفیف) = 350,000
        $this->assertEquals(400000, $totals['sub_total']);
        $this->assertEquals(350000, $totals['total']);

        $this->pricingService->syncAndSaveOrderTotals($order);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'total' => 350000,
            'sub_total' => 400000,
        ]);
    }
}
