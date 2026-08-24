<div class="space-y-6 pb-12" dir="rtl">

    {{-- هدر خوش‌آمدگویی و اکشن رزرو سریع --}}
    <div class="bg-gradient-to-l from-amber-600 via-amber-500 to-yellow-500 rounded-3xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-right">
            <h1 class="text-2xl md:text-3xl font-extrabold">
                سلام، {{ $customer->name ?? 'مشتری گرامی' }} 👋
            </h1>
            <p class="text-amber-100 text-sm md:text-base">
                به سامانه هوشمند خدمات تخصصی قالیشویی سراج خوش آمدید.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.panel.orders') }}" class="px-5 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-2xl font-bold text-sm transition">
                سوابق سفارش‌ها
            </a>
            <a href="{{ url('/panel/orders/new') }}" class="px-6 py-3 bg-white text-amber-700 hover:bg-amber-50 rounded-2xl font-extrabold text-sm shadow-lg hover:shadow-xl transition transform active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                ثبت سفارش آنلاین جدید
            </a>
        </div>
    </div>

    {{-- کارت وضعیت سفارش جاری (Live Tracking Stepper) --}}
    @if($activeOrder)
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-gray-100 dark:border-gray-700 gap-4">
                <div class="flex items-center gap-3">
                    <span class="p-3 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-2xl">
                        <svg class="w-6 h-6 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </span>
                    <div>
                        <div class="text-xs text-gray-400">وضعیت سفارش جاری</div>
                        <div class="text-lg font-bold text-gray-900 dark:text-white">
                            سفارش شماره #{{ $activeOrder->id }}
                            <span class="mr-2 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                {{ $activeOrder->status?->label }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('customer.panel.order.show', $activeOrder) }}" class="text-sm font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1">
                        جزئیات کامل سفارش
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="mt-6">
                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-2 font-medium">
                    <span>دریافت و جمع‌آوری</span>
                    <span>شستشو و اعلاشویی در کارخانه</span>
                    <span>تحویل و تسویه</span>
                </div>
                <div class="w-full h-3 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-500 to-emerald-500 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div>

            {{-- مشخصات راننده یا نوبت --}}
            @if($activeOrder->time_apply_status)
                <div class="mt-6 p-4 rounded-2xl bg-amber-50/60 dark:bg-gray-700/40 border border-amber-100/80 dark:border-gray-600/50 flex items-center justify-between text-xs md:text-sm text-gray-700 dark:text-gray-300">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>زمان‌بندی مراجعه راننده: <strong>{{ verta($activeOrder->time_apply_status)->format('l d F Y - H:i') }}</strong></span>
                    </div>
                    @if($activeOrder->driver)
                        <div class="font-bold text-gray-900 dark:text-white">
                            راننده مسئول: {{ $activeOrder->driver->name }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- کارت‌های آمار و میان‌برها --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- کل سفارش‌ها --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-400 font-medium">کل سفارش‌های ثبت‌شده</div>
                <div class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $totalOrdersCount }} <span class="text-xs font-normal text-gray-400">سفارش</span></div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>

        {{-- صورتحساب‌های معلق --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-400 font-medium">فاکتورهای منتظر پرداخت</div>
                <div class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ $pendingInvoicesCount }} <span class="text-xs font-normal text-gray-400">فاکتور</span></div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        {{-- آدرس‌های ثبت‌شده --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-xs text-gray-400 font-medium">دفترچه آدرس‌ها</div>
                <div class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $customer->addresses()->count() }} <span class="text-xs font-normal text-gray-400">موقعیت</span></div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
    </div>
</div>
