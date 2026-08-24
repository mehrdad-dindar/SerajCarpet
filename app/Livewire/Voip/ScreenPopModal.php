<?php

namespace App\Livewire\Voip;

use App\Models\Customer;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;

class ScreenPopModal extends Component
{
    public bool $isOpen = false;
    public ?array $activeCall = null;
    public string $customerUrl = '';
    public string $callStatus = 'ringing'; // 'ringing' | 'connected'
    public ?int $startTime = null;
    public array $dismissedCalls = [];

    public function mount(): void
    {
        $this->checkActiveCalls();
    }

    /**
     * بررسی دوره‌ای وضعیت تماس از کش سرور (همگام با تمام تب‌ها)
     */
    public function checkActiveCalls(): void
    {
        $callData = Cache::get('latest_voip_incoming_call');

        if ($callData && !in_array($callData['call_log_id'] ?? null, $this->dismissedCalls)) {
            $isNewCall = ($this->activeCall['call_log_id'] ?? null) !== ($callData['call_log_id'] ?? null);
            $statusChanged = ($this->callStatus !== ($callData['status'] ?? 'ringing'));

            if (!$this->isOpen || $isNewCall || $statusChanged) {
                $this->activeCall = $callData;
                $this->customerUrl = $callData['customer_url'] ?? '';
                $this->callStatus = $callData['status'] ?? 'ringing';
                $this->startTime = $callData['start_time'] ?? null;
                $this->isOpen = true;

                $this->dispatch('call-state-synced', [
                    'activeCall'  => $this->activeCall,
                    'customerUrl' => $this->customerUrl,
                    'callStatus'  => $this->callStatus,
                    'startTime'   => $this->startTime,
                ]);
            }
        }
    }

    /**
     * ثبت وضعیت پاسخگویی در کش سرور تا سایر تب‌ها هم متوجه شوند
     */
    public function answerCallOnServer(int $startTime): void
    {
        $this->callStatus = 'connected';
        $this->startTime = $startTime;

        $callData = Cache::get('latest_voip_incoming_call');
        if ($callData) {
            $callData['status'] = 'connected';
            $callData['start_time'] = $startTime;
            Cache::put('latest_voip_incoming_call', $callData, now()->addMinutes(30));
        }
    }

    #[On('echo:voip-calls,.incoming.call')]
    #[On('echo:voip-calls,incoming.call')]
    public function onIncomingCall(array $event): void
    {
        $this->activeCall = $event;
        $this->customerUrl = $event['customer_url'] ?? '';
        $this->callStatus = 'ringing';
        $this->isOpen = true;
        $this->dispatch('call-state-synced', [
            'activeCall'  => $this->activeCall,
            'customerUrl' => $this->customerUrl,
            'callStatus'  => 'ringing',
            'startTime'   => null,
        ]);
    }

    public function closePopup(): void
    {
        if ($this->activeCall && isset($this->activeCall['call_log_id'])) {
            $this->dismissedCalls[] = $this->activeCall['call_log_id'];
        }

        Cache::forget('latest_voip_incoming_call');

        $this->isOpen = false;
        $this->activeCall = null;
        $this->customerUrl = '';
        $this->callStatus = 'ringing';
        $this->startTime = null;
        $this->dispatch('stop-ringtone');
    }

    public function render()
    {
        return view('livewire.voip.screen-pop-modal');
    }
}
