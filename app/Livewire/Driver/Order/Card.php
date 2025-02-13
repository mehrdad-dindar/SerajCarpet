<?php

namespace App\Livewire\Driver\Order;

use App\Models\Address;
use App\Models\Comment;
use App\Models\Order;
use App\Models\OrderStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Card extends Component
{
    use LivewireAlert;

    public Order $order;
    public $mapUrl;

    public string $reason = '';

    protected $rules = [
        'reason' => 'required|string|min:20|max:1200',
    ];
    public function mount()
    {
        $this->getMapUrl();
    }
    public function render()
    {
        return view('livewire.driver.order.card');
    }

    private function getMapUrl()
    {
        if (isset($this->order->address)) {
            $lon = $this->order->address->longitude;
            $lat = $this->order->address->latitude;
            if ($lat && $lon) {
                $tileNumber = $this->getTileNumber($lon, $lat);
            }
            $this->mapUrl = "https://tile.openstreetmap.org/$tileNumber.png";
        } else {
            $this->mapUrl = "https://boland.eu/media/catalog/product/cache/d6214b89674db9acedf5c68b5b049789/0/0/00181_1.webp";
        }
    }

    private function getTileNumber($lon, $lat)
    {
        $zoom = 15;
        $xtile = floor((($lon + 180) / 360) * pow(2, $zoom));
        $ytile = floor((1 - log(tan(deg2rad($lat)) + 1 / cos(deg2rad($lat))) / pi()) /2 * pow(2, $zoom));
        return  $zoom . "/" . $xtile . "/" . $ytile;
    }

    public function updateInvoice(Order $order): Application|Redirector|RedirectResponse
    {
        return redirect(route('driver.orders.edit', $order));
    }

    public function customerCall($phone): Application|Redirector|RedirectResponse
    {
        return redirect("tel:+98".intval($phone));
    }

    public function direction(Address $address): void
    {
        $url = sprintf(
            'https://nshn.ir/?lat=%s&lng=%s',
            $address->latitude,
            $address->longitude
        );
        $this->dispatch('openLink', ['url' => $url]);
    }

    public function cancel()
    {
        $this->validate();
        $this->order->setUpdateDirection(false);
        $this->order->updateOrderStatus(
            OrderStatus::REVISITING_DRIVER,
            Carbon::parse($this->order->time_apply_status ?? now())->addDays(2)
        );
        $this->submitReason();

        $this->dispatch('closeModal', "cancel-{$this->order->id}");
        $this->dispatch('refreshOrderList');

        $this->alert('success', "سفارش {$this->order->customer->name} با موفقیت کنسل شد.");
    }

    private function submitReason(): void
    {
        $comment = new Comment();
        $comment->body = $this->reason;
        $comment->commenter()->associate(auth('driver')->user());
        $this->order->comments()->save($comment);
    }

    public function carpetsReceived()
    {
        $this->order->status_id = OrderStatus::where('name', OrderStatus::CARPETS_RECEIVED)->first()->id;
        $this->order->collected_at = Carbon::now();
        $this->order->save();
    }
    public function revisitingDriver()
    {
        $this->order->status_id = OrderStatus::where('name', OrderStatus::REVISITING_DRIVER)->first()->id;
        $this->order->save();
    }
    public function deliveredAndPaid()
    {
        $this->order->status_id = OrderStatus::where('name', OrderStatus::DELIVERED_AND_PAID)->first()->id;
        $this->order->save();
    }
}
