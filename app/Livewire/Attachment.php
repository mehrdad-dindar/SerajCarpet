<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Attachment extends Component
{
    use WithFileUploads;

    #[Validate('nullable|mimes:jpg,jpeg,png,webp,mp4,webm,mov,avi|max:50480')] // 20MB
    public $attachment;
    public Order $order;

    public function save()
    {
        $this->validate();

        if (!$this->attachment) return;

        $comment = $this->order->comments()->create([
            'body' => null,
            'commenter_type' => Auth::user()::class,
            'commenter_id' => Auth::id(),
        ]);

        $comment->addMedia($this->attachment->getRealPath())
            ->usingFileName($this->attachment->getClientOriginalName())
            ->toMediaCollection('attachments');

        $this->reset('attachment');
        $this->dispatch('comment-added');
    }


    public function updatedAttachment()
    {
        $this->save();
    }


    public function render()
    {
        return view('livewire.attachment');
    }
}
