<div>
    @if($orders)
        <div class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                @php
                    $title = __("Orders List") . ' ' . __("Shift") . ' ';
                @endphp

                <h6 class="inline-block">
                    {{ $title }}
                    @if ($shift === \App\Models\OptimizedRoute::MORNING_SHIFT)
                        {{ __("Morning") }}
                    @else
                        {{ __("Afternoon") }}
                    @endif
                </h6>
                <x-srj-mini-badge class="bg-gradient-fuchsia ms-4" rounded :label="\App\Models\OptimizedRoute::getOrdersCount(shift: $shift)" danger/>
            </div>
            <div class="flex-auto p-4 pt-0 pb-2">
                {{--@livewire('driver.order.list-orders',['orders'=>$orders])--}}
                @livewire('driver.order.grid',['orders'=>$orders])
            </div>
        </div>
    @else
        <x-srj-alert id="alert" :title="__('There are currently no orders.')" warning rounded="2xl" />
    @endif
</div>
