<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            state: $wire.entangle('{{ $getStatePath() }}'),
            map: null,
            marker: null,
            initMap() {
                // بارگذاری فایل CSS نشان
                if (!document.getElementById('neshan-css')) {
                    const link = document.createElement('link');
                    link.id = 'neshan-css';
                    link.rel = 'stylesheet';
                    link.href = 'https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.css';
                    document.head.appendChild(link);
                }

                // بارگذاری فایل JS نشان
                if (!document.getElementById('neshan-js')) {
                    const script = document.createElement('script');
                    script.id = 'neshan-js';
                    script.src = 'https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.js';
                    script.onload = () => this.setupMap();
                    document.head.appendChild(script);
                } else {
                    this.setupMap();
                }
            },
            setupMap() {
                // اگر قبلا مختصاتی بود آن را لود کن، در غیر این صورت مرکز تهران
                const lat = this.state?.lat || 35.6997;
                const lng = this.state?.lng || 51.3380;

                // ساخت نقشه با SDK نشان
                this.map = new L.Map(this.$refs.mapContainer, {
                    key: 'web.dc4131e9109d4b12ab1de99b8cfda235',
                    maptype: 'neshan',
                    poi: true,
                    traffic: false,
                    center: [lat, lng],
                    zoom: 14
                });

                // افزودن پین روی نقشه
                this.marker = L.marker([lat, lng]).addTo(this.map);

                // آپدیت کردن مختصات با کلیک روی نقشه
                this.map.on('click', (e) => {
                    const newLat = e.latlng.lat;
                    const newLng = e.latlng.lng;

                    this.marker.setLatLng([newLat, newLng]);

                    // آپدیت کردن state فیلامنت
                    this.state = { lat: newLat, lng: newLng };
                });
            }
        }"
        x-init="initMap()"
        wire:ignore
    >
        {{-- حتما باید ارتفاع (height) ثابت داشته باشد تا نقشه لود شود --}}
        <div x-ref="mapContainer" style="height: 400px; width: 100%; border-radius: 0.5rem; z-index: 10;"></div>
    </div>
</x-dynamic-component>
