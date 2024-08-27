<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Order::factory()->count(50)->create()->each(function ($order) {
            $address = Address::factory()->create([
                'customer_id' => $order->customer->id,
                'state' => 'تهران',
                'city' => 'تهران',
                'address' => 'آدرس تستی ' . random_int(100, 500),
                'no' => random_int(1, 100),
                'floor' => random_int(1, 10),
                'unit' => random_int(1, 10),
                'municipality_zone' => random_int(1, 22),
                'neighbourhood' => fake()->streetName,
                'latitude' => floatval("35." . random_int(612367, 777689)),
                'longitude' => floatval("51." . random_int(262341, 560316)),
                'is_active' => true,
            ]);

             $order->address_id = $address->id;
             $order->save();
        });
    }
}
