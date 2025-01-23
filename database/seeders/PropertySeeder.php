<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Property::insert([
            [
                "service_item_id" => 1,
                "parent_id" => null,
                "name" => "ماشینی",
                "dimensions" => json_encode([6,9,12]),
                "unit" => "meter",
                "price" => 40000
            ], [
                "service_item_id" => 1,
                "parent_id" => null,
                "name" => "دستبافت",
                "dimensions" => null,
                "unit" => "meter",
                "price" => 80000
            ], [
                "service_item_id" => 1,
                "parent_id" => 2,
                "name" => "لاکی",
                "dimensions" => json_encode([6,9,12]),
                "unit" => "meter",
                "price" => 80000
            ], [
                "service_item_id" => 1,
                "parent_id" => 2,
                "name" => "روشن",
                "dimensions" => json_encode([6,9,12]),
                "unit" => "meter",
                "price" => 100000
            ], [
                "service_item_id" => 2,
                "parent_id" => null,
                "name" => "ماشینی",
                "dimensions" => null,
                "unit" => "meter",
                "price" => 100000
            ], [
                "service_item_id" => 2,
                "parent_id" => 5,
                "name" => "کوجک (۱.۵ متری)",
                "dimensions" => null,
                "unit" => "takhte",
                "price" => 100000
            ], [
                "service_item_id" => 2,
                "parent_id" => 5,
                "name" => "متوسط (۲ متری)",
                "dimensions" => null,
                "unit" => "takhte",
                "price" => 150000
            ], [
                "service_item_id" => 2,
                "parent_id" => 5,
                "name" => "بزرگ (۳ متری)",
                "dimensions" => null,
                "unit" => "takhte",
                "price" => 200000
            ], [
                "service_item_id" => 2,
                "parent_id" => null,
                "name" => "دستبافت",
                "dimensions" => null,
                "unit" => "takhte",
                "price" => 100000
            ], [
                "service_item_id" => 2,
                "parent_id" => 9,
                "name" => "ذرع چهارک (معمولی)",
                "dimensions" => null,
                "unit" => "takhte",
                "price" => 100000
            ], [
                "service_item_id" => 1,
                "parent_id" => 9,
                "name" => "ذرع و نیم (معمولی)",
                "dimensions" => null,
                "unit" => "takhte",
                "price" => 200000
            ], [
                "service_item_id" => 1,
                "parent_id" => 9,
                "name" => "متوسط (معمولی)",
                "dimensions" => null,
                "unit" => "takhte",
                "price" => 300000
            ], [
                "service_item_id" => 1,
                "parent_id" => 9,
                "name" => "بزرگ (معمولی)",
                "dimensions" => null,
                "unit" => "takhte",
                "price" => 400000
            ],
        ]);
    }
}
