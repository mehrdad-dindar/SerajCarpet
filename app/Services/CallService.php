<?php

namespace App\Services;

use App\Events\IncomingCall;
use App\Models\CallLog;
use App\Models\Customer;
use Illuminate\Support\ServiceProvider;

class CallService
{
    public function normalizePhoneNumber(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $normalized = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($normalized, '98')) {
            $normalized = '0' . substr($normalized, 2);
        }

        return $normalized;
    }

    public function handleIncomingCall(array $data): CallLog
    {
        try {
            $callerId = $this->normalizePhoneNumber($data['caller_id']);
            if (!$callerId) {
                throw new \Exception('شماره تماس نامعتبر است.');
            }

            $customer = Customer::firstOrCreate(['phone' => $callerId]);

            $callLog = $customer->callLogs()->create([
                'caller_id' => $callerId,
                'call_type' => $data['call_type'] ?? 'incoming',
                'timestamp' => $data['timestamp'] ?? now(),
            ]);

            event(new IncomingCall($callLog));

            return $callLog;
        } catch (\Exception $e) {
            \Log::error('Error processing incoming call: ' . $e->getMessage());
            throw $e;
        }
    }
}
