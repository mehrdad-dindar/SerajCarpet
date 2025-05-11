<div class="w-full p-6 mx-auto">

    <div class="flex flex-wrap -mx-3">
        <div class="w-full max-w-full px-3 mt-6 md:w-7/12 md:flex-none">
            <div
                class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                    <h6 class="mb-0">{{ __("Order Items") }}</h6>
                </div>
                <div class="flex-auto p-4 pt-6">
                    @foreach($allItems as $item)
                        <x-order-item
                            :title="$item->property->full_title ?? $item->title"
                            :dimensions="$item->dimensions"
                            :unit="($item->property->unit ?? $item->unit) ?? 'meter'"
                            :quantity="$item->quantity"
                            :price="$item->sub_total"
                            :item="$item"
                        />
                    @endforeach
                </div>
            </div>
        </div>
        <div class="w-full max-w-full px-3 mt-6 md:w-5/12 md:flex-none">
            <div
                class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                    <div class="flex flex-wrap -mx-3">
                        <div class="max-w-full px-3 md:w-1/2 md:flex-none">
                            <h6 class="mb-0">{{ __("Order Summary") }}</h6>
                        </div>
                        <div class="flex items-center justify-end max-w-full px-3 md:w-1/2 md:flex-none">
                            <i class="me-2 far fa-calendar-alt"></i>
                            <small>{{ $order->created_at }}</small>
                        </div>
                    </div>
                </div>
                <div class="flex-auto p-4 pt-6">
                    <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                        {{--<li
                            class="relative flex justify-between px-4 py-2 pl-0 mb-2 bg-white border-0 rounded-t-inherit text-size-inherit rounded-xl">
                            <div class="flex items-center">
                                <button
                                    class="leading-pro ease-soft-in text-size-xs bg-150 w-6.35 h-6.35 p-1.2 rounded-3.5xl tracking-tight-soft bg-x-25 mr-4 mb-0 flex cursor-pointer items-center justify-center border border-solid border-lime-500 border-transparent bg-transparent text-center align-middle font-bold uppercase text-lime-500 transition-all hover:opacity-75"><i
                                        class="fas fa-arrow-up text-size-3xs"></i></button>
                                <div class="flex flex-col">
                                    <h6 class="mb-1 leading-normal text-size-sm text-slate-700">Stripe</h6>
                                    <span class="leading-tight text-size-xs">26 March 2020, at 13:45 PM</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <p
                                    class="relative z-10 inline-block m-0 font-semibold leading-normal text-transparent bg-gradient-lime text-size-sm bg-clip-text">
                                    + $ 750</p>
                            </div>
                        </li>
                        <li
                            class="relative flex justify-between px-4 py-2 pl-0 mb-2 bg-white border-0 border-t-0 text-size-inherit rounded-xl">
                            <div class="flex items-center">
                                <button
                                    class="leading-pro ease-soft-in text-size-xs bg-150 w-6.35 h-6.35 p-1.2 rounded-3.5xl tracking-tight-soft bg-x-25 mr-4 mb-0 flex cursor-pointer items-center justify-center border border-solid border-lime-500 border-transparent bg-transparent text-center align-middle font-bold uppercase text-lime-500 transition-all hover:opacity-75"><i
                                        class="fas fa-arrow-up text-size-3xs"></i></button>
                                <div class="flex flex-col">
                                    <h6 class="mb-1 leading-normal text-size-sm text-slate-700">HubSpot</h6>
                                    <span class="leading-tight text-size-xs">26 March 2020, at 12:30 PM</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <p
                                    class="relative z-10 inline-block m-0 font-semibold leading-normal text-transparent bg-gradient-lime text-size-sm bg-clip-text">
                                    + $ 1,000</p>
                            </div>
                        </li>
                        <li
                            class="relative flex justify-between px-4 py-2 pl-0 mb-2 bg-white border-0 border-t-0 text-size-inherit rounded-xl">
                            <div class="flex items-center">
                                <button
                                    class="leading-pro ease-soft-in text-size-xs bg-150 w-6.35 h-6.35 p-1.2 rounded-3.5xl tracking-tight-soft bg-x-25 mr-4 mb-0 flex cursor-pointer items-center justify-center border border-solid border-lime-500 border-transparent bg-transparent text-center align-middle font-bold uppercase text-lime-500 transition-all hover:opacity-75"><i
                                        class="fas fa-arrow-up text-size-3xs"></i></button>
                                <div class="flex flex-col">
                                    <h6 class="mb-1 leading-normal text-size-sm text-slate-700">Creative Tim</h6>
                                    <span class="leading-tight text-size-xs">26 March 2020, at 08:30 AM</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <p
                                    class="relative z-10 items-center inline-block m-0 font-semibold leading-normal text-transparent bg-gradient-lime text-size-sm bg-clip-text">
                                    + $ 2,500</p>
                            </div>
                        </li>--}}
                        <li
                            class="relative flex justify-between px-4 py-2 pl-0 mb-2 bg-white border-0 border-t-0 rounded-b-inherit text-size-inherit rounded-xl">
                            <div class="flex items-center">
                                <button
                                    class="leading-pro ease-soft-in text-size-xs bg-150 w-6.35 h-6.35 p-1.2 rounded-3.5xl tracking-tight-soft bg-x-25 me-4 mb-0 flex cursor-pointer items-center justify-center border border-solid border-slate-700 border-transparent bg-transparent text-center align-middle font-bold uppercase text-slate-700 transition-all hover:opacity-75 {{$order->getStatusColor()}}">
                                    <i
                                        class="fas fa-exclamation text-size-3xs"></i></button>
                                <div class="flex flex-col">
                                    <h6 class="mb-1 leading-normal text-size-sm text-slate-700">{{ __("Status") }}</h6>
                                    <span class="leading-tight text-size-xs">در {{ $order->updated_at }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <p class="flex items-center m-0 font-semibold leading-normal text-size-sm text-slate-700">
                                    <x-srj-badge :label="$order->getStatusLabel()" :class="$order->getStatusColor()"/>
                                </p>
                            </div>
                        </li>
                    </ul>
                    <h6 class="mb-4 text-center leading-tight uppercase text-size-xs text-slate-500">--------------------------------</h6>
                    <ul class="flex flex-col ps-0 mb-0 rounded-lg">
                        <li
                            class="relative flex justify-between px-4 py-2 ps-0 mb-2 bg-white border-0 border-t-0 rounded-b-inherit text-size-inherit rounded-xl">
                            <div class="flex items-center">
                                <button
                                    class="leading-pro ease-soft-in text-size-xs bg-150 w-6.35 h-6.35 p-1.2 rounded-3.5xl tracking-tight-soft bg-x-25 mr-4 mb-0 flex cursor-pointer items-center justify-center border border-solid border-lime-500 border-transparent bg-transparent text-center align-middle font-bold uppercase text-lime-500 transition-all hover:opacity-75">
                                    <x-srj-icon name="plus" class="text-size-3xs"/>
                                </button>
                                <div class="flex flex-col">
                                    <h6 class="mb-1 leading-normal text-size-sm text-slate-700">{{ __("Order Total") }}</h6>
                                    <span class="leading-tight text-size-xs">@php
                                            $uniqueItems = $order->items
                                                ->pluck('property.serviceItem.name')
                                                ->unique()
                                                ->join(' - ');
                                        @endphp

                                        {{ $order->items->count()  . " مورد " . $uniqueItems }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <p class="relative z-10 inline-block m-0 font-semibold leading-normal text-transparent bg-gradient-lime text-size-sm bg-clip-text">{{ number_format($order->total) }}
                                    تومان</p>
                            </div>
                        </li>
                    </ul>
                    <x-srj-button
                        href="{{route('customer.panel.invoice.show', ['invoice' => $order->invoice->id])}}"
                        full
                        info
                        spinner
                        rounded="sm"
                        icon="banknotes"
                        :label="__('Bill payment')"/>
                </div>
            </div>
        </div>
    </div>
</div>
