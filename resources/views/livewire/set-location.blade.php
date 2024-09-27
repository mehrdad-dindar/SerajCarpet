<div class="relative w-screen h-screen">
    <div id="map" class="w-full h-full"></div>
    <span class="text-white absolute top-1.5 left-1.5 z-30">SerajCarpet</span>
    <div class="absolute bottom-16 z-30 right-0 left-0 flex justify-center">
        <x-srj-button :label="__('Submit Location')" class="bg-gradient-fuchsia font-iranSans !text-base" id="button1"/>
    </div>
    @push('scripts')
        <script type="module">
            document.addEventListener('DOMContentLoaded', function () {
                const map = new nmp_mapboxgl.Map({
                    attributionControl: true,
                    mapType: nmp_mapboxgl.Map.mapTypes.neshanVector,
                    container: "map",
                    zoom: 11,
                    pitch: 0,
                    center: [51.389, 35.6892],
                    minZoom: 2,
                    maxZoom: 21,
                    trackResize: true,
                    mapKey: 'web.00dec639aee84334909b52fb87d55b51',
                    poi: false,
                    traffic: false,
                    mapTypeControllerOptions: {
                        show: false,
                        position: 'bottom-left'
                    }
                });
                map.addControl(new nmp_mapboxgl.GeolocateControl({
                    positionOptions: {
                        enableHighAccuracy: true
                    },
                    // When active the map will receive updates to the device's location as it changes.
                    trackUserLocation: true,
                    // Draw an arrow next to the location dot to indicate which direction the device is heading.
                    showUserHeading: true
                }));
                const marker = new nmp_mapboxgl.Marker()
                    .setLngLat(map.getCenter())
                    .addTo(map);

                map.on('dragstart', () => {
                    marker.setRotation(10);
                });
                map.on('dragend', () => {
                    marker.setRotation(0);
                });
                map.on('move', () => {
                    const center = map.getCenter();
                    marker.setLngLat(center);
                });
                document.getElementById('button1').addEventListener('click', function () {
                    const center = map.getCenter();
                    marker.setLngLat(center);

                    const centerCoordinates = {
                        lng: center.lng.toFixed(12),
                        lat: center.lat.toFixed(12)
                    };
                    Livewire.dispatch('updateCustomerLocation', {
                        latitude: centerCoordinates.lat,
                        longitude: centerCoordinates.lng
                    });
                });

                const driver = window.driver.js.driver;
                const driverObj = driver({
                    nextBtnText: 'بعدی',
                    prevBtnText: 'قبلی',
                    doneBtnText: 'حله',
                    showProgress: true,
                    steps: [
                        {popover: {title: 'سلام 😃👋', description: 'به صفحه ثبت لوکیشن قالی‌شویی سراج خوش آمدید'}},
                        {
                            element: 'button.mapboxgl-ctrl-geolocate',
                            popover: {
                                title: 'مکان شما',
                                description: 'با استفاده از این دکمه میتوانید مکان خود را روی نقشه پیدا کنید'
                            }
                        },
                        {
                            element: 'div.mapboxgl-marker',
                            popover: {
                                title: 'انتخاب نقطه موقعیت',
                                description: 'میتوانید با کشیدن صفحه موقعیت خود را با این علامت تنظیم کنید'
                            }
                        },
                        {
                            element: '#button1',
                            popover: {
                                title: 'ثبت موقعیت مکانی',
                                description: 'پس از اطمینان از صحیح بودن موقعیت با استفاده از این دکمه نسبت به ثبت اقدام کنید'
                            }
                        },
                    ]
                });

                driverObj.drive();
            });
        </script>
    @endpush
</div>
