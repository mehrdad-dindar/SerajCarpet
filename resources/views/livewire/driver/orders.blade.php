<div>
    @if($orders)
        <div class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                <h6 class="inline-block">
                    {{ __("Orders List") .' '. $shiftTitle }}
                </h6>
                <x-srj-mini-badge class="bg-gradient-fuchsia ms-4" rounded :label="\App\Models\OptimizedRoute::getOrdersCount(shift: $shift)" danger/>
            </div>
            <div class="container">
                @livewire('driver.order.grid',['orders'=>$orders])
            </div>
        </div>
    @else
        <x-srj-alert id="alert" :title="__('There are currently no orders.')" warning rounded="2xl" />
    @endif
</div>
