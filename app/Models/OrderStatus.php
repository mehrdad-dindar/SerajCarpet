<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class OrderStatus extends Model
{
    const RESERVED = 'reserved';
    const IN_COLLECTIVE_LIST = 'in_collective_list';
    const IN_DISTRIBUTION_LIST = 'in_distribution_list';
    const REVISITING_DRIVER = 'revisiting_driver';
    const CARPETS_RECEIVED = 'carpets_received';
    const READY_FOR_DELIVERY = 'ready_for_deliver';
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
    public static function getCachedStatuses()
    {
        return Cache::rememberForever('order_statuses_all', function () {
            return self::all()->keyBy('id');
        });
    }

    public static function getLabelById($id)
    {
        return self::getCachedStatuses()->get($id)?->label;
    }

    public function typeLabel(): string
    {
        return match ($this->name) {
            OrderStatus::IN_COLLECTIVE_LIST => 'لیست جمعی',
            OrderStatus::IN_DISTRIBUTION_LIST => 'لیست پخشی',
            OrderStatus::REVISITING_DRIVER => 'مراجعه مجدد',
            default => 'وضعیت نامشخص'
        };
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function optimizedRoutes(): HasMany
    {
        return $this->hasMany(OptimizedRoute::class);
    }
}
