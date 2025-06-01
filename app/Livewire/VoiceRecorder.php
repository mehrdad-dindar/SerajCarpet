<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VoiceRecorder extends Component
{
    public $order;
    public $isRecording = false;
    public $recordingTime = 0;
    public $recordedAudio;

    public function mount($order)
    {
        $this->order = $order;
    }

    public function startRecording()
    {
        $this->isRecording = true;
        $this->dispatch('start-recording');
    }

    public function stopRecording()
    {
        $this->isRecording = false;
        $this->dispatch('stop-recording');
    }

    public function saveRecording($audioBlob)
    {
        // تبدیل Blob به فایل
        $file = $this->blobToFile($audioBlob, 'voice-note-'.now()->timestamp.'.wav');
        //        dd($file, $this->recordingTime);

        // ذخیره با Spatie
        $media = $this->order->addMedia($file)
            ->withCustomProperties([
//                'duration' => $this->recordingTime,
                'recorded_at' => now()
            ])
            ->toMediaCollection('voice_notes');
        //        dd($media);
        $this->recordingTime = 0;
        $this->recordedAudio = null;
    }

    private function blobToFile($blob, $fileName)
    {
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'voice');
        file_put_contents($tmpFilePath, base64_decode(explode(',', $blob)[1]));
        return new \Illuminate\Http\File($tmpFilePath);
    }

    public function render()
    {
        //        dd('hi');
        //        return <<<'blade'
        //            <div>
        //                <button wire:click="delete">Delete Post</button>
        //            </div>
        //        blade;
        return view('livewire.voice-recorder');
    }
}
