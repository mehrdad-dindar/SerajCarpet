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
            'options' => $this->faker->randomElements([1, 2, 3, 4, 5, 6, 7], rand(3, 4)),
//            'sub_total',
            'total' => $this->faker->randomFloat(0, 1000000, 9900000),
            'status_id' => random_int(1,10),
//            'reserved_for',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
