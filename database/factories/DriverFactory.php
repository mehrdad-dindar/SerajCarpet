<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $letters = [
            'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی'
        ];

        return [
            'name' => fake()->name(),
            'phone' => "06". rand(0,3). rand(0,9). rand(0,9). rand(0,9). rand(0,9). rand(0,9). rand(0,9). rand(0,9). rand(0,9),
            'license' => rand(1000002,4999990),
            'car_tag'=> rand(10,99). ' ' . $letters[array_rand($letters)] . ' ' . rand(100,999) . ' ' . rand(11,99) ,
        ];
    }
}
