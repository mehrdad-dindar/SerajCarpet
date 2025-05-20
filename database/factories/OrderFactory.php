<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

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
     * @throws RandomException
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'total' => $this->faker->randomFloat(0, 1000000, 9900000),
            'status_id' => random_int(1, 10),
            'created_at' => Carbon::now()->subDays(rand(1, 368)),
            'updated_at' => Carbon::now()->subDays(rand(1, 368)),
        ];
    }
}
