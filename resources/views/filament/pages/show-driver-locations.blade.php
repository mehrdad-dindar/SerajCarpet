<x-filament-panels::page>
    <div id="map" style="width: 100%; height: 500px;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const map = L.map('map').setView([35.6892, 51.3890], 12);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                tileSize: 512,
                zoomOffset: -1,
                attributionControl: false
            }).addTo(map);
            @dd($this->getDriverLocations())

            /*var myAttrControl = L.control.attribution({position: 'bottomleft'}).addTo(map);
            myAttrControl.setPrefix('<a href="https://serajcarpet.com/">سراج</a>');

            @foreach($this->getDriverLocations() as $location)
            L.marker([{{ $location->latitude }}, {{ $location->longitude }}])
                .addTo(map)
                .bindPopup('راننده: {{ $location->driver->name }}');
            @endforeach*/
        });
    </script>
</x-filament-panels::page>
