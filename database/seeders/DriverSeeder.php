<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Driver::create([
            'name' => 'رضا راننده',
            'phone' => '09121111111',
            'license' => 98456789,
            'car_tag' => "36الف45678"
        ]);
        Driver::factory()->count(50)->create();

    }
}
