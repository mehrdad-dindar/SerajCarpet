<div>
    @if($orders)
        <div class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                @php
                    $shift = Carbon\Carbon::now()->hour <= 14 ? \App\Models\OptimizedRoute::MORNING_SHIFT : \App\Models\OptimizedRoute::AFTERNOON_SHIFT;
                    $title =  __("Orders List") . ' ' . __("Shift") . ' ';
                    $title .=  $shift === \App\Models\OptimizedRoute::MORNING_SHIFT ? __("Morning") : __("Afternoon");
                @endphp
                <h6 class="inline-block">{{ $title }}</h6>
                <x-srj-mini-badge class="bg-gradient-fuchsia ms-4" rounded :label="\App\Models\OptimizedRoute::getOrdersCount(shift: $shift)" danger/>
            </div>
            <div class="flex-auto p-4 pt-0 pb-2">
                @livewire('driver.order.list-orders',['orders'=>$orders])
            </div>
        </div>
    @endif
</div>
