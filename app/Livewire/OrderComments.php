<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderComments extends Component
{
    use WithPagination;

    public Order $order;
    protected $listeners = [ 'comment-added' => '$refresh'];
    public function mount(Order $record)
    {
        $this->order = $record;
    }

    public function render()
    {
        return view('livewire.order-comments', [
            'comments' => $this->order->comments()
                ->latest()
                ->paginate(10)
        ]);
    }
}
