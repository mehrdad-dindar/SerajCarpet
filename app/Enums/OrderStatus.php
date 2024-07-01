<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel
{
    case RESERVED = 'reserved';
    case IN_WAITING_LIST = 'in_waiting_list';
    case CARPETS_RECEIVED = 'carpets_received';
    case PRE_WASH_REPAIR_SERVICE = 'pre_wash_repair_service';
    case SENT_TO_FACTORY_FOR_WASHING = 'sent_to_factory_for_washing';
    case POST_WASH_REPAIR_SERVICE = 'post_wash_repair_service';
    case READY_FOR_DELIVERY_TO_CUSTOMER = 'ready_for_delivery_to_customer';
    case DELIVERED_AND_PAID = 'delivered_and_paid';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::RESERVED => 'رزرو شده',
            self::IN_WAITING_LIST => 'در لیست جمعی قرار دارد',
            self::CARPETS_RECEIVED => 'فرش ها تحویل گرفته شده',
            self::PRE_WASH_REPAIR_SERVICE => 'خدمات ترمیم پیش از شستشو دارد',
            self::SENT_TO_FACTORY_FOR_WASHING => 'جهت شستشو به کارخانه ارسال گردیده',
            self::POST_WASH_REPAIR_SERVICE => 'خدمات ترمیم پس از شستشو دارد',
            self::READY_FOR_DELIVERY_TO_CUSTOMER => 'اماده تحویل به مشتری',
            self::DELIVERED_AND_PAID => 'تحویل و تسویه شده',
            default => 'وضعیت نامشخص',
        };
    }
}
