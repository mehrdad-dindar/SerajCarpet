<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor, HasIcon
{
    case RESERVED                   = 'reserved';
    case IN_COLLECTIVE_LIST         = 'in_collective_list';
    case REVISITING_DRIVER          = 'revisiting_driver';
    case CARPETS_RECEIVED           = 'carpets_received';
    case PRE_WASH_REPAIR_SERVICE    = 'pre_wash_repair_service';
    case SENT_TO_FACTORY_FOR_WASHING = 'sent_to_factory_for_washing';
    case POST_WASH_REPAIR_SERVICE   = 'post_wash_repair_service';
    case READY_FOR_DELIVERY         = 'ready_for_delivery';
    case IN_DISTRIBUTION_LIST       = 'in_distribution_list';
    case DELIVERED_AND_PAID         = 'delivered_and_paid';

    public function getLabel(): string
    {
        return match ($this) {
            self::RESERVED                   => 'رزرو اولیه',
            self::IN_COLLECTIVE_LIST         => 'در لیست جمع‌آوری راننده',
            self::REVISITING_DRIVER          => 'مراجعه مجدد راننده',
            self::CARPETS_RECEIVED           => 'ورود به کارخانه (تحویل شد)',
            self::PRE_WASH_REPAIR_SERVICE    => 'رفوگری قبل از شستشو',
            self::SENT_TO_FACTORY_FOR_WASHING => 'در حال شستشو در سالن',
            self::POST_WASH_REPAIR_SERVICE   => 'رفوگری و پرداخت بعد شستشو',
            self::READY_FOR_DELIVERY         => 'کنترل کیفیت و آماده تحویل',
            self::IN_DISTRIBUTION_LIST       => 'در لیست پخش و توزیع',
            self::DELIVERED_AND_PAID         => 'تحویل مشتری و تسویه کامل',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::RESERVED                   => 'slate',
            self::IN_COLLECTIVE_LIST         => 'amber',
            self::REVISITING_DRIVER          => 'rose',
            self::CARPETS_RECEIVED           => 'indigo',
            self::PRE_WASH_REPAIR_SERVICE    => 'orange',
            self::SENT_TO_FACTORY_FOR_WASHING => 'cyan',
            self::POST_WASH_REPAIR_SERVICE   => 'violet',
            self::READY_FOR_DELIVERY         => 'teal',
            self::IN_DISTRIBUTION_LIST       => 'sky',
            self::DELIVERED_AND_PAID         => 'emerald',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::RESERVED                   => 'heroicon-m-calendar-days',
            self::IN_COLLECTIVE_LIST         => 'heroicon-m-truck',
            self::REVISITING_DRIVER          => 'heroicon-m-arrow-path',
            self::CARPETS_RECEIVED           => 'heroicon-m-building-storefront',
            self::PRE_WASH_REPAIR_SERVICE    => 'heroicon-m-scissors',
            self::SENT_TO_FACTORY_FOR_WASHING => 'heroicon-m-sparkles',
            self::POST_WASH_REPAIR_SERVICE   => 'heroicon-m-paint-brush',
            self::READY_FOR_DELIVERY         => 'heroicon-m-check-badge',
            self::IN_DISTRIBUTION_LIST       => 'heroicon-m-map-pin',
            self::DELIVERED_AND_PAID         => 'heroicon-m-banknotes',
        };
    }
}
