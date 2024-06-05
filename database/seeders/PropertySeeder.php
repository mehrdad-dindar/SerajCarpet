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
                "unit" => "meter",
                "price" => 40000
            ], [
                "service_item_id" => 1,
                "parent_id" => null,
                "name" => "دستبافت",
                "unit" => "meter",
                "price" => 80000
            ], [
                "service_item_id" => 1,
                "parent_id" => 2,
                "name" => "لاکی",
                "unit" => "meter",
                "price" => 80000
            ], [
                "service_item_id" => 1,
                "parent_id" => 2,
                "name" => "روشن",
                "unit" => "meter",
                "price" => 100000
            ], [
                "service_item_id" => 2,
                "parent_id" => null,
                "name" => "ماشینی",
                "unit" => "meter",
                "price" => 100000
            ], [
                "service_item_id" => 2,
                "parent_id" => 5,
                "name" => "کوجک (۱.۵ متری)",
                "unit" => "takhte",
                "price" => 100000
            ], [
                "service_item_id" => 2,
                "parent_id" => 5,
                "name" => "متوسط (۲ متری)",
                "unit" => "takhte",
                "price" => 150000
            ], [
                "service_item_id" => 2,
                "parent_id" => 5,
                "name" => "بزرگ (۳ متری)",
                "unit" => "takhte",
                "price" => 200000
            ], [
                "service_item_id" => 2,
                "parent_id" => null,
                "name" => "دستبافت",
                "unit" => "takhte",
                "price" => 100000
            ], [
                "service_item_id" => 2,
                "parent_id" => 9,
                "name" => "ذرع چهارک (معمولی)",
                "unit" => "takhte",
                "price" => 100000
            ], [
                "service_item_id" => 1,
                "parent_id" => 9,
                "name" => "ذرع و نیم (معمولی)",
                "unit" => "takhte",
                "price" => 200000
            ], [
                "service_item_id" => 1,
                "parent_id" => 9,
                "name" => "متوسط (معمولی)",
                "unit" => "takhte",
                "price" => 300000
            ], [
                "service_item_id" => 1,
                "parent_id" => 9,
                "name" => "بزرگ (معمولی)",
                "unit" => "takhte",
                "price" => 400000
            ],
        ]);
    }
}
