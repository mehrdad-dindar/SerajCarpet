<div
    class="max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative">
    <div class="p-4">
        <div class="flex justify-start items-center">
            <x-srj-avatar xl icon="user" border="thick" class="me-2 bg-gradient-fuchsia"/>
            <div class="flex flex-col gap-2">
                <span class="font-semibold text-lg text-gray-900 dark:text-white">{{$order->customer->name}}</span>
                <span class="flex justify-between gap-4">
                    <a href="tel:+98{{intval($order->customer->phone)}}">{{$order->customer->phone}}</a>
                    <a href="tel:+98{{intval($order->customer->phone2)}}">{{$order->customer?->phone2}}</a>
                </span>
            </div>
        </div>
        <hr class="h-[1px] bg-gray-600">
        @if($order->address->municipality_zone)
        <b>{!! 'منطقه '.$order->address->municipality_zone . ' - محله '.$order->address->neighbourhood !!}</b>
        @endif
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
            <x-phosphor.icons::duotone.map-pin-area class="w-5 h-5 ml-1" />
            {{$order->address->getFullAddress()}}</p>
        @if($order->address->description)
            <p class="border p-2 rounded-lg mb-3 font-normal text-gray-700 dark:text-gray-400">
                <x-phosphor.icons::duotone.chat-circle-dots class="w-5 h-5 ml-1" />
                {{$order->address->description}}</p>
        @endif
        @if(!is_null($comment))
            <p class="border p-2 rounded-lg mb-3 font-normal text-gray-700 dark:text-gray-400">
                <x-phosphor.icons::duotone.chats-circle class="w-5 h-5 ml-1" />
                {{$comment->body}}</p>
        @endif
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2">
            {{--            <x-srj-button wire:click="updateInvoice({{$order}})" spinner="updateInvoice" label="ثبت فاکتور" class="bg-gradient-fuchsia sm:col-span-full">--}}
            {{--                <x-slot name="prepend">--}}
            {{--                    <x-phosphor.icons::duotone.invoice class="w-5 h-5" />--}}
            {{--                </x-slot>--}}
            {{--            </x-srj-button>--}}
            <x-srj-button wire:click="customerCall({{$order->customer->phone}})" spinner="customerCall" label="تماس"
                          rounded-lg light green hover:outline.green focus:solid.green>
                <x-slot name="prepend">
                    <x-phosphor.icons::regular.phone-call class="w-5 h-5"/>
                </x-slot>
            </x-srj-button>
            <x-srj-button wire:click="direction({{$order->address}})" spinner="direction" rounded-lg light primary
                          hover:outline.primary focus:solid.primary label="مسیریابی">
                <x-slot name="prepend">
                    <x-phosphor.icons::regular.path class="w-5 h-5"/>
                </x-slot>
            </x-srj-button>
            <x-srj-dropdown height="4xl" class="srj-dropdown">
                <x-slot name="trigger" class="relative">
                    <x-srj-button rounded-lg outline primary hover:outline.info focus:solid.info label="عملیات" class="w-full"/>
                    @if($order->comments->count())
                    <span class="absolute top-0 left-0 -mt-1 -mr-1">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-warning-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-warning-500"></span>
                        </span>
                    </span>
                    @endif
                </x-slot>

                <x-srj-dropdown.item wire:click="carpetsReceived()" icon="check" label="تحویل گرفته شد"/>
                <x-srj-dropdown.item wire:click="revisitingDriver()" separator icon="arrow-path" label="مراجعه مجدد راننده"/>
                <x-srj-dropdown.item wire:click="deliveredAndPaid()" separator icon="clipboard-document-check" label="تحویل و تسویه"/>
                <x-srj-dropdown.item separator class="relative">
                    <x-srj-icon name="clipboard-document-check" class="w-5 h-5 mr-2" />
                    <span x-on:click="$openModal('orderDescription-{{$order->id}}')">توضیحات سفارش</span>
                    @if($order->comments->count())
                        <x-srj-mini-badge negative rounded :label="$order->comments->count()" class="absolute top-0 left-0"/>
                    @endif
                </x-srj-dropdown.item>
                <x-srj-dropdown.item primery separator icon="x-circle" x-on:click="$openModal('cancel-{{$order->id}}')" rounded-lg outline negative hover:outline.negative focus:solid.negative label="کنسل"/>
            </x-srj-dropdown>
            <x-srj-modal name="orderDescription-{{$order->id}}" blur="md" align="items-center" width="xl">
                <x-srj-card title="توضیحات سفارش">
                    <x-slot name="header" class="border-secondary-200 dark:border-secondary-600 px-4 py-2.5 flex justify-between items-center rounded-t-md border-b">
                            <span class="font-medium text-base whitespace-normal text-secondary-700 dark:text-secondary-400">توضیحات سفارش</span>
                        <x-srj-button positive label="افزودن" icon="plus" wire:click="toggleForm()"/>
                    </x-slot>
                    @if($showForm)
                        <div class="animate-fade-in">
                            @livewire('comment.create', ['order' => $order])
                        </div>
                    @endif
                    <div wire:ignore>
                        {{--@livewire('order-comments', ['record' => $order])--}}
                        <livewire:order-comments :record="$order" :key="'ss'.now()->timestamp"/>
                    </div>
                    <x-slot name="footer" class="flex justify-end gap-x-4">
                        <x-srj-button outline red label="بستن !" x-on:click="close" />
                    </x-slot>
                </x-srj-card>
            </x-srj-modal>
        </div>
    </div>
    <x-srj-badge :label="$order->getStatusLabel()" :class="$order->getStatusColor() . ' absolute top-1 end-1'"
                 icon="tag"/>
    <x-srj-modal-card
        title="کنسل کردن سفارش"
        name="cancel-{{$order->id}}"
        z-index="z-30"
        align="items-center">
        <div class="grid grid-cols-1">
            <x-srj-alert title="توجه !" warning padding="medium" class="mb-4">
                <x-slot name="slot">
                    شما در حال <strong>کنسل کردن</strong> سفارش ( <strong>{{$order->customer->name}}</strong> ) هستید !
                    <br>
                    لطفا به صورت خلاصه علت کنسل شدن را بیان کنید.
                </x-slot>
            </x-srj-alert>
            <x-srj-textarea
                wire:model.defer="reason"
                required
                label="توضیحات"
                placeholder="علت کنسلی ..."/>
        </div>
        <x-slot name="footer" class="flex justify-between gap-x-4">
            <div class="flex gap-x-4">
                <x-srj-button negative label="کنسل شود" wire:click="cancel"/>

                <x-srj-button outline primary label="بیخیال" x-on:click="close"/>
            </div>
        </x-slot>
    </x-srj-modal-card>
</div>
