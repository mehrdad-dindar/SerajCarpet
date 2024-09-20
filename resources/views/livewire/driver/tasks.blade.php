<div class="relative">
    @section('headerScripts')
        <link rel="stylesheet" href="{{ asset("css/driver.css") }}"/>
        <script src="{{ asset("js/driver.js.iife.js") }}"></script>
    @endsection
    <div wire:ignore id="driver-map" class="relative w-[100vw] h-[100vh]"></div>
        <x-srj-mini-button icon="arrow-left" rounded fuchsia class="absolute left-5 top-5 z-30 bg-gradient-fuchsia"
                           wire:click="goToIndex"/>
        <div class="absolute bottom-16 inset-x-0 z-30 flex justify-center">
            <ul class="max-h-60 overflow-y-scroll w-4/5" id="orders-container">
                @foreach($orders as $key => $order)
                    <li class="bg-white rounded-xl p-4 mb-2 relative">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-base font-semibold">{{$order->customer->name}}</h2>
                                <span class="text-xxs text-muted">
                                    {{ "منطقه " . $order->address->municipality_zone . " - محله " . $order->address->neighbourhood}}
                                </span>

                            </div>
                            <div class="flex gap-2">
                                <div @if($key === 0) id="phone" @endif>
                                <x-srj-mini-button icon="phone" rounded lime class="bg-gradient-lime"
                                                   wire:click="makeCall('{{ $order->customer->phone }}')"/>
                                </div>
                                <div @if($key === 0) id="order-wizard" @endif>
                                <x-srj-mini-button icon="pencil-square" rounded info class="bg-gradient-cyan"
                                                   show-step="customer-info"
                                                   x-on:click="$openModal('orderWizardModal')"
                                                   :key="$order->id"
                                                   wire:click="showOrderWizard({{ $order->id }})"/>
                                </div>
                                <div @if($key === 0) id="neshan" @endif>
                                <x-srj-mini-button icon="map-pin" rounded warning class="bg-gradient-orange"
                                                   wire:click="getDirections({{$order}})" />
                                </div>
                            </div>
                        </div>
                        <span class="text-muted text-xs">{{$order->address->getFullAddress()}}</span>
                        <x-srj-badge :label="$order->status->getLabel($order->status_id)"
                                     :class="$order->status->getColor($order->status_id) . ' text-xxs absolute top-0 left-0'"/>
                    </li>
                @endforeach
                <x-srj-modal title="{{ __('Edit Order') }}" name="orderWizardModal" blur="base">
                    @if($selectedOrder)
                        <livewire:create-order-wizard show-step="customer-info" :order="$selectedOrder" :key="$selectedOrder->id"/>
                    @endif
                </x-srj-modal>
            </ul>
        </div>
    <script type="module">
        document.addEventListener('livewire:init', function () {
            Livewire.on('callInitiated', function (data) {
                window.location.href = 'tel:+98' + data.number;
            });
            Livewire.on('closeModal', function () {
                $closeModal('orderWizardModal')
            });
        });
        document.addEventListener('DOMContentLoaded', function () {

            const driver = window.driver.js.driver;
            const driverObj = driver({
                nextBtnText: 'بعدی',
                prevBtnText: 'قبلی',
                doneBtnText: 'حله',
                showProgress: true,
                steps: [
                    {
                        popover: {
                            title: 'سلام راننده 👋',
                            description: 'به صفحه مسیرها و سفارشات خوش آمدید. اینجا مسیرهای شما برای سفارشات پخشی / جمعی نمایش داده می‌شود.'
                        }
                    },
                    {
                        element: '#driver-map',
                        popover: {
                            title: 'نقشه مسیرها',
                            description: 'این نقشه تمامی مسیرها به همراه مقصدهای شما را نمایش می‌دهد. ما برای این مسیر یابی از شیوه‌ای بسیار نوین استفاده کردیم تا بهینه ترین مسیر برای شما فراهم شود'
                        }
                    },
                    {
                        element: '#orders-container',
                        popover: {
                            title: 'لیست سفارشات هر مقصد',
                            description: 'هر مقصد دارای لیستی از سفارشات است که در این بخش نمایش داده می‌شود. شما می‌توانید جزئیات هر سفارش را مشاهده کنید.'
                        }
                    },
                    {
                        element: '#phone',
                        popover: {
                            title: 'تماس با مشتری',
                            description: 'برای هماهنگی بیشتر یا سوالات دیگر، می‌توانید با مشتری از طریق این دکمه تماس بگیرید.'
                        }
                    },
                    {
                        element: '#order-wizard',
                        popover: {
                            title: 'ثبت تحویل سفارش',
                            description: 'پس از تحویل سفارش در مقصد، با کلیک روی این دکمه و فرم نمایش داده شده تحویل را ثبت کنید.'
                        }
                    },
                    {
                        element: '#neshan',
                        popover: {
                            title: 'مسیریابی با نرم افزار نشان',
                            description: 'برای مسیریابی تا مقصد هر سفارش با استفاده از مسیریاب نشان میتوانید از این دکمه استفاده کنید.'
                        }
                    }
                ]
            });

            driverObj.drive();
        });
    </script>
    @script
    <script type="module">
        const map = L.map('driver-map', {
            zoomControl: false
        }).setView([35.6892, 51.3890], 12); // مرکز نقشه روی تهران

        // http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}
        L.tileLayer('http://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}', {
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

        var LeafIcon = L.Icon.extend({
            options: {
                shadowUrl: false,
                iconSize:     [71, 95],
                iconAnchor:   [71, 95],
                popupAnchor:  [-3, -76]
            }
        });

        var generalIcon = new LeafIcon({iconUrl: '{{asset("panel/img/general-icon.png")}}'}),
            driverIcon = new LeafIcon({iconUrl: '{{asset("panel/img/driver-icon.png")}}'});

        L.icon = function (options) {
            return new L.Icon(options);
        };

        var control = L.Routing.control({
            waypoints: [
                L.latLng({{settings()->location_latitude}}, {{settings()->location_longitude}}),
                @php foreach($points as $point){
             echo "L.latLng(".$point['latitude'].", ".$point['longitude']."),\n";
            }
                @endphp
                L.latLng({{settings()->location_latitude}}, {{settings()->location_longitude}})],
            routeWhileDragging: true,
            draggableWaypoints: false,
            addWaypoints: false,
            createMarker: function (i, wp, nWps) {
                return L.marker(wp.latLng, {
                    icon: generalIcon,
                    draggable: false
                });
            },
            // غیرفعال کردن توضیحات مسیر
            show: false,
            lineOptions: {
                addWaypoints: false,
                styles: [
                    {
                        color: "blue",
                        opacity: 0.6,
                        weight: 4
                    }
                ]
            },
            router: new L.Routing.OSRMv1({
                serviceUrl: `https://router.project-osrm.org/route/v1`
            }),
            fitSelectedRoutes: false, // جلوگیری از زوم خودکار به مسیر انتخاب شده
            showAlternatives: false, // غیرفعال کردن نمایش مسیرهای جایگزین
            altLineOptions: {styles: [{opacity: 0}]}
        }).addTo(map);

        const driverMarker = L.marker([35.6892, 51.3890],{icon: driverIcon}).addTo(map);

        function updateDriverLocation(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            // به روز رسانی موقعیت مارکر راننده
            driverMarker.setLatLng([lat, lng]);
            // به روز رسانی مسیر با موقعیت جدید راننده

            map.panTo(new L.LatLng(lat, lng), {
                animate: true, // فعال کردن انیمیشن
                duration: 1.0 // مدت زمان انیمیشن (به ثانیه)
            });
            var waypoints = control.getWaypoints();
            waypoints[0].latLng = L.latLng(lat, lng); // بروز رسانی نقطه شروع به موقعیت جدید
            // control.setWaypoints(waypoints);
            $wire.dispatch('updateDriverLocation',{
                latitude: lat,
                longitude: lng
            });
        }

        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(function (position) {
                updateDriverLocation(position);
            }, function (error) {
                console.error(error);
            }, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }


    </script>
    @endscript
</div>
