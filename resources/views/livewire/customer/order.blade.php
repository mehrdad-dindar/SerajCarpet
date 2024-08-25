<div class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
        <h6>{{ __("Orders List") }}</h6>
    </div>
    <div class="flex-auto px-0 pt-0 pb-2">
        <div class="p-0 overflow-x-auto">
            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                <thead class="align-bottom">
                <tr>
                    <th class="px-6 py-3 font-bold text-right uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">{{ __("Order Id") }}</th>
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
                            <a href="{{ route("customer.panel.orders.show", $order) }}" class="font-semibold leading-tight text-xs text-slate-400"> <x-srj-icon name="eye" /> {{ __("View") }} </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $orders->links("vendor/livewire/tailwind") }}
        </div>
    </div>
</div>
