<div class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
    <div class="p-6">
        <h6>{{ __("Orders List") }}</h6>
    </div>
    @if($orders->count())
        <livewire:customer.order.list-orders :orders="$orders"/>
    @else
        <x-srj-alert id="alert" :title="__('There are currently no orders.')" warning rounded="2xl" />
    @endif
</div>
