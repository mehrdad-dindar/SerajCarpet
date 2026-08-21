<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class OrderStatus extends Model
{
    protected $casts = [
        'has_time' => 'boolean',
    ];

    public const RESERVED = 'reserved';
    public const IN_COLLECTIVE_LIST = 'in_collective_list';
    public const REVISITING_DRIVER = 'revisiting_driver';
    public const CARPETS_RECEIVED = 'carpets_received';
    public const PRE_WASH_REPAIR_SERVICE = 'pre_wash_repair_service';
    public const SENT_TO_FACTORY_FOR_WASHING = 'sent_to_factory_for_washing';
    public const POST_WASH_REPAIR_SERVICE = 'post_wash_repair_service';
    public const READY_FOR_DELIVERY = 'ready_for_delivery';
    public const IN_DISTRIBUTION_LIST = 'in_distribution_list';
    public const DELIVERED_AND_PAID = 'delivered_and_paid';

    protected $guarded = [];

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

    public static function getIdByName(string $name): ?int
    {
        return self::getCachedStatuses()->firstWhere('name', $name)?->id
            ?? self::firstWhere('name', $name)?->id;
    }

    public static function getLabelById(int|string|null $id): string
    {
        if (!$id) {
            return 'N/A';
        }
        return self::getCachedStatuses()->get($id)?->label ?? 'نامشخص';
    }

    public static function getColor(string $state): string
    {
        return self::where('name', $state)->orWhere('label', $state)->value('color') ?? 'info';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'status_id');
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

    public function optimizedRoutes(): HasMany
    {
        return $this->hasMany(OptimizedRoute::class);
    }
}
