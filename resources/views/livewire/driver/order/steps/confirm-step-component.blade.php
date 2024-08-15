<div>
    <div class="flex flex-wrap -mx-3">
        <!-- card1 -->
        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
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
                                <span>
                                    عضویت:
                                    <span class="leading-normal text-size-xs font-weight-bolder text-lime-500">
                                        +{{ verta($customer->created_at)->formatDifference() }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @dd($orderItems)
</div>
