<div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
    @include('livewire.driver.order.navigation')
    <div class="flex-auto p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 -mx-3">
            <div class="px-3">
                <div class="flex flex-col h-full">
                    <h5 class="font-bold mb-12">{{__("Select Or Create Customer")}}</h5>
                    <x-srj-select
                                wire:model="customer_id"
                                :label="__('Select Customer')"
                                name="customer_id"
                                class="mb-12"
                                :placeholder="__('Select some customer...')"
                                :async-data="route('customer.index')"
                                option-label="id_name"
                                option-value="id"
                                required
                            />
                    <div class="mt-auto mb-0 font-semibold leading-normal text-sm group text-slate-500 flex justify-between flex-row">
                        <x-srj-button :label="__('Submit')" icon="rocket-launch" wire:click="submit" info/>
                        <div>
                            <x-srj-button :label="__('Create Customer')" icon="user-plus" green hover="success" focus:solid.gray  data-toggle="modal" data-target="#import" x-on:click="$openModal('simpleModal')"/>
                            <livewire:driver.order.create-customer />
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-3 mt-12 ml-auto text-center lg:mt-0">
                <div class="h-full bg-gradient-to-tl from-purple-700 to-pink-500 rounded-xl">
                    <img
                        src="{{ asset("panel/img/shapes/waves-white.svg") }}"
                        class="absolute top-0 hidden w-1/2 h-full lg:block" alt="waves"/>
                    <div class="relative flex items-center justify-center h-full">
                        <img class="relative z-20 w-full pt-6"
                             src="{{ asset("panel/img/illustrations/rocket-white.png") }}"
                             alt="rocket"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{--<div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">--}}
{{--    @include('livewire.driver.order.navigation')--}}
{{--    <div class="flex-auto p-4">--}}
{{--        <div class="flex flex-wrap -mx-3">--}}
{{--            <div class="max-w-full px-3 lg:w-1/2 lg:flex-none">--}}
{{--                <div class="flex flex-col h-full">--}}
{{--                    <p class="pt-2 mb-1 font-semibold">Built by developers</p>--}}
{{--                    <x-srj-select--}}
{{--                        wire:model="customer_id"--}}
{{--                        name="customer_id"--}}
{{--                        label="Search a User"--}}
{{--                        placeholder="Select some user"--}}
{{--                        :async-data="route('customer.index')"--}}
{{--                        option-label="id_name"--}}
{{--                        option-value="id"--}}
{{--                        required--}}
{{--                    />--}}
{{--                    <p class="mb-12">From colors, cards, typography to complex elements, you will find the full documentation.</p>--}}
{{--                    <a class="mt-auto mb-0 font-semibold leading-normal text-sm group text-slate-500" href="javascript:;">--}}
{{--                        Read More--}}
{{--                        <i class="fas fa-arrow-right ease-bounce text-sm group-hover:translate-x-1.25 ml-1 leading-normal transition-all duration-200"></i>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="max-w-full px-3 mt-12 ml-auto text-center lg:mt-0 lg:w-5/12 lg:flex-none">--}}
{{--                <div class="h-full bg-gradient-to-tl from-purple-700 to-pink-500 rounded-xl">--}}
{{--                    <img src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard/assets/img/shapes/waves-white.svg" class="absolute top-0 hidden w-1/2 h-full lg:block" alt="waves" />--}}
{{--                    <div class="relative flex items-center justify-center h-full">--}}
{{--                        <x-button label="Submit" wire:click="submit"/>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
