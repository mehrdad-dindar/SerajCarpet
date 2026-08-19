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
    public array $dismissedCalls = [];

    public function mount(): void
    {
        $this->checkActiveCalls();
    }

    /**
     * بررسی دوره‌ای تماس‌ها از کش (مخصوص هاست اشتراکی)
     */
    public function checkActiveCalls(): void
    {
        $callData = Cache::get('latest_voip_incoming_call');

        if ($callData && !in_array($callData['call_log_id'] ?? null, $this->dismissedCalls)) {
            // اگر تماس جدیدی در کش ثبت شده که قبلا ندیده‌ایم
            if (!$this->isOpen || ($this->activeCall['call_log_id'] ?? null) !== ($callData['call_log_id'] ?? null)) {
                $this->activeCall = $callData;
                $this->customerUrl = $callData['customer_url'] ?? '';
                $this->isOpen = true;
                $this->dispatch('incoming-call-detected', activeCall: $this->activeCall, customerUrl: $this->customerUrl);
            }
        }
    }

    #[On('echo:voip-calls,.incoming.call')]
    #[On('echo:voip-calls,incoming.call')]
    public function onIncomingCall(array $event): void
    {
        $this->activeCall = $event;
        $this->customerUrl = $event['customer_url'] ?? '';
        $this->isOpen = true;
        $this->dispatch('incoming-call-detected', activeCall: $this->activeCall, customerUrl: $this->customerUrl);
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
        $this->dispatch('stop-ringtone');
    }

    public function render()
    {
        return view('livewire.voip.screen-pop-modal');
    }
}
