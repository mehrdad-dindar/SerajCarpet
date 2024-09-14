<div class="w-full max-w-full md:flex-none xl:mb-0">
    <div class="relative flex justify-between items-center min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border p-4">
        <div class="relative">
            <a class="block shadow-xl rounded-2xl">
                <img src="{{ asset("panel/img/delivery_truck.png") }}" alt="img-blur-shadow" class="max-h-24 shadow-soft-2xl rounded-2xl" />
            </a>

        </div>
        <div>
            <h5 class="text-center">{!! $this->typeLabel() !!}</h5>
            <x-srj-button icon="rocket-launch" wire:click="getRoute" class="bg-gradient-fuchsia" fuchsia label="مشاهده و مسیریابی"/>
        </div>
        @if($this->getOrdersCount())
            <x-srj-mini-badge :label="$this->getOrdersCount()" rounded class="absolute inset-0" negative/>
        @endif
    </div>
</div>
