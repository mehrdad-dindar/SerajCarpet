<?php

namespace Database\Seeders;

use App\Models\CarpetColor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarpetColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ["name" => "آبی", "hex" => "#0000FF"],
            ["name" => "متالیک", "hex" => "#C0C0C0"],
            ["name" => "کاربنی", "hex" => "#2A2A2A"],
            ["name" => "سرمه‌ای", "hex" => "#003366"],
            ["name" => "صورتی", "hex" => "#FFC0CB"],
            ["name" => "یاسی", "hex" => "#9966CC"],
            ["name" => "زرشکی", "hex" => "#FF4500"],
            ["name" => "ترمه‌ای", "hex" => "#808080"],
            ["name" => "دلفینی", "hex" => "#708090"],
            ["name" => "طوسی", "hex" => "#C0C0C0"],
            ["name" => "فیلی", "hex" => "#333333"],
            ["name" => "مشکی", "hex" => "#000000"],
            ["name" => "کرم", "hex" => "#FFFDD0"],
            ["name" => "بژ", "hex" => "#F5F5DC"],
            ["name" => "طلایی", "hex" => "#FFD700"],
            ["name" => "نسکافه‌ای", "hex" => "#A0522D"],
            ["name" => "بادامی", "hex" => "#8B4513"],
            ["name" => "گردویی", "hex" => "#8B4513"],
            ["name" => "سبز پسته‌ای", "hex" => "#556B2F"],
        ];
        CarpetColor::insert($colors);
    }
}
