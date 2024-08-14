<div class="flex justify-center flex-wrap gap-4 py-4">
    @foreach($steps as $step)
        @if($step->isCurrent())
            <x-srj-button
                :label="$step->label"
                :icon="$step->icon"
                outline
                fuchsia
                hover="warning"
                focus:solid.gray
            />
        @else
            <x-srj-button
                :label="$step->label"
                :icon="$step->icon"
                outline
                gray
                hover="warning"
                focus:solid.gray
                :wire:click="$step->isPrevious() ? $step->show() : ''"
            />
        @endif
    @endforeach
</div>
