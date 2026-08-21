<div
    wire:poll.2s="checkActiveCalls"
    x-data="{
        isOpen: @entangle('isOpen'),
        activeCall: @entangle('activeCall'),
        customerUrl: @entangle('customerUrl'),
        callStatus: 'ringing', // 'ringing' | 'connected'
        timerSeconds: 0,
        timerInterval: null,
        startTime: null,
        audioElement: null,

        init() {
            this.audioElement = document.getElementById('seraj-ringtone');

            // بررسی تماس فعال از localStorage
            this.checkActiveCallFromStorage();

            // همگام‌سازی بین تمام تب‌های باز
            window.addEventListener('storage', (event) => {
                if (event.key === 'seraj_active_call') {
                    if (event.newValue) {
                        this.loadCallData(JSON.parse(event.newValue));
                    } else {
                        this.resetCallState();
                    }
                }
            });

            // باز کردن قفل صدای مرورگر
            document.addEventListener('click', () => {
                if (this.audioElement && this.audioElement.paused && this.audioElement.currentTime === 0) {
                    this.audioElement.load();
                }
            }, { once: true });

            // دریافت سیگنال تماس از Livewire
            Livewire.on('incoming-call-detected', (event) => {
                this.activeCall = event.activeCall;
                this.customerUrl = event.customerUrl;
                this.callStatus = 'ringing';
                this.isOpen = true;
                this.playAudio();
            });

            Livewire.on('stop-ringtone', () => {
                this.stopAudio();
                this.stopTimer();
            });
        },

        checkActiveCallFromStorage() {
            const savedCall = localStorage.getItem('seraj_active_call');
            if (savedCall) {
                try {
                    this.loadCallData(JSON.parse(savedCall));
                } catch (e) {
                    localStorage.removeItem('seraj_active_call');
                }
            }
        },

        loadCallData(data) {
            this.activeCall = data.activeCall;
            this.customerUrl = data.customerUrl;
            this.callStatus = data.callStatus || 'connected';
            this.startTime = data.startTime;
            this.isOpen = true;
            this.startTimerFromTimestamp();
        },

        playAudio() {
            if (this.audioElement) {
                this.audioElement.currentTime = 0;
                this.audioElement.loop = true;
                let playPromise = this.audioElement.play();
                if (playPromise !== undefined) {
                    playPromise.catch(() => console.log('Autoplay prevented. Click anywhere on page.'));
                }
            }
        },

        stopAudio() {
            if (this.audioElement) {
                this.audioElement.pause();
                this.audioElement.currentTime = 0;
            }
        },

        startTimer() {
            this.startTime = Date.now();
            localStorage.setItem('seraj_active_call', JSON.stringify({
                activeCall: this.activeCall,
                customerUrl: this.customerUrl,
                callStatus: 'connected',
                startTime: this.startTime
            }));
            this.startTimerFromTimestamp();
        },

        startTimerFromTimestamp() {
            clearInterval(this.timerInterval);
            this.updateSeconds();
            this.timerInterval = setInterval(() => {
                this.updateSeconds();
            }, 1000);
        },

        updateSeconds() {
            if (this.startTime) {
                this.timerSeconds = Math.floor((Date.now() - this.startTime) / 1000);
            }
        },

        stopTimer() {
            clearInterval(this.timerInterval);
            this.timerSeconds = 0;
            this.startTime = null;
        },

        resetCallState() {
            this.stopAudio();
            this.stopTimer();
            this.isOpen = false;
            this.activeCall = null;
            this.customerUrl = '';
            this.callStatus = 'ringing';
        },

        get formattedTime() {
            let m = Math.floor(this.timerSeconds / 60).toString().padStart(2, '0');
            let s = (this.timerSeconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        },

        answerCall() {
            this.stopAudio();
            this.callStatus = 'connected';
            this.startTimer();

            if (this.customerUrl) {
                const newTab = window.open(this.customerUrl, '_blank');
                if (!newTab || newTab.closed || typeof newTab.closed === 'undefined') {
                    window.location.href = this.customerUrl;
                }
            }
        },

        hangupCall() {
            localStorage.removeItem('seraj_active_call');
            this.resetCallState();
            $wire.closePopup();
        }
    }"
    class="relative"
