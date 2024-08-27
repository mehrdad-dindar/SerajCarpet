<div class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl flex items-center justify-between">
        <h6>{{ __("Orders List") }}</h6>
        <x-srj-badge outline secondary :label="verta()->format('d F Y')" />
    </div>
    <div class="flex justify-center">
        <div class="relative w-full md:w-3/4">
        <ul class="relative flex flex-wrap p-1 list-none bg-transparent rounded-xl" nav-pills role="tablist">
            <li class="z-30 flex-auto text-center">
                <a class="z-30 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700" nav-link active href="javascript:;" role="tab" aria-selected="true">
                    <x-srj-ph-icon name="truck" class="w-4 h-4"/>
                    <span class="ml-1">{{ __("Collective Orders") }}</span>
                </a>
            </li>
            <li class="z-30 flex-auto text-center">
                <a class="z-30 block w-full px-0 py-1 mb-0 transition-all border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700" nav-link href="javascript:;" role="tab" aria-selected="false">
                    <x-srj-ph-icon name="truck" class="w-4 h-4" variant="duotone"/>
                    <span class="ml-1">{{ __("Delivery Orders") }}</span>
                </a>
            </li>
            <li class="z-30 flex-auto text-center">
                <a class="z-30 block w-full px-0 py-1 mb-0 transition-colors border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700" nav-link href="javascript:;" role="tab" aria-selected="false">
                    <x-srj-ph-icon name="arrow-bend-double-up-left" class="w-4 h-4"/>
                    <span class="ml-1">{{ __("Revisit Orders") }}</span>
                </a>
            </li>
        </ul>
    </div>
    </div>
    <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-0 overflow-x-auto">
            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                <thead class="align-bottom">
                <tr>
                    <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">{{ __("Order Id") }}</th>
                    <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">{{ __("Customer Name") }}</th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                        {{ __("Order Items") }}</th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                        {{ __("Status") }}</th>
                    <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                        {{ __("Created at") }}</th>
                    <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-gray-200 border-solid shadow-none tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                        <div class="flex flex-col justify-center">
                            <h6 class="mb-0 leading-normal text-sm">#{{ $order->id }}</h6>
                        </div>
                    </td>
                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                        <div class="flex flex-col justify-center">
                            <h6 class="mb-0 leading-normal text-sm">{{ $order->customer->name ?? "مشترک بی نام" }}</h6>
                            <p class="mb-0 leading-tight text-xs text-slate-400">{{ $order->customer->phone }}</p>
                        </div>
                    </td>
                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent text-center">
                        <p class="mb-0 font-semibold leading-tight text-xs">{{ $order->items()->count() }}</p>
                        <p class="mb-0 leading-tight text-xs text-slate-400">
                            @php
                                $uniqueItems = $order->items
                                    ->pluck('property.serviceItem.name')
                                    ->unique()
                                    ->join(' - ');
                            @endphp

                            {{ $uniqueItems }}
                        </p>
                    </td>
                    <td class="p-2 leading-normal text-center align-middle bg-transparent border-b text-sm whitespace-nowrap shadow-transparent">
                        <x-srj-badge :label="$order->getStatusLabel()" :class="$order->getStatusColor()"/>
                    </td>
                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                        <span class="font-semibold leading-tight text-xs text-slate-400">{{ $order->created_at }}</span>
                    </td>
                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                        <a href="javascript:;" class="font-semibold leading-tight text-xs text-slate-400"> Edit </a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            {{ $orders->links("vendor/livewire/tailwind") }}
        </div>
    </div>
</div>
