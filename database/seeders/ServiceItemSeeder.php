<?php

namespace Database\Seeders;

use App\Models\ServiceItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceItem::insert([
           [
               "name" => "فرش",
               "service_id" => 1
           ],[
               "name" => "قالیچه",
               "service_id" => 1
           ],[
               "name" => "پادری",
               "service_id" => 1
           ],
        ]);
    }
}
