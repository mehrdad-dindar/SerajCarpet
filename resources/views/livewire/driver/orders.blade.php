<div>
    <div x-data="{ open: false }">
        <x-srj-button :label="__('New Order')" icon="plus" green @click="open = true"/>

        <div x-show="open" @click.away="open = false">
            <livewire:driver.new-order/>
        </div>
    </div>
</div>
