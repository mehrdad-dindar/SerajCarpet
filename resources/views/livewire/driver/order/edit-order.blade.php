<div>
    <h1 class="text-lg">{{ __("Edit Order") . " #" .$order->id }}</h1>

    <form wire:submit="save">
        {{ $this->form }}
    </form>
</div>
