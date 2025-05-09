<?php

namespace App\Livewire\Comment;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Create extends Component
{
    use WireUiActions;

    public Order $order;
    public $body = "";

    public function mount(Order $order): void
    {
        $this->order = $order;
    }
    public function render()
    {
        return view('livewire.comment.create');
    }

    public function submit()
    {
        try {
            $this->order->comments()->create([
                'body' => $this->body,
                'commenter_type' => Auth::user()::class,
                'commenter_id' => Auth::id(),
            ]);
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'ثبت شد !',
                'description' => 'توضیحات شما با موفقیت ثبت شد!',
            ]);
            $this->body = "";
            $this->dispatch('comment-added');
        } catch (Exception $e)
        {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'خطا! '.$e->getCode(),
                'description' => $e->getMessage(),
            ]);
        }
    }
}
