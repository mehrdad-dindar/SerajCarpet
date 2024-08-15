<div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
    @include('livewire.driver.order.navigation')
    <div class="flex-auto p-4">
        <div class="px-3">
                <div class="flex flex-col h-full">
                    <h5 class="font-bold mb-12">{{__("Add Order Items")}}</h5>
                    <div class="flex flex-col gap-4">
                    @foreach ($order_items as $index => $item)
                        <div class="flex items-center gap-4">
                        <x-srj-select
                            wire:model.live="order_items.{{ $index }}.property_id"
                            :label="__('Select Item')"
                            name="order_items[{{ $index }}][property_id]"
                            class="mb-12"
                            :placeholder="__('Select some Item...')"
                            :async-data="route('property.index')"
                            option-label="fullTitle"
                            option-value="id"
                            required
                        />
                        @if(isset($order_items[$index]['property_id']))
                            <x-srj-select
                                wire:model.live="order_items.{{ $index }}.dimensions"
                                :label="__('Dimensions')"
                                name="order_items[{{ $index }}][dimensions]"
                                class="mb-12"
                                :placeholder="__('Select some Dimensions...')"
                                :async-data="route('property.dimensions', $order_items[$index]['property_id'])"
                                option-label="title"
                                option-value="id"
                            />
                            <div x-data="{ count: 0 }" class="flex items-center gap-x-3">
                                <x-srj-button x-hold.click.repeat.200ms="count--" icon="minus" />

                                <span class="bg-teal-600 text-white px-5 py-1.5 rounded-lg" x-text="count"></span>

                                <x-srj-button x-hold.click.repeat.200ms="count++" icon="plus" />
                            </div>
                        @endif
                        @if ($index > 0)
                            <x-mini-button rounded negative icon="trash" wire:click.prevent="removeItem({{ $index }})"/>
                        @endif
                        </div>
                    @endforeach
                    </div>
                    <div class="col-3 py-4">
{{--                        <button class="btn btn-warning form-control" wire:click.prevent="addItem">+ Add More</button>--}}
                        <x-srj-button warning icon="plus" wire:click.prevent="addItem" :label="__('Add Item')"/>
                    </div>
                    <div class="mt-auto mb-0 font-semibold leading-normal text-sm group text-slate-500 flex justify-between flex-row">
                        <x-srj-button :label="__('Submit')" icon="rocket-launch" wire:click="submit" info/>
                        <div>
                            <x-srj-button :label="__('Create Customer')" icon="user-plus" green hover="success" focus:solid.gray  data-toggle="modal" data-target="#import" x-on:click="$openModal('simpleModal')"/>
                            <livewire:driver.order.create-customer />
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
