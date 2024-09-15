<div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
    @include('livewire.driver.order.navigation')
    <div class="flex-auto p-4">
        <div class="px-3">
            <div class="flex flex-col h-full">
                <h5 class="font-bold mb-4">{{__("Order Summary")}}</h5>
                <div class="flex flex-wrap justify-between mb-4 -mx-3">
                    <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4 relative">
                        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                            <div class="flex-auto p-4">
                                <div class="flex flex-row -mx-3">
                                    <div class="px-3 text-right basis-1/3">
                                        <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-fuchsia">
                                            <x-srj-icon name="user" class="text-size-lg relative top-3.5 text-white"/>
                                        </div>
                                    </div>
                                    <div class="flex-none w-2/3 max-w-full px-3">
                                        <div>
                                            <span class="mb-0 block font-semibold leading-normal">
                                                {{ $customer->name ?? "مشتری بی نام" }}
                                            </span>
                                            <span class="mb-2 block text-size-sm">
                                                {{ $customer->phone }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-srj-badge flat primary label="+{{ verta($customer->created_at)->formatDifference() }}" class="absolute top-0 left-0 text-lime-300">
                            <x-slot name="append" class="relative flex items-center w-2 h-2">
                                <span class="absolute inline-flex w-full h-full rounded-full opacity-75 bg-cyan-500 animate-ping"></span>
                                <span class="relative inline-flex w-2 h-2 rounded-full bg-cyan-500"></span>
                            </x-slot>
                        </x-srj-badge>
                    </div>
                    <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                        <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                            <div class="flex-auto p-4">
                                <div class="flex flex-row -mx-3">
                                    <div class="px-3 text-right basis-1/3">
                                        <div class="inline-block w-12 h-12 text-center rounded-lg bg-gradient-fuchsia">
                                            <x-srj-icon name="truck" class="text-size-lg relative top-3.5 text-white"/>
                                        </div>
                                    </div>
                                    <div class="flex-none w-2/3 max-w-full px-3">
                                        <div>
                                            <span class="mb-0 block font-semibold leading-normal">
                                                {{ auth('driver')->user()->name }}
                                            </span>
                                            <span class="mb-2 block text-size-sm">
                                                {{ auth('driver')->user()->phone }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($washing_type)
                <div class="mb-4">
                    @foreach($washing_type as $type)
                        <x-srj-badge icon-size="md" lg icon="check" lime label="Lime" :label="$type"/>
                    @endforeach
                </div>
                @endif
                <div class="relative flex flex-col w-full min-w-0 mb-4 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6>{{ __("Order Items") }}</h6>
                    </div>
                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0 overflow-x-auto">
                            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                <thead class="align-bottom">
                                <tr>
                                    <th class="px-6 py-3 font-bold align-middle text-start bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">{{ __("Name") }}</th>
                                    <th class="px-6 py-3 font-bold align-middle text-start bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">{{ __("Dimensions") }}</th>
                                    <th class="px-6 py-3 font-bold align-middle text-start bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">{{ __("Quantity") }}</th>
                                    <th class="px-6 py-3 font-bold align-middle text-start bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">{{ __("Unit Price") }}</th>
                                    <th class="px-6 py-3 font-bold align-middle text-start bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">{{ __("Sub Total Price") }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orderItems as $item)
                                    <tr>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex px-2 py-1">
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 leading-normal text-sm">{{ $item->fullTitle }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        @if(!is_null($details[$item->id]['dimensions']))
                                        <p class="mb-0 font-semibold leading-tight text-sm">
                                            {{ $details[$item->id]['dimensions'] }}
                                        </p>
                                        <p class="mb-0 leading-tight text-sm text-slate-400">
                                            {{ __($item->unit) }}
                                        </p>
                                        @endif
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="mb-0 font-semibold leading-tight text-sm text-slate-400">
                                            {{ $details[$item->id]['quantity'] }}
                                        </span>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="font-semibold leading-tight text-sm text-slate-400">
                                            {{ number_format($item->price) }} تومان
                                        </span>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="font-semibold leading-tight text-sm text-slate-400">
                                            {{ number_format($item->price * ($details[$item->id]['dimensions'] ?? 1) * $details[$item->id]['quantity']) }} تومان
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <div class="flex px-2 py-1">
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 leading-normal font-bold text-sm">{{ __("Order Total") }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                        <span class="font-bold leading-tight text-sm text-slate-400">
                                            {{ number_format($totalPrice) }} تومان
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mt-auto mb-0 font-semibold leading-normal text-sm group text-slate-500 flex justify-between flex-row">
                    <x-srj-button :label="__('Submit Order')" icon="rocket-launch" wire:click="submit" info/>
                </div>
            </div>
        </div>
    </div>
</div>
