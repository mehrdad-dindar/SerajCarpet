<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    const RESERVED = 'reserved';
    const IN_WAITING_LIST = 'in_waiting_list';
    const CARPETS_RECEIVED = 'carpets_received';
    const PRE_WASH_REPAIR_SERVICE = 'pre_wash_repair_service';
    const SENT_TO_FACTORY_FOR_WASHING = 'sent_to_factory_for_washing';
    const POST_WASH_REPAIR_SERVICE = 'post_wash_repair_service';
    const READY_FOR_DELIVERY_TO_CUSTOMER = 'ready_for_delivery_to_customer';
    const DELIVERED_AND_PAID = 'delivered_and_paid';
    protected $guarded;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function getColor(string $state): string
    {
            return self::where('label', $state)->value('color') ?? "info";
    }

}
