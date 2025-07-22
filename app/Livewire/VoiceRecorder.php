<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VoiceRecorder extends Component
{
    public $order;
    public $comment;
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
        // استخراج نوع فایل و پسوند از base64
        if (preg_match('/^data:audio\/(\w+);base64,/', $audioBlob, $matches)) {
            $extension = $matches[1]; // مانند 'wav', 'webm', 'ogg'
            $data = substr($audioBlob, strpos($audioBlob, ',') + 1);
            $data = base64_decode($data);

            // نام فایل با پسوند درست
            $filename = 'voice-note-' . now()->timestamp . '.' . $extension;

            // ایجاد resource stream از محتوا
            $tmpStream = fopen('php://temp', 'r+');
            fwrite($tmpStream, $data);
            rewind($tmpStream);

            // ایجاد comment و ذخیره فایل در media collection
            $this->comment = $this->order->comments()->create([
                'body' => null,
                'commenter_type' => Auth::user()::class,
                'commenter_id' => Auth::id(),
            ]);

            $this->comment->addMediaFromStream($tmpStream)
                ->usingFileName($filename)
                ->toMediaCollection('voice_notes');

            fclose($tmpStream);

            // ریست وضعیت
            $this->recordingTime = 0;
            $this->recordedAudio = null;

            $this->dispatch('comment-added');
        } else {
            throw new \Exception('فرمت فایل صوتی نامعتبر است.');
        }
    }


    private function blobToFile($blob, $fileName)
    {
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'voice');
        file_put_contents($tmpFilePath, base64_decode(explode(',', $blob)[1]));
        return new \Illuminate\Http\File($tmpFilePath);
    }

    public function render()
    {
        return view('livewire.voice-recorder');
    }
}
