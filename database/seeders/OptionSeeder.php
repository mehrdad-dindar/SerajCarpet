<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            [
                "name" => "آبشور",
                "is_default" => true
            ],
            [
                "name" => "اعلاء‌شوئی",
                "is_default" => true
            ],
            [
                "name" => "براق‌شویی",
                "is_default" => false
            ],
            [
                "name" => "رنگ‌برداری",
                "is_default" => false
            ],
            [
                "name" => "رفوگری",
                "is_default" => false
            ],
            [
                "name" => "پرداخت",
                "is_default" => false
            ],
            [
                "name" => "کاور",
                "is_default" => true
            ]
        ];
        Option::insert($options);
    }
}
