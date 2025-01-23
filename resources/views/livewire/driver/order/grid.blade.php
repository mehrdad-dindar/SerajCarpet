<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
    @if($arrangedOrders)
        @foreach($arrangedOrders as $order)
            <livewire:driver.order.card :order="$order" :key="$order->id" />
        @endforeach
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-livewire-alert::scripts />
<script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
<x-livewire-alert::flash />
@script
<script>
    $wire.on('openLink', (event) => {
        const url = event[0].url;
        window.open(url, '_blank');
    });
    $wire.on('closeModal', (event) => {
        $closeModal(event[0])
    });
</script>
@endscript
