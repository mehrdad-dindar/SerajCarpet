<?php

namespace App\Helpers;

class ColorHelper
{
    public static function filamentColorToHex(string $color): string
    {
        return match ($color) {
            'primary' => '#3b82f6',   // آبی
            'secondary' => '#94a3b8', // خاکستری
            'success' => '#10b981',   // سبز
            'warning' => '#f59e0b',   // نارنجی
            'danger' => '#ef4444',    // قرمز
            'info' => '#06b6d4',      // فیروزه‌ای
            'gray' => '#6b7280',      // خاکستری
            default => '#94a3b8',     // پیش‌فرض
        };
    }
}
