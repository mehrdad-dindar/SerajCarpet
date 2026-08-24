<?php

namespace App\Services;

use App\Events\IncomingCall;
use App\Models\CallLog;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CallService
{
    public function normalizePhoneNumber(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $normalized = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($normalized, '0098')) {
            $normalized = '0' . substr($normalized, 4);
        } elseif (str_starts_with($normalized, '98') && strlen($normalized) > 10) {
            $normalized = '0' . substr($normalized, 2);
        } elseif (!str_starts_with($normalized, '0') && strlen($normalized) >= 10) {
            $normalized = '0' . $normalized;
        }

        return $normalized;
    }

    public function handleIncomingCall(array $data): CallLog
    {
        try {
            $callerId = $this->normalizePhoneNumber($data['caller_id'] ?? null);
            if (!$callerId) {
                throw new Exception('شماره تماس نامعتبر است.');
            }

            $customer = Customer::where('phone', $callerId)
                ->orWhere('phone2', $callerId)
                ->first();

            $callLog = CallLog::create([
                'customer_id' => $customer?->id,
                'caller_id'   => $callerId,
                'extension'   => $data['extension'] ?? '101',
                'did'         => $data['did'] ?? null,
                'type'        => 'inbound',
                'uniqueid'    => $data['uniqueid'] ?? null,
                'duration'    => 0,
            ]);

            $customerUrl = $customer
                ? route('filament.admin.resources.customers.edit', $customer->id)
                : route('filament.admin.resources.orders.create', ['phone' => $callerId]);

            $payload = [
                'call_log_id'  => $callLog->id,
                'caller_id'    => $callerId,
                'extension'    => $callLog->extension ?? '101',
                'customer_url' => $customerUrl,
                'uniqueid'     => $callLog->uniqueid,
                'timestamp'    => now()->timestamp,
                'customer'     => $customer ? [
                    'id'       => $customer->id,
                    'name'     => $customer->name ?? 'بدون نام',
                    'phone'    => $customer->phone,
                ] : null,
            ];

            // ذخیره در کش برای شناسایی خودکار در هاست اشتراکی (به مدت ۴۰ ثانیه)
            Cache::put('latest_voip_incoming_call', $payload, now()->addSeconds(40));

            // ارسال پیام به وب‌سوکت در صورت وجود سرور Reverb
            try {
                event(new IncomingCall($callLog));
            } catch (\Throwable $e) {
                Log::info('Websocket broadcast skipped (using cache polling fallback)');
            }

            return $callLog;
        } catch (Exception $e) {
            Log::error('VoIP Call Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function handleCallHangup(array $data): ?CallLog
    {
        try {
            $uniqueId = $data['uniqueid'] ?? null;
            if (!$uniqueId) {
                return null;
            }

            // پاکسازی تماس از کش
            Cache::forget('latest_voip_incoming_call');

            $callLog = CallLog::where('uniqueid', $uniqueId)->first();
            if ($callLog) {
                $callLog->update([
                    'extension'      => $data['extension'] ?? $callLog->extension,
                    'duration'       => (int) ($data['duration'] ?? 0),
                    'type'           => ((int) ($data['duration'] ?? 0)) > 0 ? 'inbound' : 'missed',
                    'recording_file' => $data['recording_file'] ?? null,
                ]);
            }

            return $callLog;
        } catch (Exception $e) {
            Log::error('VoIP Hangup Error: ' . $e->getMessage());
            return null;
        }
    }
}
