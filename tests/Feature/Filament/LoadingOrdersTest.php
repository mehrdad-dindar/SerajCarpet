<?php

namespace Tests\Feature\Filament;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Filament\Pages\LoadingOrders;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Settings\ShiftSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LoadingOrdersTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Driver $driver;
    protected OrderStatus $readyStatus;
    protected OrderStatus $distributionStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->driver = Driver::create([
            'name' => 'رضا احمدی',
            'phone' => '09351112233',
            'status' => 'active',
        ]);

        $this->readyStatus = OrderStatus::create([
            'name' => OrderStatusEnum::READY_FOR_DELIVERY->value,
            'label' => 'آماده تحویل',
            'color' => 'purple',
        ]);

        $this->distributionStatus = OrderStatus::create([
            'name' => OrderStatusEnum::IN_DISTRIBUTION_LIST->value,
            'label' => 'در لیست پخش',
            'color' => 'warning',
        ]);
    }

    public function test_loading_orders_page_can_assign_ready_orders_to_driver(): void
    {
        $customer = Customer::create(['name' => 'سارا نوری', 'phone' => '09129876543']);

        $order1 = Order::create([
            'customer_id' => $customer->id,
            'status_id' => $this->readyStatus->id,
            'total' => 500000,
        ]);

        $order2 = Order::create([
            'customer_id' => $customer->id,
            'status_id' => $this->readyStatus->id,
            'total' => 700000,
        ]);

        Livewire::actingAs($this->admin)
            ->test(LoadingOrders::class)
            ->set('data.operation_type', 'distribution')
            ->set('data.driver_id', $this->driver->id)
            ->set('data.shift', '08:00:00 - 14:00:00')
            ->set('data.orders', [$order1->id, $order2->id])
            ->call('submit')
            ->assertHasNoErrors()
            ->assertNotified('عملیات بارگیری با موفقیت انجام شد');

        $this->assertDatabaseHas('orders', [
            'id' => $order1->id,
            'driver_id' => $this->driver->id,
            'status_id' => $this->distributionStatus->id,
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order2->id,
            'driver_id' => $this->driver->id,
            'status_id' => $this->distributionStatus->id,
        ]);
    }
}
