<div
    class="relative flex flex-col w-full min-w-0 mb-0 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
    <div class="p-6">
        <h6>{{ __("Invoices List") }}</h6>
    </div>
    <livewire:customer.invoice.list-invoices :invoices="$invoices"/>
</div>
