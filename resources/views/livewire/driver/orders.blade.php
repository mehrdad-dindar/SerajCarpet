<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($routeTypes as $type)
            <div wire:click="selectCard('{{ $type->id }}')" class="cursor-pointer">
                @livewire("driver.order.card",['type' => $type],key($type->id))
            </div>
        @endforeach
    </div>
    <div class="mt-5">
        @if($selectedType && $orders)
            <div class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                    <h6>{{ $selectedType->typeLabel() }}</h6>
                </div>
                <div class="flex-auto p-4 pt-0 pb-2">
                    @livewire('driver.order.list-orders',['orders'=>$orders])
                </div>
            </div>
        @else
            <x-srj-alert id="alert" :title="__('Select a card to view orders.')" warning rounded="2xl" />
        @endif
    </div>
</div>