>
    {{-- فایل صوتی زنگ --}}
    <audio id="seraj-ringtone" preload="auto">
        <source src="{{ asset('ring.wav') }}" type="audio/wav">
    </audio>

    {{-- نوار شناور کپسولی مطابق طرح دقیق Dynamic Island --}}
    <template x-if="isOpen && activeCall">
        <div class="fixed top-5 inset-x-0 z-[9999] flex justify-center px-4 pointer-events-none transition-all duration-300" dir="ltr">
            <div
                style="background: #1c1c1e; border: 1px solid rgba(255, 255, 255, 0.12);"
                class="pointer-events-auto flex items-center justify-between gap-4 sm:gap-8 px-4 py-2.5 sm:px-6 sm:py-3 rounded-full shadow-[0_25px_60px_rgba(0,0,0,0.85)] max-w-lg w-full transition-all transform animate-fade-in"
            >

                {{-- آواتار و مشخصات --}}
                <div class="flex items-center gap-3.5 min-w-0">
                    <div
                        style="background: #3a3a3c;"
                        class="relative flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-[#8e8e93]"
                    >
                        <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>

                        {{-- انیمیشن زنگ خوردن --}}
                        <template x-if="callStatus === 'ringing'">
                            <span class="absolute top-0 right-0 flex h-3.5 w-3.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border-2 border-[#1c1c1e]"></span>
                            </span>
                        </template>

                        {{-- نقطه سبز مکالمه --}}
                        <template x-if="callStatus === 'connected'">
                            <span class="absolute top-0 right-0 flex h-3.5 w-3.5">
                                <span class="inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border-2 border-[#1c1c1e]"></span>
                            </span>
                        </template>
                    </div>

                    <div class="min-w-0 flex flex-col justify-center text-left">
                        <div class="flex items-baseline gap-2">
                            <h4
                                class="text-base font-bold text-white truncate max-w-[140px] sm:max-w-[180px]"
                                x-text="activeCall?.customer?.name || 'مشتری جدید'"
                            ></h4>
                            <span
                                class="text-xs sm:text-sm font-mono text-[#8e8e93] tracking-wide truncate"
                                x-text="activeCall?.caller_id || ''"
                            ></span>
                        </div>

                        <div class="text-xs font-mono mt-0.5 flex items-center gap-1.5">
                            <template x-if="callStatus === 'ringing'">
                                <span class="text-amber-400 flex items-center gap-1">
                                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-400 animate-ping"></span>
                                    تماس ورودی (داخلی <span x-text="activeCall?.extension || '۱۰۱'"></span>)
                                </span>
                            </template>

                            <template x-if="callStatus === 'connected'">
                                <div class="flex items-center gap-2">
                                    <span class="text-emerald-400 font-bold flex items-center gap-1">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <span x-text="formattedTime">00:00</span>
                                    </span>
                                    <span class="text-zinc-500">•</span>
                                    <template x-if="customerUrl">
                                        <a
                                            :href="customerUrl"
                                            class="text-xs text-amber-400 hover:text-amber-300 underline font-sans"
                                        >
                                            پرونده مشتری
                                        </a>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- دکمه‌ها --}}
                <div class="flex items-center gap-3 flex-shrink-0">
                    <template x-if="callStatus === 'ringing'">
                        <button
                            x-on:click="answerCall()"
                            type="button"
                            style="background-color: #34c759;"
                            class="w-11 h-11 sm:w-12 sm:h-12 rounded-full text-white flex items-center justify-center shadow-lg shadow-green-500/30 hover:scale-105 active:scale-95 transition-all"
                            title="پاسخ و باز کردن پرونده در تب جدید"
                        >
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                            </svg>
                        </button>
                    </template>

                    <button
                        x-on:click="hangupCall()"
                        type="button"
                        style="background-color: #ff3b30;"
                        class="w-11 h-11 sm:w-12 sm:h-12 rounded-full text-white flex items-center justify-center shadow-lg shadow-red-500/30 hover:scale-105 active:scale-95 transition-all"
                        title="قطع تماس"
                    >
                        <svg class="w-6 h-6 fill-current transform rotate-[135deg]" viewBox="0 0 24 24">
                            <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1 1 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </template>
</div>
