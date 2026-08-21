<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\CallService;
use Illuminate\Console\Command;

class SimulateVoipCall extends Command
{
    protected $signature = 'voip:simulate
                            {phone? : شماره تستی تماس گیرنده}
                            {--ext=101 : شماره داخلی}
                            {--new : تماس از شماره ناشناس}';

    protected $description = 'شبیه سازی تماس ورودی برای تست وب سوکت و پاپ آپ';

    public function handle(CallService $callService): int
    {
        $phone = $this->argument('phone');
        $extension = $this->option('ext');
        $uniqueId = 'sim-' . uniqid();

        if ($this->option('new')) {
            $phone = '09' . rand(100000000, 999999999);
            $this->warn("👤 شماره تصادفی جدید: {$phone}");
        } elseif (!$phone) {
            $existingCustomer = Customer::has('orders')->first() ?? Customer::first();
            $phone = $existingCustomer ? $existingCustomer->phone : '09121234567';
            $this->info("👤 مشتری موجود: {$existingCustomer?->name} ({$phone})");
        }

        $callLog = $callService->handleIncomingCall([
            'caller_id' => $phone,
            'extension' => $extension,
            'did'       => '02177492073',
            'uniqueid'  => $uniqueId,
        ]);

        $this->line("<fg=green>✔ تماس با موفقیت برودکست شد! (شناسه لاگ: {$callLog->id})</>");

        return Command::SUCCESS;
    }
}
