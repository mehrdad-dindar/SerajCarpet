import "../css/client/client.css";
// import swal from 'sweetalert2';
// window.Swal = swal;
// import Alpine from 'alpinejs'

// window.Alpine = Alpine

// Alpine.start()

import { driver } from "driver.js";
import "driver.js/dist/driver.css";

// تابع برای به‌روزرسانی موقعیت مکانی راننده
function updateDriverLocation()
{
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                axios.post('/dashboard/update-location', {
                    latitude: position.coords.latitude.toFixed(12),
                    longitude: position.coords.longitude.toFixed(12)
                }).catch(error => {
                    console.error("خطا در به‌روزرسانی موقعیت:", error);
                })
            },
            (error) => {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        console.error("کاربر اجازه دسترسی به موقعیت مکانی را نداد.");
                        // alert("لطفاً دسترسی به موقعیت مکانی را در تنظیمات مرورگر فعال کنید.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        console.error("اطلاعات موقعیت مکانی در دسترس نیست.");
                        // alert("اطلاعات موقعیت مکانی در دسترس نمی‌باشد.");
                        break;
                    case error.TIMEOUT:
                        console.error("درخواست موقعیت مکانی به پایان رسید.");
                        // alert("درخواست موقعیت مکانی زمان‌بر بود. لطفاً دوباره تلاش کنید.");
                        break;
                    default:
                        console.error("خطای ناشناخته‌ای رخ داده است.");
                        // alert("مشکلی در دریافت موقعیت مکانی شما وجود دارد.");
                        break;
                }
            },
            {
                enableHighAccuracy: true,
                maximumAge: 10000
            }
        );
        /*navigator.geolocation.watchPosition(
            (position) => {
                const latitude = position.coords.latitude.toFixed(12);
                const longitude = position.coords.longitude.toFixed(12);
                axios.post('/dashboard/update-location', {
                    latitude: latitude,
                    longitude: longitude
                }).then(response => {
                    console.log("موقعیت با موفقیت به‌روزرسانی شد:", response.data);
                }).catch(error => {
                    console.error("خطا در به‌روزرسانی موقعیت:", error);
                });
            },
            (error) => {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        console.error("کاربر اجازه دسترسی به موقعیت مکانی را نداد.");
                        // alert("لطفاً دسترسی به موقعیت مکانی را در تنظیمات مرورگر فعال کنید.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        console.error("اطلاعات موقعیت مکانی در دسترس نیست.");
                        // alert("اطلاعات موقعیت مکانی در دسترس نمی‌باشد.");
                        break;
                    case error.TIMEOUT:
                        console.error("درخواست موقعیت مکانی به پایان رسید.");
                        // alert("درخواست موقعیت مکانی زمان‌بر بود. لطفاً دوباره تلاش کنید.");
                        break;
                    default:
                        console.error("خطای ناشناخته‌ای رخ داده است.");
                        // alert("مشکلی در دریافت موقعیت مکانی شما وجود دارد.");
                        break;
                }
            },
            {
                enableHighAccuracy: true, // دقت بالاتر برای موقعیت
                maximumAge: 10000,       // ذخیره موقعیت برای ۱۰ ثانیه
                timeout: 5000            // محدودیت زمانی ۵ ثانیه
            }
        );*/
    } else {
        // alert("مرورگر شما از موقعیت‌یاب پشتیبانی نمی‌کند.");
        console.error("مرورگر شما از موقعیت‌یاب پشتیبانی نمی‌کند.");
    }
}

setInterval(() => {
    updateDriverLocation();
    },60000);
