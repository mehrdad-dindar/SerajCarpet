<div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
    @include('livewire.driver.order.navigation')
    <div class="flex-auto p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 -mx-3">
            <div class="px-3">
                <div class="flex flex-col h-full">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                        <h1 class="font-bold text-xl">{{ $customer->name }}</h1>
                        <x-srj-badge md icon="phone" positive :label="$this->hideMiddleDigits($customer->phone)" class="ltr"/>
                    </div>
                    <div class="flex gap-2">
                        <x-phosphor.icons::duotone.map-pin-line class="w-5 h-5 me-1" />
                        <p class="text-sm">{{$order->address->getFullAddress()}}</p>
                    </div>
                    <div
                        class="mt-auto mb-0 font-semibold leading-normal text-sm group text-slate-500 flex justify-between flex-row">
                        <x-srj-button :label="__('Approve')" icon="rocket-launch" wire:click="submit"
                                      class="bg-gradient-fuchsia"/>
                    </div>
                </div>
            </div>
            <div class="px-3 mt-12 ml-auto text-center lg:mt-0">
                <div class="h-full bg-gradient-to-tl from-purple-700 to-pink-500 rounded-xl">
                    <img
                        src="{{ asset("panel/img/shapes/waves-white.svg") }}"
                        class="absolute top-0 hidden w-1/2 h-full lg:block" alt="waves"/>
                    <div class="relative flex items-center justify-center h-full">
                        <img class="relative z-[1] w-full pt-6"
                             src="{{ asset("panel/img/illustrations/rocket-white.png") }}"
                             alt="rocket"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
