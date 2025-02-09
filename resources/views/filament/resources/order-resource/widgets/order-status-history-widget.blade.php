<x-filament-widgets::widget>
    <x-filament::section>
        <h3 class="text-lg font-bold mb-4">{{ __("Order Status History") }}</h3>
        <ul>
            @foreach ($activities as $activity)
                @if(isset($activity->properties['attributes']))
                    <li class="py-2 border-b">
                        @if($activity->event !== "created")
                            @if(isset($activity->properties['old']['status_id']))
                                <div>
                                    <strong>{{ __("Status changed from:") }}</strong>
                                    <span class="outline-none inline-flex justify-center items-center group rounded-md text-amber-600 bg-amber-100 dark:bg-slate-700 gap-x-1 text-xs font-semibold px-2.5 py-0.5" dir="ltr">{{ \App\Models\OrderStatus::getLabel($activity->properties['old']['status_id'] ?? null) }} </span>
                                    <strong>{{ __("To:") }}</strong>
                                    <span class="outline-none inline-flex justify-center items-center group rounded-md text-sky-600 bg-sky-100 dark:bg-slate-700 gap-x-1 text-xs font-semibold px-2.5 py-0.5" dir="ltr">{{ \App\Models\OrderStatus::getLabel($activity->properties['attributes']['status_id']) }} </span>
                                </div>
                            @elseif(isset($activity->properties['attributes']['driver_id']))
                                @if(!isset($activity->properties['old']['driver_id']))
                                    <div>
                                        <h3>{{ __("Assign Driver") }}</h3>
                                        <span class="outline-none inline-flex justify-center items-center group rounded-md text-sky-600 bg-sky-100 dark:bg-slate-700 gap-x-1 text-xs font-semibold px-2.5 py-0.5" dir="ltr">{{  \App\Models\Driver::getName($activity->properties['attributes']['driver_id']) }} </span>
                                    </div>
                                @else
                                <div>
                                    <h3>{{ __("Driver changed from:") }}</h3>
                                    <span class="outline-none inline-flex justify-center items-center group rounded-md text-amber-600 bg-amber-100 dark:bg-slate-700 gap-x-1 text-xs font-semibold px-2.5 py-0.5" dir="ltr">{{ \App\Models\Driver::getName($activity->properties['old']['driver_id'] ?? null) }} </span>
                                    <strong>{{ __("To:") }}</strong>
                                    <span class="outline-none inline-flex justify-center items-center group rounded-md text-sky-600 bg-sky-100 dark:bg-slate-700 gap-x-1 text-xs font-semibold px-2.5 py-0.5" dir="ltr">{{  \App\Models\Driver::getName($activity->properties['attributes']['driver_id']) }} </span>
                                </div>
                                @endif
                            @elseif(isset($activity->properties['old']['time_apply_status']))
                                <div>
                                    <h3>{{ __("The time of applying the status changed:") }}</h3>
                                    <span class="outline-none inline-flex justify-center items-center group rounded-md text-amber-600 bg-amber-100 dark:bg-slate-700 gap-x-1 text-xs font-semibold px-2.5 py-0.5" dir="ltr">{{ verta($activity->properties['old']['time_apply_status'])->format("Y/m/d H:i") }}</span>
                                    <strong>{{ __("To:") }}</strong>
                                    <span class="outline-none inline-flex justify-center items-center group rounded-md text-sky-600 bg-sky-100 dark:bg-slate-700 gap-x-1 text-xs font-semibold px-2.5 py-0.5" dir="ltr">{{ verta($activity->properties['attributes']['time_apply_status'])->format("Y/m/d H:i") }}</span>
                                </div>
                            @endif
                        @else
                            <div>
                                <strong> {{ $activity->description }} </strong>
                            </div>
                        @endif
                        <div>
                            <strong>{{ __("Changed by:") }}</strong> {{ $activity->causer->name ?? 'System' }}
                            <strong>{{ __("At:") }}</strong>
                            <span class="outline-none inline-flex justify-center items-center group rounded-md text-secondary-600 bg-secondary-100 dark:text-secondary-400 dark:bg-slate-700 gap-x-1 text-xs font-semibold px-2.5 py-0.5">{{ verta($activity->created_at)->format('d F Y - H:i') }}</span>
                        </div>
                    </li>
                @else
                    {{-- TODO: will complate --}}
                @endif
            @endforeach
        </ul>
    </x-filament::section>
</x-filament-widgets::widget>
