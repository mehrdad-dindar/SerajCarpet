<div class="w-full max-w-full md:flex-none xl:mb-0 @if($ordersCount) hover:cursor-pointer @else hover:cursor-not-allowed @endif" wire:click="getRoute">
    <div class="relative flex justify-between items-center min-w-0 break-words bg-white border-0 shadow-md rounded-2xl bg-clip-border p-4 target:shadow-sm">
        <div class="flex gap-2 items-center">
            @switch($type->name)
                @case(\App\Models\OrderStatus::IN_COLLECTIVE_LIST)
                    <x-phosphor.icons::regular.truck class="w-5 h-5 me-1"/>
                    @break
                @case(\App\Models\OrderStatus::IN_DISTRIBUTION_LIST)
                    <x-phosphor.icons::fill.truck class="w-5 h-5 me-1"/>
                @break
                @default
                    <x-phosphor.icons::duotone.repeat class="w-5 h-5 me-1"/>
            @endswitch
            <span class="font-semibold text-sm">{!! $type->typeLabel() !!}</span>
        </div>
        @if($ordersCount)
            <x-srj-badge icon="map-pin" outline negative :label="$ordersCount . ' آدرس'" />
        @endif
    </div>
</div>
