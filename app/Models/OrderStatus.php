<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    const RESERVED = 'reserved';
    const IN_COLLECTIVE_LIST = 'in_collective_list';
    const IN_DISTRIBUTION_LIST = 'in_distribution_list';
    const REVISITING_DRIVER = 'revisiting_driver';
    const CARPETS_RECEIVED = 'carpets_received';
    const READY_FOR_DELIVERY_TO_CUSTOMER = 'ready_for_delivery_to_customer';
    const DELIVERED_AND_PAID = 'delivered_and_paid';
    const PRE_WASH_REPAIR_SERVICE = 'pre_wash_repair_service';
    const POST_WASH_REPAIR_SERVICE = 'post_wash_repair_service';
    const SENT_TO_FACTORY_FOR_WASHING = 'sent_to_factory_for_washing';

    protected $guarded;

    public static function getColor(string $state): string
    {
        return self::where('label', $state)->value('color') ?? "info";
    }

    public static function getLabel($state): string
    {
        if ($state !== null) {
            return self::find($state)->label ?? "--";
        } else {
            return 'N/A';
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
