<div class="font-iranSans">
    <div id="driver-map" class="absolute inset-0 z-0"></div>
    <x-srj-mini-button icon="arrow-left" rounded fuchsia class="absolute left-5 top-5 z-10 bg-gradient-fuchsia"
                       wire:click="goToIndex"/>
    <div class="absolute bottom-16 inset-x-0 z-10 flex justify-center">
        <ul class="max-h-60 overflow-y-scroll w-3/4">
            @foreach($orders as $order)
                <li class="bg-white rounded-xl p-4 mb-2 relative">
                    <div class="flex justify-between items-center">
                        <div>
                            <h5 class="text-sm font-semibold">{{$order->customer->name}}</h5>
                            <span class="text-xxs text-muted">
                                {{ "منطقه " . $order->address->municipality_zone . " - " . $order->address->neighbourhood}}
                            </span>
                        </div>
                        <x-srj-mini-button icon="phone" rounded lime class="bg-gradient-lime"
                                           wire:click="makeCall('{{ $order->customer->phone }}')"/>
                    </div>
                    <span class="text-muted text-xs">{{$order->address->address}}</span>
                    <x-srj-badge :label="$order->getStatusLabel()" :class="$order->getStatusColor() . ' text-xxs absolute top-0 left-0'" />
                </li>
            @endforeach
        </ul>
    </div>
    <script>
        document.addEventListener('livewire:init', function () {
            Livewire.on('callInitiated', function (data) {
                console.log('tel:+98' + data.number);
                window.location.href = 'tel:+98' + data.number;
            });
        });
    </script>

    <script type="module">
        var map = L.map('driver-map', {
            attributionControl: false,
            zoomControl: false
        }).setView([35.7219, 51.3347], 12);
        L.control.zoom({
            position: 'topright'
        }).addTo(map);

        var myAttrControl = L.control.attribution({position: 'bottomleft'}).addTo(map);
        myAttrControl.setPrefix('<a href="https://serajcarpet.com/">سراج</a>');

        const key = 'KLoLXEB9eFv60ELGhKUn';

        L.tileLayer(`https://api.maptiler.com/maps/streets-v2/{z}/{x}/{y}.png?key=${key}`, { //style URL
            tileSize: 512,
            zoomOffset: -1,
            minZoom: 1,
            crossOrigin: true
        }).addTo(map);
    </script>
</div>
