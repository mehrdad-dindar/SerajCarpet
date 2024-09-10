<div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
    @include('livewire.driver.order.navigation')
    <div class="flex-auto p-4">
        <div class="px-3">
                <div class="flex flex-col h-full">
                    <h5 class="font-bold mb-12">{{__("Add Order Items")}}</h5>
                    <div class="flex flex-col gap-4">
                    @foreach ($order_tmp_items as $index => $item)
                        <div class="grid grid-cols-1 md:grid-cols-6 items-start justify-center gap-4 border rounded-xl p-2">
                        <x-srj-select
                            wire:model.live="order_items.{{ $index }}.property_id"
                            :label="__('Select Item')"
                            name="order_items[{{ $index }}][property_id]"
                            class="mb-2 select-css"
                            :placeholder="__('Select some Item...')"
                            :async-data="route('property.index')"
                            option-label="fullTitle"
                            option-value="id"
                            required
                        />
                        @if(isset($item['property_id']))
                            <x-srj-select
                                wire:model.live="order_items.{{ $index }}.dimensions"
                                :label="__('Dimensions')"
                                name="order_items[{{ $index }}][dimensions]"
                                class="mb-2 select-css"
                                :placeholder="__('Select some Dimensions...')"
                                :async-data="route('property.dimensions', $item['property_id'])"
                                option-label="title"
                                option-value="id"
                            />
                            <label>{{ __("Quantity") }}
                            <div x-data="{ count: @entangle('order_items.' . $index . '.count') }" class="flex items-center gap-x-3">
                                <x-srj-button x-hold.click.repeat.200ms="count > 1 ? count-- : 1" icon="minus" />
                                <span class="bg-cyan-100 px-5 py-1.5 rounded-lg" x-text="count"></span>
                                <x-srj-button x-hold.click.repeat.200ms="count++" icon="plus" />
                            </div>
                            </label>
                        @endif
                        @if ($index > 0)
                            <x-srj-mini-button rounded negative icon="trash" wire:click.prevent="removeItem({{ $index }})"/>
                        @endif
                        </div>
                    @endforeach
                    </div>
                    <div class="col-3 py-4">
{{--                        <button class="btn btn-warning form-control" wire:click.prevent="addItem">+ Add More</button>--}}
                        <x-srj-button icon="plus" wire:click.prevent="addItem" :label="__('Add Item')" class="bg-gradient-cyan"/>
                    </div>
                    <div>
                        <x-srj-select
                            wire:model.live="washing_type"
                            :label="__('Washing type')"
                            placeholder="Select many statuses"
                            class="mb-2 md:w-1/4 select-css"
                            multiselect
                            :options="['آبشور', 'اعلاء‌شوئی', 'براق‌شویی', 'رنگ‌برداری', 'رفوگری', 'پرداخت', 'کاور']"
                        />
                    </div>
                    <div class="mt-auto mb-0 font-semibold leading-normal text-sm group text-slate-500 flex justify-between flex-row">
                        <x-srj-button :label="__('Submit')" icon="rocket-launch" wire:click="submit" class="bg-gradient-fuchsia"/>
                    </div>
                </div>
            </div>
    </div>
</div>
