<div>
    <div x-data class="relative inline-block">
        <!-- آیکون سنجاق -->
        <x-srj-mini-button
            rounded
            info
            icon="paper-clip"
            x-on:click="$refs.fileInput.click()"
            title="پیوست فایل"
        />

        <!-- فیلد فایل مخفی -->
        <input
            type="file"
            x-ref="fileInput"
            wire:model="attachment"
            accept="image/*,video/*"
            class="hidden"
        >
    </div>
    @error('attachment') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
</div>
