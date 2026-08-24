<div class="max-w-4xl mx-auto space-y-8" dir="rtl">

    {{-- نوار مراحل (Stepper Header) --}}
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between relative">
            @php
                $steps = [
                    1 => ['title' => 'نوع و تعداد فرش‌ها', 'icon' => 'heroicon-o-sparkles'],
                    2 => ['title' => 'انتخاب آدرس', 'icon' => 'heroicon-o-map-pin'],
                    3 => ['title' => 'زمان جمع‌آوری', 'icon' => 'heroicon-o-calendar-days'],
                    4 => ['title' => 'تأیید و ثبت نهایی', 'icon' => 'heroicon-o-check-badge'],
                ];
            @endphp

            @foreach($steps as $num => $step)
                <div class="flex flex-col items-center relative z-10">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-sm transition-all duration-300 {{ $currentStep >= $num ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'bg-gray-100 dark:bg-gray-700 text-gray-400' }}">
                        {{ $num }}
                    </div>
                    <span class="text-xs font-bold mt-2 text-center {{ $currentStep >= $num ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">
                        {{ $step['title'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- محتوای مرحله ۱: انتخاب اقلام --}}
    @if($currentStep === 1)
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700 space-y-6 animate-fade-in">
            <div class="flex items-center justify-between border-b pb-4 dark:border-gray-700">
                <div>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">اقلام مورد نظر برای شستشو</h3>
                    <p class="text-xs text-gray-400 mt-1">نوع خدمات، متراژ تقریبی و تعداد تخته‌ها را مشخص فرمایید.</p>
                </div>
                <button wire:click="addItem" type="button" class="px-4 py-2 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl text-xs font-bold hover:bg-amber-100 transition flex items-center gap-1">
                    + افزودن فرش دیگر
                </button>
            </div>

            <div class="space-y-4">
                @foreach($orderItems as $index => $item)
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200/70 dark:border-gray-600 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                        {{-- انتخاب خدمت و ویژگی --}}
                        <div class="md:col-span-6">
                            <label class="text-xs font-bold text-gray-600 dark:text-gray-300 block mb-1">نوع فرش یا شستشو</label>
                            <select wire:model.live="orderItems.{{ $index }}.property_id" class="w-full bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-amber-500">
                                @foreach($availableProperties as $prop)
                                    <option value="{{ $prop->id }}">
                                        {{ $prop->fullTitle }} (هر {{ __($prop->unit) }} {{ number_format($prop->price) }} تومان)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- متراژ یا ابعاد --}}
                        @php
                            $currentProp = $availableProperties->firstWhere('id', $item['property_id']);
                        @endphp
                        <div class="md:col-span-3">
                            <label class="text-xs font-bold text-gray-600 dark:text-gray-300 block mb-1">متراژ / اندازه</label>
                            @if(!empty($currentProp?->dimensions))
                                <select wire:model.live="orderItems.{{ $index }}.dimensions" class="w-full bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600 rounded-xl text-sm">
                                    @foreach($currentProp->dimensions as $dim)
                                        <option value="{{ $dim }}">{{ $dim }} متری</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="number" wire:model.live="orderItems.{{ $index }}.dimensions" min="1" class="w-full bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600 rounded-xl text-sm" placeholder="متراژ">
                            @endif
                        </div>

                        {{-- تعداد --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-gray-600 dark:text-gray-300 block mb-1">تعداد (تخته)</label>
                            <input type="number" wire:model.live="orderItems.{{ $index }}.quantity" min="1" class="w-full bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600 rounded-xl text-sm">
                        </div>

                        {{-- حذف --}}
                        <div class="md:col-span-1 flex justify-center md:pt-5">
                            <button wire:click="removeItem({{ $index }})" type="button" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- خدمات جانبی و کاور --}}
            <div class="pt-4 border-t dark:border-gray-700">
                <label class="text-xs font-bold text-gray-600 dark:text-gray-300 block mb-2">خدمات ویژه و تکمیلی</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($availableOptions as $opt)
                        <label class="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-700/40 rounded-xl border border-gray-200/60 dark:border-gray-600 text-xs font-medium cursor-pointer">
                            <input type="checkbox" wire:model="selectedOptions" value="{{ $opt->id }}" class="rounded text-amber-500 focus:ring-amber-500">
                            <span class="text-gray-700 dark:text-gray-300">{{ $opt->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- محتوای مرحله ۲: انتخاب آدرس --}}
    @if($currentStep === 2)
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700 space-y-6 animate-fade-in">
            <div class="flex items-center justify-between border-b pb-4 dark:border-gray-700">
                <div>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">انتخاب آدرس تحویل و جمع‌آوری</h3>
                    <p class="text-xs text-gray-400 mt-1">فرش‌ها از کدام آدرس تحویل گرفته شوند؟</p>
                </div>
                <a href="{{ route('customer.panel.addresses') }}" target="_blank" class="text-xs font-bold text-amber-600 hover:underline">
                    + مدیریت / افزودن آدرس جدید
                </a>
            </div>

            @if($addresses->isEmpty())
                <div class="p-6 text-center bg-amber-50 dark:bg-gray-700/40 rounded-2xl border border-amber-100">
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">هنوز آدرسی در حساب کاربری شما ثبت نشده است.</p>
                    <a href="{{ route('customer.panel.addresses') }}" class="px-5 py-2.5 bg-amber-500 text-white rounded-xl text-xs font-bold">
                        ثبت اولین آدرس روی نقشه
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($addresses as $addr)
                        <label class="p-5 rounded-2xl border-2 transition cursor-pointer flex flex-col justify-between {{ $selectedAddressId === $addr->id ? 'border-amber-500 bg-amber-50/40 dark:bg-gray-700/60 shadow-md' : 'border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-2">
                                    <input type="radio" wire:model="selectedAddressId" value="{{ $addr->id }}" class="text-amber-500 focus:ring-amber-500">
                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200">
                                        منطقه {{ $addr->municipality_zone ?? '-' }} ({{ $addr->neighbourhood ?? 'تهران' }})
                                    </span>
                                </div>
                                @if($addr->is_active)
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-extrabold rounded-full">پیش‌فرض</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-3 leading-relaxed">
                                {{ $addr->getFullAddress() }}
                            </p>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- محتوای مرحله ۳: انتخاب زمان مراجعه راننده --}}
    @if($currentStep === 3)
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700 space-y-6 animate-fade-in">
            <div>
                <h3 class="text-lg font-black text-gray-900 dark:text-white">تعیین زمان و شیفت مراجعه راننده</h3>
                <p class="text-xs text-gray-400 mt-1">زمان مناسب جهت مراجعه خودروی قالیشویی سراج را انتخاب کنید.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- انتخاب تاریخ --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300 block mb-2">روز مراجعه</label>
                    <select wire:model.live="reservationDate" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-sm font-medium">
                        @foreach($availableDates as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- انتخاب شیفت --}}
                <div>
                    <label class="text-xs font-bold text-gray-700 dark:text-gray-300 block mb-2">بازه ساعت / شیفت</label>
                    <select wire:model="reservationTime" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-sm font-medium">
                        @foreach($availableShifts as $timeVal => $shiftLabel)
                            <option value="{{ $timeVal }}">{{ $shiftLabel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- توضیحات یا یادداشت برای راننده --}}
            <div>
                <label class="text-xs font-bold text-gray-700 dark:text-gray-300 block mb-2">توضیحات تکمیلی برای راننده (اختیاری)</label>
                <textarea wire:model="comment" rows="3" class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-sm" placeholder="مثال: زنگ واحد سمت راست خراب است، لطفاً تماس بگیرید..."></textarea>
            </div>
        </div>
    @endif

    {{-- محتوای مرحله ۴: خلاصه و تأیید نهایی --}}
    @if($currentStep === 4)
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-700 space-y-6 animate-fade-in">
            <h3 class="text-lg font-black text-gray-900 dark:text-white border-b pb-4 dark:border-gray-700">پیش‌فاکتور و تأیید نهایی سفارش</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/40 space-y-2">
                    <span class="text-gray-400 font-medium">زمان مراجعه راننده:</span>
                    <div class="font-bold text-gray-800 dark:text-gray-200 text-sm">
                        {{ $availableDates[$reservationDate] ?? $reservationDate }} (ساعت {{ $reservationTime }})
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/40 space-y-2">
                    <span class="text-gray-400 font-medium">آدرس تحویل:</span>
                    <div class="font-bold text-gray-800 dark:text-gray-200">
                        {{ $addresses->firstWhere('id', $selectedAddressId)?->getFullAddress() }}
                    </div>
                </div>
            </div>

            {{-- جدول ریز اقلام --}}
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-right">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500">
                    <tr>
                        <th class="p-3 rounded-r-xl">شرح فرش / خدمت</th>
                        <th class="p-3 text-center">اندازه</th>
                        <th class="p-3 text-center">تعداد</th>
                        <th class="p-3 text-left rounded-l-xl">مبلغ برآوردی (تومان)</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($orderItems as $item)
                        @php $p = $availableProperties->firstWhere('id', $item['property_id']); @endphp
                        <tr>
                            <td class="p-3 font-bold text-gray-800 dark:text-gray-200">{{ $p?->fullTitle }}</td>
                            <td class="p-3 text-center">{{ $item['dimensions'] }} متری</td>
                            <td class="p-3 text-center font-bold">{{ $item['quantity'] }} تخته</td>
                            <td class="p-3 text-left font-mono font-bold">{{ number_format(((int)$item['dimensions']) * ((int)$item['quantity']) * ($p?->price ?? 0)) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-5 rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 flex items-center justify-between">
                <span class="text-sm font-bold text-amber-900 dark:text-amber-200">مجموع برآورد اولیه هزینه شستشو:</span>
                <span class="text-xl font-black text-amber-600 dark:text-amber-400 font-mono">{{ number_format($estimatedTotal) }} تومان</span>
            </div>
            <p class="text-[11px] text-gray-400 leading-relaxed">* توجه: متراژ نهایی و هزینه‌های احتمالی رفوگری پس از کارشناسی حضوری توسط راننده در فاکتور نهایی لحاظ خواهد شد.</p>
        </div>
    @endif

    {{-- دکمه‌های ناوبری ویزارد --}}
    <div class="flex items-center justify-between pt-4">
        @if($currentStep > 1)
            <button wire:click="previousStep" type="button" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded-2xl text-sm transition">
                مرحله قبل
            </button>
        @else
            <div></div>
        @endif

        @if($currentStep < 4)
            <button wire:click="nextStep" type="button" class="px-8 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-extrabold rounded-2xl text-sm shadow-lg shadow-amber-500/30 transition transform active:scale-95 flex items-center gap-2">
                ادامه و مرحله بعد
                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
        @else
            <button wire:click="submitOrder" type="button" class="px-10 py-4 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-black rounded-2xl text-base shadow-xl shadow-green-500/30 transition transform active:scale-95 flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                تأیید نهایی و ثبت سفارش
            </button>
        @endif
    </div>
</div>
