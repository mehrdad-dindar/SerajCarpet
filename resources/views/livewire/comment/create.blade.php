<div id="commentForm">
    <x-srj-textarea
        class="w-full focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
        rows="4"
        placeholder="توضیحات خود را اینجا بنویسید..."
        wire:model="body"
    />
    <div wire:ignore.self class="flex items-center my-2 gap-2 justify-between">
        <x-srj-button primary wire:click="submit" label="ثبت توضیح جدید" icon="paper-airplane"/>
        <livewire:voice-recorder :order="$order" :key="'voice-recorder-'.$order->id"/>
    </div>
</div>
