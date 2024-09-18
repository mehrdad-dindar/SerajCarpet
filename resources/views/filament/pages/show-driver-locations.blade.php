<x-filament-panels::page>
    <div id="map" style="width: 100%; height: 500px;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            var map = L.map('map', {
                zoomControl: false
            }).setView([35.6892, 51.3890], 12);

            L.tileLayer('https://api.maptiler.com/maps/streets/{z}/{x}/{y}.png?key=KLoLXEB9eFv60ELGhKUn	', {
                maxZoom: 18,
                tileSize: 512,
                zoomOffset: -1,
                attributionControl: false
            }).addTo(map);

            var myAttrControl = L.control.attribution({position: 'bottomleft'}).addTo(map);
            myAttrControl.setPrefix('<a href="https://serajcarpet.com/">سراج</a>');

            L.control.zoom({
                position: 'topright'
            }).addTo(map);

            var driverIcon = L.icon({
                iconUrl: '{{asset("panel/img/driver-icon.png")}}',
                shadowUrl: false,

                iconSize:     [71, 95],
                iconAnchor:   [22, 94],
                popupAnchor:  [-3, -76]
            });

            @php
            foreach ($this->getDriverLocations() as $location) {
                echo "L.marker([".$location->latitude.", ".$location->longitude." ],{icon: driverIcon})
                .addTo(map)
                .bindPopup('راننده: ".$location->driver->name ."');";
            }
            @endphp
        });
    </script>
</x-filament-panels::page>
