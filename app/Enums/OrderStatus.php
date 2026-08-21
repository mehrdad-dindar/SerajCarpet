<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor, HasIcon
{
    case RESERVED = 'reserved';
    case IN_COLLECTIVE_LIST = 'in_collective_list';
    case REVISITING_DRIVER = 'revisiting_driver';
    case CARPETS_RECEIVED = 'carpets_received';
    case PRE_WASH_REPAIR_SERVICE = 'pre_wash_repair_service';
    case SENT_TO_FACTORY_FOR_WASHING = 'sent_to_factory_for_washing';
    case POST_WASH_REPAIR_SERVICE = 'post_wash_repair_service';
    case READY_FOR_DELIVERY = 'ready_for_delivery';
    case IN_DISTRIBUTION_LIST = 'in_distribution_list';
    case DELIVERED_AND_PAID = 'delivered_and_paid';

    public function getLabel(): string
    {
        return match ($this) {
            self::RESERVED => 'رزرو اولیه',
            self::IN_COLLECTIVE_LIST => 'در لیست جمع‌آوری راننده',
            self::REVISITING_DRIVER => 'مراجعه مجدد راننده',
            self::CARPETS_RECEIVED => 'تحویل گرفته شد (ورود به کارخانه)',
            self::PRE_WASH_REPAIR_SERVICE => 'رفوگری و ترمیم پیش از شستشو',
            self::SENT_TO_FACTORY_FOR_WASHING => 'در حال شستشو در سالن کارخانه',
            self::POST_WASH_REPAIR_SERVICE => 'رفوگری و پرداخت پس از شستشو',
            self::READY_FOR_DELIVERY => 'کنترل کیفیت و آماده تحویل',
            self::IN_DISTRIBUTION_LIST => 'در لیست پخش راننده',
            self::DELIVERED_AND_PAID => 'تحویل مشتری و تسویه کامل',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::RESERVED => 'gray',
            self::IN_COLLECTIVE_LIST, self::IN_DISTRIBUTION_LIST => 'warning',
            self::REVISITING_DRIVER => 'danger',
            self::CARPETS_RECEIVED => 'info',
            self::PRE_WASH_REPAIR_SERVICE, self::POST_WASH_REPAIR_SERVICE => 'orange',
            self::SENT_TO_FACTORY_FOR_WASHING => 'sky',
            self::READY_FOR_DELIVERY => 'purple',
            self::DELIVERED_AND_PAID => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::RESERVED => 'heroicon-o-clock',
            self::IN_COLLECTIVE_LIST, self::IN_DISTRIBUTION_LIST => 'heroicon-o-truck',
            self::REVISITING_DRIVER => 'heroicon-o-arrow-path',
            self::CARPETS_RECEIVED => 'heroicon-o-building-storefront',
            self::PRE_WASH_REPAIR_SERVICE, self::POST_WASH_REPAIR_SERVICE => 'heroicon-o-scissors',
            self::SENT_TO_FACTORY_FOR_WASHING => 'heroicon-o-sparkles',
            self::READY_FOR_DELIVERY => 'heroicon-o-check-circle',
            self::DELIVERED_AND_PAID => 'heroicon-o-banknotes',
        };
    }
}
