<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'reserved',
                'label' => 'رزرو شده',
                'color' => 'primary',
                'has_time' => true
            ],
            [
                'name' => 'in_collective_list',
                'label' => 'در لیست جمعی قرار دارد',
                'color' => 'warning',
                'has_time' => true
            ],
            [
                'name' => 'in_distribution_list',
                'label' => 'در لیست پخشی قرار دارد',
                'color' => 'primary',
                'has_time' => true
            ],
            [
                'name' => 'revisiting_driver',
                'label' => 'مراجعه مجدد راننده',
                'color' => 'primary',
                'has_time' => true
            ],
            [
                'name' => 'carpets_received',
                'label' => 'فرش ها تحویل گرفته شده',
                'color' => 'info',
                'has_time' => false
            ],
            [
                'name' => 'ready_for_deliver',
                'label' => 'اماده تحویل به مشتری',
                'color' => 'success',
                'has_time' => false
            ],
            [
                'name' => 'delivered_and_paid',
                'label' => 'تحویل و تسویه شده',
                'color' => 'success',
                'has_time' => false
            ],
            [
                'name' => 'pre_wash_repair_service',
                'label' => 'خدمات ترمیم پیش از شستشو دارد',
                'color' => 'warning',
                'has_time' => false
            ],
            [
                'name' => 'post_wash_repair_service',
                'label' => 'خدمات ترمیم پس از شستشو دارد',
                'color' => 'danger',
                'has_time' => false
            ],
            [
                'name' => 'sent_to_factory_for_washing',
                'label' => 'جهت شستشو به کارخانه ارسال گردیده',
                'color' => 'success',
                'has_time' => false
            ],
        ];

        OrderStatus::insert($statuses);
    }
}
