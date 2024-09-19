<div id="map" class="relative w-[100vw] h-[100vh]">
    <span class="text-white absolute top-1.5 left-1.5 z-30">SerajCarpet</span>
    <div class="absolute bottom-16 z-30 right-0 left-0 flex justify-center">
        <x-srj-button :label="__('Submit Location')" class="bg-gradient-fuchsia font-iranSans !text-base" wire:click="submit" id="button1"/>
    </div>
    <script type="module">
        console.log(L)
        console.log(nmp_mapboxgl)
        // var map = L.map('map', {
        //     zoomControl: false
        // }).setView([35.6892, 51.3890], 12);
        //
        // L.tileLayer('http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}&lang=fa', {
        //     maxZoom: 18,
        //     tileSize: 512,
        //     zoomOffset: -1,
        //     attributionControl: false
        // }).addTo(map);
    </script>

{{--    <script type="module">--}}
{{--        document.addEventListener('DOMContentLoaded', function () {--}}
{{--            const map = new nmp_mapboxgl.Map({--}}
{{--                attributionControl: true,--}}
{{--                mapType: nmp_mapboxgl.Map.mapTypes.neshanVector,--}}
{{--                container: "map",--}}
{{--                zoom: 11,--}}
{{--                pitch: 0,--}}
{{--                center: [51.389, 35.6892],--}}
{{--                minZoom: 2,--}}
{{--                maxZoom: 21,--}}
{{--                trackResize: true,--}}
{{--                mapKey: 'web.9b720353743c4534a41a4a22df831720',--}}
{{--                poi: false,--}}
{{--                traffic: true,--}}
{{--                mapTypeControllerOptions: {--}}
{{--                    show: false,--}}
{{--                    position: 'bottom-left'--}}
{{--                }--}}
{{--            });--}}
{{--            map.addControl(new nmp_mapboxgl.GeolocateControl({--}}
{{--                positionOptions: {--}}
{{--                    enableHighAccuracy: true--}}
{{--                },--}}
{{--                // When active the map will receive updates to the device's location as it changes.--}}
{{--                trackUserLocation: true,--}}
{{--                // Draw an arrow next to the location dot to indicate which direction the device is heading.--}}
{{--                showUserHeading: true--}}
{{--            }));--}}
{{--            const marker = new nmp_mapboxgl.Marker()--}}
{{--                .setLngLat(map.getCenter())--}}
{{--                .addTo(map);--}}

{{--            map.on('dragstart', () => {--}}
{{--                marker.setRotation(10);--}}
{{--            });--}}
{{--            map.on('dragend', () => {--}}
{{--                marker.setRotation(0);--}}
{{--            });--}}
{{--            map.on('move', () => {--}}
{{--                const center = map.getCenter();--}}
{{--                marker.setLngLat(center);--}}
{{--            });--}}

{{--            document.querySelector('[wire\\:click="submit"]').addEventListener('click', function () {--}}
{{--                const center = map.getCenter();--}}
{{--                marker.setLngLat(center);--}}

{{--                const centerCoordinates = {--}}
{{--                    lng: center.lng.toFixed(12),--}}
{{--                    lat: center.lat.toFixed(12)--}}
{{--                };--}}
{{--                console.log(centerCoordinates.lat)--}}
{{--                Livewire.dispatch('updateLocation', {--}}
{{--                    latitude: centerCoordinates.lat,--}}
{{--                    longitude: centerCoordinates.lng--}}
{{--                });--}}
{{--            });--}}

{{--            const driverObj = driver({--}}
{{--                nextBtnText: 'بعدی',--}}
{{--                prevBtnText: 'قبلی',--}}
{{--                doneBtnText: 'حله',--}}
{{--                showProgress: true,--}}
{{--                steps: [--}}
{{--                    {popover: {title: 'سلام 😃👋', description: 'به صفحه ثبت لوکیشن قالی‌شویی سراج خوش آمدید'}},--}}
{{--                    {--}}
{{--                        element: 'button.mapboxgl-ctrl-geolocate',--}}
{{--                        popover: {--}}
{{--                            title: 'مکان شما',--}}
{{--                            description: 'با استفاده از این دکمه میتوانید مکان خود را روی نقشه پیدا کنید'--}}
{{--                        }--}}
{{--                    },--}}
{{--                    {--}}
{{--                        element: '#button1',--}}
{{--                        popover: {--}}
{{--                            title: 'ثبت موقعیت مکانی',--}}
{{--                            description: 'پس از اطمینان از صحیح بودن موقعیت با استفاده از این دکمه نسبت به ثبت اقدام کنید'--}}
{{--                        }--}}
{{--                    },--}}
{{--                ]--}}
{{--            });--}}

{{--            driverObj.drive();--}}
{{--        });--}}
{{--    </script>--}}
</div>
