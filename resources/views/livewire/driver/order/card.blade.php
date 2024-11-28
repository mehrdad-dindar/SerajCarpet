<div
    class="max-w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 relative">
    <a href="{{sprintf('https://nshn.ir/?lat=%s&lng=%s',$order->address->latitude,$order->address->longitude)}}">
        <img class="rounded-t-lg object-cover w-full max-h-60" src="{{$mapUrl}}" alt=""/>
    </a>
    <div class="p-4">
        <a href="tel:+98{{intval($order->customer->phone)}}" class="flex justify-start items-center">
        <x-srj-avatar xl icon="user" border="thick" class="me-2 bg-gradient-fuchsia"/>
            <div class="flex flex-col gap-2">
                <span class="font-semibold text-lg text-gray-900 dark:text-white">{{$order->customer->name}}</span>
                <span>{{$order->customer->phone}}</span>
            </div>
        </a>
        <hr class="h-[1px] bg-gray-600">

        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{$order->address->getFullAddress()}}</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2">
            <x-srj-button wire:click="updateInvoice({{$order}})" spinner="updateInvoice" label="ثبت فاکتور" class="bg-gradient-fuchsia sm:col-span-full">
                <x-slot name="prepend">
                    <x-phosphor.icons::duotone.invoice class="w-5 h-5" />
                </x-slot>
            </x-srj-button>
            <x-srj-button wire:click="customerCall({{$order->customer->phone}})" spinner="customerCall" label="تماس" rounded-lg light green hover:outline.green focus:solid.green>
                <x-slot name="prepend">
                    <x-phosphor.icons::regular.phone-call class="w-5 h-5" />
                </x-slot>
            </x-srj-button>
            <x-srj-button wire:click="direction({{$order->address}})" spinner="direction" rounded-lg light primary hover:outline.primary focus:solid.primary label="مسیریابی">
                <x-slot name="prepend">
                    <x-phosphor.icons::regular.path class="w-5 h-5" />
                </x-slot>
            </x-srj-button>
            <x-srj-button x-on:click="$openModal('cancel-{{$order->id}}')" rounded-lg outline negative hover:outline.negative focus:solid.negative label="کنسل">
                <x-slot name="prepend">
                    <x-phosphor.icons::regular.x-circle class="w-5 h-5" />
                </x-slot>
            </x-srj-button>
        </div>
    </div>
    <x-srj-badge :label="$order->getStatusLabel()" :class="$order->getStatusColor() . ' absolute top-1 start-1'"
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
                <x-srj-button negative label="کنسل شود" wire:click="cancel" />

                <x-srj-button outline primary label="بیخیال" x-on:click="close" />
            </div>
        </x-slot>
    </x-srj-modal-card>
</div>
