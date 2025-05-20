<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::insert([
            [
                'name' => 'پوز',
                'active' => true,
                'description' => 'پرداخت از طریق دستگاه پوز',
            ], [
                'name' => 'آنلاین',
                'active' => true,
                'description' => 'پرداخت آنلاین از تمامی کارت های عضو شبکه شتاب',
            ], [
                'name' => 'نقدی',
                'active' => true,
                'description' => 'پرداخت نقدی',
            ], [
                'name' => 'کارت به کارت',
                'active' => true,
                'description' => 'پرداخت به صورت کارت به کارت',
            ], [
                'name' => 'کیف پول',
                'active' => true,
                'description' => 'پرداخت از حساب کیف پول مجازی',
            ]
        ]);
    }
}
