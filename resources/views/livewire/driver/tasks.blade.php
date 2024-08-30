<div class="font-iranSans">
    @section('head')
        <link
            rel="stylesheet"
            href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.1/mapbox-gl-directions.css"
            type="text/css"
        />
    @endsection
    <div id="driver-map" class="absolute inset-0 z-0"></div>
    <x-srj-mini-button icon="arrow-left" rounded fuchsia class="absolute left-5 top-5 z-10 bg-gradient-fuchsia"
                       wire:click="goToIndex"/>
    <x-srj-button id="getLocationButton" fuchsia class="absolute left-1/2 top-5 z-10 bg-gradient-fuchsia"/>
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
                    <x-srj-badge :label="$order->getStatusLabel()"
                                 :class="$order->getStatusColor() . ' text-xxs absolute top-0 left-0'"/>
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
        let lat = 35.7219;
        let lng = 51.3347;
        let circle;
        document.getElementById('getLocationButton').addEventListener('click', function() {
            navigator.geolocation.watchPosition(success, error);
        });

        function success(pos){
            lat = pos.coords.latitude;
            lng = pos.coords.longitude;
            const accuracy = pos.coords.accuracy;
            console.log(lat,lng)

            if (circle)
                map.removeLayer(circle)

            circle = L.circle([lat,lng],{radius: accuracy}).addTo(map)
            map.fitBounds(circle.getBounds())
        }
        function error(err){
            if (err.code === 1){
                alert("لطفا برای مسیریابی بهتر لوکیشن خود را روشن کنید")
            }
        }

        var map = L.map('driver-map', {
            attributionControl: false,
            zoomControl: false
        }).setView([lat, lng], 12);
        L.control.zoom({
            position: 'topright'
        }).addTo(map);

        var myAttrControl = L.control.attribution({position: 'bottomleft'}).addTo(map);
        myAttrControl.setPrefix('<a href="https://serajcarpet.com/">سراج</a>');

        L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', { //style URL
            tileSize: 512,
            zoomOffset: -1,
            minZoom: 1,
            crossOrigin: true
        }).addTo(map);


        L.Routing.control({
            waypoints: [
                L.latLng(lat, lng),
                @php foreach($points as $point){
             echo "L.latLng(".$point->location[0].", ".$point->location[1]."),\n";
            }
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
        }).addTo(map);


        // map.locate({setView: true, maxZoom: 16});
        //
        // function onLocationFound(e) {
        //     var radius = e.accuracy;
        //
        //     if (circle)
        //         map.removeLayer(circle)
        //     // L.marker(e.latlng).addTo(map);
        //     L.circle(e.latlng, radius).addTo(map);
        //     map.fitBounds(circle.getBounds())
        //
        // }
        //
        // map.on('locationfound', onLocationFound);
        //
        //
        // function onLocationError(e) {
        //     alert(e.message);
        // }
        //
        // map.on('locationerror', onLocationError);
    </script>
</div>
