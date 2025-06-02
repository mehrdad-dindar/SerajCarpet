<div x-data="{ isHolding: false }"
     x-on:mouseleave="isHolding = false"
     class="relative">
    <!-- دکمه ضبط -->
    <button
        x-on:mousedown="isHolding = true; $wire.startRecording()"
        x-on:mouseup="isHolding = false; $wire.stopRecording()"
        x-on:touchstart.passive="isHolding = true; $wire.startRecording()"
        x-on:touchend.passive="isHolding = false; $wire.stopRecording()"
        :class="isHolding ? 'bg-red-500 scale-110' : 'bg-gray-600'"
        class="p-1.5 rounded-full text-white transition-all duration-200">
        <svg x-show="!isHolding" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
        </svg>

        <svg x-show="isHolding" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
        </svg>
    </button>

    <!-- نمایش مدت زمان ضبط -->
    <div x-show="isHolding" class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-2 py-1 rounded">
        <span x-text="$wire.recordingTime"></span> ثانیه
    </div>

    <!-- اسکریپت ضبط صدا -->
    @script
    <script>
        let recorder;
        let audioChunks = [];
        let timerInterval;
        let stream;


        Livewire.on('start-recording', async () => {
            navigator.mediaDevices.getUserMedia({ audio: true })
                .catch(() => {
                    alert('برای ضبط صدا باید دسترسی به میکروفون را فعال کنید');
                });
            stream = await navigator.mediaDevices.getUserMedia({ audio: true });

            {{--recorder = new RecordRTCPromisesHandler(stream, {--}}
            {{--    type: 'audio',--}}
            {{--    mimeType: 'audio/wav',--}}
            {{--    timeSlice: 1000,--}}
            {{--    ondataavailable: (blob) => {--}}
            {{--        audioChunks.push(blob);--}}
            {{--        @this.recordingTime++;--}}
            {{--    }--}}
            {{--});--}}
            recorder = RecordRTC(stream, {
                type: 'audio',
                mimeType: 'audio/x-wav',
                recorderType: StereoAudioRecorder,
                numberOfAudioChannels: 1,
                desiredSampRate: 16000,
            });

            // recorder.record();
            recorder.startRecording();
            @this.recordingTime = 0;

            // Start timer
            timerInterval = setInterval(() => {
                @this.recordingTime++;
            }, 1000);

        });

        Livewire.on('stop-recording', async () => {
            clearInterval(timerInterval);
            await recorder.stopRecording(function() {
                const blob = recorder.getBlob();
                const reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = () => {
                    @this.saveRecording(reader.result);
                    @this.recordingTime = 0;
                }
            });

            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
            {{--const reader = new FileReader();--}}
            {{--reader.readAsDataURL(blob);--}}
            {{--reader.onloadend = () => {--}}
            {{--    @this.recordingTime = 0;--}}
            {{--    @this.saveRecording(reader.result);--}}
            {{--}--}}
        });
    </script>
@endscript
