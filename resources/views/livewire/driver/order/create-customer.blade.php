<x-srj-modal name="simpleModal">
    <x-srj-card :title="__('Create Customer')">
        <x-srj-alert :title="__('Note that the customer\'s name and mobile number are required !')" warning icon="bell-alert" class="icon-margin mb-8"/>
        <div class="grid md:grid-cols-2 gap-4 grid-cols-1">
            <x-srj-input
                wire:model="name"
                :label="__('Name')"
                autofocus
                tabindex="1"
            />
            <x-srj-phone
                wire:model="phone"
                :label="__('Customer Phone')"
                placeholder="(0912) 345-6789"
                class="ltr"
                mask="(####) ###-####"
                tabindex="2"
            />
        </div>
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-srj-button flat label="Cancel" x-on:click="close" />

            <x-srj-button primary label="I Agree" wire:click="create" x-on:click="close"/>
        </x-slot>
    </x-srj-card>
</x-srj-modal>
