<div id="commentForm">
<x-srj-textarea
    class="w-full focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"
    rows="4"
    placeholder="توضیحات خود را اینجا بنویسید..."
    wire:model="body"
/>
    <div wire:ignore.self>
        <livewire:voice-recorder :order="$order" :key="'voice-recorder-'.$order->id"/>
    </div>
    <button
        type="submit"
        class="mt-4 w-full bg-blue-500 text-white py-2 rounded-xl hover:bg-blue-600 transition duration-300"
        wire:click="submit"
    >
        ثبت توضیح جدید
    </button>
</div>
