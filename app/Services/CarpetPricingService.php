<?php

namespace App\Services;

use App\Models\Option;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Property;

class CarpetPricingService
{
    /**
     * Calculate subtotal for a standard order item based on property unit and options.
     */
    public function calculateItemSubTotal(
        Property $property,
        float $dimensions = 1,
        int $quantity = 1,
        ?int $customUnitPrice = null,
        array $optionIds = []
    ): array {
        $unitPrice = $customUnitPrice ?? (int) $property->price;
        $unit = $property->unit ?? 'meter';

        // Area or multiplier calculation
        $multiplier = match ($unit) {
            'meter' => max(0.1, $dimensions), // Minimum 0.1 m2
            'takhte', 'item' => 1.0,
            default => max(1.0, $dimensions),
        };

        $baseTotal = (int) round($multiplier * $quantity * $unitPrice);

        // Calculate selected ancillary services/options if priced
        $optionsTotal = 0;
        if (!empty($optionIds)) {
            $options = Option::whereIn('id', $optionIds)->get();
            foreach ($options as $option) {
                // If options have additional price in DB
                if (isset($option->price) && $option->price > 0) {
                    $optionsTotal += (int) ($option->price * $quantity);
                }
            }
        }

        $subTotal = $baseTotal + $optionsTotal;

        return [
            'dimensions'  => $dimensions,
            'quantity'    => $quantity,
            'unit_price'  => $unitPrice,
            'base_total'  => $baseTotal,
            'options_fee' => $optionsTotal,
            'sub_total'   => $subTotal,
        ];
    }

    /**
     * Calculate subtotal for custom items (Other Items).
     */
    public function calculateCustomItemSubTotal(int $quantity, int $unitPrice): int
    {
        return max(0, $quantity * $unitPrice);
    }

    /**
     * Calculate grand totals for an entire Order model.
     */
    public function calculateOrderTotals(Order $order): array
    {
        $subTotal = 0;

        // 1. Standard Items
        $order->loadMissing(['items.property', 'otherItems']);

        foreach ($order->items as $item) {
            if ($item->property) {
                $optionIds = is_array($item->options) ? $item->options : [];
                $calc = $this->calculateItemSubTotal(
                    $item->property,
                    (float) ($item->dimensions ?? 1),
                    (int) ($item->quantity ?? 1),
                    $item->unit_price ? (int) $item->unit_price : null,
                    $optionIds
                );
                $subTotal += $calc['sub_total'];
            } else {
                $subTotal += (int) ($item->sub_total ?? 0);
            }
        }

        // 2. Custom Items (Other Items)
        foreach ($order->otherItems as $customItem) {
            $subTotal += (int) ($customItem->sub_total ?? ($customItem->quantity * $customItem->unit_price));
        }

        $discount = (int) ($order->discount ?? 0);
        $grandTotal = max(0, $subTotal - $discount);

        return [
            'sub_total' => $subTotal,
            'discount'  => $discount,
            'total'     => $grandTotal,
        ];
    }

    /**
     * Recalculate and persist totals directly on the order.
     */
    public function syncAndSaveOrderTotals(Order $order): Order
    {
        $totals = $this->calculateOrderTotals($order);

        $order->forceFill([
            'sub_total' => $totals['sub_total'],
            'discount'  => $totals['discount'],
            'total'     => $totals['total'],
        ])->saveQuietly();

        return $order;
    }

    /**
     * Static helper for Filament Form reactive recalculations.
     */
    public static function calculateFromFormData(callable $get): int
    {
        $items = $get('items') ?? [];
        $otherItems = $get('otherItems') ?? [];
        $total = 0;

        foreach ($items as $item) {
            $dimensions = isset($item['dimensions']) && is_numeric($item['dimensions']) ? (float) $item['dimensions'] : 1.0;
            $quantity = isset($item['quantity']) && is_numeric($item['quantity']) ? (int) $item['quantity'] : 1;
            $unitPrice = isset($item['unit_price']) ? (int) str_replace(',', '', (string) $item['unit_price']) : 0;

            if ($unitPrice === 0 && !empty($item['property_id'])) {
                $property = Property::find($item['property_id']);
                $unitPrice = $property?->price ?? 0;
            }

            $total += (int) round($dimensions * $quantity * $unitPrice);
        }

        foreach ($otherItems as $customItem) {
            $quantity = isset($customItem['quantity']) && is_numeric($customItem['quantity']) ? (int) $customItem['quantity'] : 1;
            $unitPrice = isset($customItem['unit_price']) ? (int) str_replace(',', '', (string) $customItem['unit_price']) : 0;
            $total += ($quantity * $unitPrice);
        }

        return max(0, $total);
    }
}
