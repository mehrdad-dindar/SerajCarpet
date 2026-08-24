<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-2 mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
            <x-filament::icon icon="heroicon-o-clock" class="h-5 w-5 text-amber-500" />
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                {{ __('تاریخچه سیر و تغییرات سفارش') }}
            </h3>
        </div>

        @php
            $activities = $this->getActivities();
        @endphp

        @if($activities->isEmpty())
            <div class="py-6 text-center text-gray-500 dark:text-gray-400 text-xs">
                هنوز هیچ رویدادی برای این سفارش ثبت نشده است.
            </div>
        @else
            <div class="relative border-r-2 border-amber-500/30 dark:border-amber-500/20 mr-3 space-y-4">
                @foreach ($activities as $activity)
                    @php
                        $props = $activity->properties ?? collect();
                        $old = $props['old'] ?? [];
                        $new = $props['attributes'] ?? [];
                        $causer = $activity->causer?->name ?? 'سیستم';
                    @endphp

                    <div class="relative pr-5">
                        <!-- نشانگر نقطه تایم‌لاین -->
                        <span class="absolute -right-[7px] top-2 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-amber-500 ring-4 ring-white dark:ring-gray-900"></span>

                        <div class="bg-gray-50 dark:bg-gray-800/80 p-3.5 rounded-xl border border-gray-200/80 dark:border-gray-700 shadow-sm text-xs">
                            <!-- هدر لاگ -->
                            <div class="flex items-center justify-between gap-x-2 mb-2">
                                <span class="font-bold text-gray-900 dark:text-gray-100 text-sm">
                                    {{ $activity->description }}
                                </span>
                                <span class="text-gray-400 dark:text-gray-500 font-mono" dir="ltr">
                                    {{ verta($activity->created_at)->format('Y/m/d - H:i') }}
                                </span>
                            </div>

                            <!-- جزئیات تغییر وضعیت -->
                            @if(isset($new['status_id']) && (!isset($old['status_id']) || $new['status_id'] !== $old['status_id']))
                                <div class="flex items-center gap-x-2 my-2 py-1 px-2 rounded-lg bg-gray-100 dark:bg-gray-900/50">
                                    <span class="text-gray-500">وضعیت:</span>
                                    @if(isset($old['status_id']))
                                        <span class="px-2 py-0.5 rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            {{ \App\Models\OrderStatus::getLabelById($old['status_id']) }}
                                        </span>
                                        <span class="text-amber-500 font-bold">←</span>
                                    @endif
                                    <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-600 dark:text-amber-400 font-bold border border-amber-500/20">
                                        {{ \App\Models\OrderStatus::getLabelById($new['status_id']) }}
                                    </span>
                                </div>
                            @endif

                            <!-- جزئیات تخصیص راننده -->
                            @if(isset($new['driver_id']))
                                <div class="flex items-center gap-x-2 my-1.5 text-gray-600 dark:text-gray-300">
                                    <x-filament::icon icon="heroicon-m-truck" class="h-4 w-4 text-sky-500" />
                                    <span>راننده:</span>
                                    <strong class="text-sky-600 dark:text-sky-400">
                                        {{ \App\Models\Driver::getName($new['driver_id']) }}
                                    </strong>
                                    @if(isset($new['shift']))
                                        <span class="text-gray-400 text-[11px]">({{ $new['shift'] }})</span>
                                    @endif
                                </div>
                            @endif

                            <!-- فوتر لاگ: ثبت‌کننده و زمان نسبی -->
                            <div class="mt-2.5 pt-2 border-t border-gray-200/50 dark:border-gray-700/50 flex items-center justify-between text-gray-400 dark:text-gray-500 text-[11px]">
                                <span>توسط: <strong class="text-gray-700 dark:text-gray-300">{{ $causer }}</strong></span>
                                <span>{{ verta($activity->created_at)->formatDifference() }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
