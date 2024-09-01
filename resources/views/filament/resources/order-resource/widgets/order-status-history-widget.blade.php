<x-filament-widgets::widget>
    <x-filament::section>
        <h3 class="text-lg font-bold mb-4">{{ __("Order Status History") }}</h3>
        <ul>
            @foreach ($activities as $activity)
                @if($activity->properties['attributes'])
                    <li class="mb-2">
                        <div>
                            <strong>{{ __("Status changed from:") }}</strong> <x-srj-badge :label="\App\Models\OrderStatus::getLabel($activity->properties['old']['status_id'] ?? null)" />
                            <strong>{{ __("To:") }}</strong> <x-srj-badge :label="\App\Models\OrderStatus::getLabel($activity->properties['attributes']['status_id'])" />
                        </div>
                        <div>
                            <strong>{{ __("Changed by:") }}</strong> {{ $activity->causer->name ?? 'System' }}
                            <strong>{{ __("At:") }}</strong> {{ $activity->created_at->format('Y-m-d H:i:s') }}
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    </x-filament::section>
</x-filament-widgets::widget>
