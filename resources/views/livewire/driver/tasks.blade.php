<div class="font-iranSans">
    @section('head')
        <link rel="stylesheet" href="https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.css"/>
        <script src="https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.js"></script>
    @endsection
    <div id="driver-map" class="absolute inset-0 z-0"></div>
    <x-srj-mini-button icon="arrow-left" rounded fuchsia class="absolute left-5 top-5 z-10 bg-gradient-fuchsia"
                       wire:click="goToIndex"/>
    <x-srj-button id="getLocationButton" label="Salam" fuchsia class="absolute left-1/2 top-5 z-10 bg-gradient-fuchsia"/>
    <div class="absolute bottom-16 inset-x-0 z-10 flex justify-center">
        <ul class="max-h-60 overflow-y-scroll w-4/5">
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
                    <x-srj-badge :label="$order->status->getLabel($order->status_id)"
                                 :class="$order->status->getColor($order->status_id) . ' text-xxs absolute top-0 left-0'"/>
                </li>
            @endforeach
        </ul>
    </div>
    <script type="module">
        document.addEventListener('livewire:init', function () {
            Livewire.on('callInitiated', function (data) {
                console.log('tel:+98' + data.number);
                window.location.href = 'tel:+98' + data.number;
            });
        });

        // const map = new L.Map("driver-map", {
        //     key: "web.9b720353743c4534a41a4a22df831720",
        //     maptype: "neshan",
        //     poi: false,
        //     traffic: false,
        //     center: [35.699756, 51.338076],
        //     zoom: 14,
        //     zoomControl: false
        // })

        var map = L.map('driver-map').setView([35.6892, 51.3890], 12); // مرکز نقشه روی تهران

        L.tileLayer('http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
            zoomControl: false,
            traffic: true,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);


        L.control.zoom({
            position: 'topright'
        }).addTo(map);


        L.Routing.control({
            waypoints: [
                L.latLng({{settings()->location_latitude}}, {{settings()->location_longitude}}),
                @php foreach($points as $point){
             echo "L.latLng(".$point['latitude'].", ".$point['longitude']."),\n";
            }
                @endphp
                L.latLng({{settings()->location_latitude}}, {{settings()->location_longitude}})],
            lineOptions: {
                styles: [
                    {
                        color: "blue",
                        opacity: 0.6,
                        weight: 4
                    }
                ]
            },
            routeWhileDragging: false,
            draggableWaypoints: false,
            addWaypoints: false,
            collapsible: false,
        }).addTo(map);
    </script>


    <script type="module">
        /*var map = L.map('driver-map', {
            attributionControl: false,
            zoomControl: false
        }).setView([35.6892, 51.3890], 13);

        L.control.zoom({
            position: 'topright'
        }).addTo(map);

        var myAttrControl = L.control.attribution({position: 'bottomleft'}).addTo(map);
        myAttrControl.setPrefix('<a href="https://serajcarpet.com/">سراج</a>');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            tileSize: 512,
            zoomOffset: -1,
            minZoom: 1,
            crossOrigin: true
        }).addTo(map);


        L.Routing.control({
            waypoints: [
                L.latLng(lat, lng),
                @php /*foreach($points as $point){
             echo "L.latLng(".$point->location[0].", ".$point->location[1]."),\n";
            }*/
                @endphp],
            lineOptions: {
                styles: [
                    {
                        color: "blue",
                        opacity: 0.6,
                        weight: 4
                    }
                ]
            },
            routeWhileDragging: false,
            draggableWaypoints: false,
            addWaypoints: false,
            collapsible: false,
        }).addTo(map);*/
    </script>
</div>
