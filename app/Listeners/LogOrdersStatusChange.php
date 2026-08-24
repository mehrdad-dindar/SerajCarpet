<?php

namespace App\Listeners;

use App\Events\BulkOrderUpdated;

class LogOrdersStatusChange
{
    public function handle(BulkOrderUpdated $event): void
    {
        // جهت جلوگیری از ایجاد لاگ‌های تکراری، لاگ‌گیری به صورت متمرکز در مدل انجام می‌شود.
    }
}
