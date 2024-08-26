<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{

    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'driver_id' => 1,
//            'address_id',
//            'discount',
            'options' => [1,3,4,5,6],
//            'sub_total',
            'total' => $this->faker->randomFloat(0, 1000000, 9900000),
            'status' => $this->faker->randomElement(['in_waiting_list', 'carpets_received', 'pre_wash_repair_service', 'sent_to_factory_for_washing']),
//            'reserved_for',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
