<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
    @foreach($routeTypes as $type)
        @livewire("driver.order.card",['type' => $type])
    @endforeach
</div>
