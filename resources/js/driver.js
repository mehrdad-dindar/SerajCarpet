import "../css/client/client.css";
// import swal from 'sweetalert2';
// window.Swal = swal;
// import Alpine from 'alpinejs'

// window.Alpine = Alpine

// Alpine.start()

import { driver } from "driver.js";
import "driver.js/dist/driver.css";

// تابع برای به‌روزرسانی موقعیت مکانی راننده
function updateDriverLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            (position) => {
                // موقعیت مکانی جدید راننده
                const latitude = position.coords.latitude.toFixed(12);
                const longitude = position.coords.longitude.toFixed(12);

                // ارسال به سرور با استفاده از AJAX
                axios.post('/dashboard/update-location', {
                    latitude: latitude,
                    longitude: longitude
                }).then(response => {
                    console.log("Location updated successfully:", response.data);
                }).catch(error => {
                    console.error("Error updating location:", error);
                });
            },
            (error) => {
                console.error("Error getting location:", error);
            },
            {
                enableHighAccuracy: true, // دقت بالاتر برای موقعیت
                maximumAge: 10000,       // ذخیره موقعیت برای ۱۰ ثانیه
                timeout: 5000            // محدودیت زمانی ۵ ثانیه
            }
        );
    } else {
        console.error("Geolocation is not supported by this browser.");
    }
}
updateDriverLocation();
