<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ServiceSeeder::class,
            ServiceItemSeeder::class,
            PropertySeeder::class,
            CustomerSeeder::class,
            DriverSeeder::class,
            OptionSeeder::class,
            OrderStatusSeeder::class,
            OrderSeeder::class,
            CarpetColorSeeder::class,
            PaymentMethodSeeder::class
        ]);
    }
}
