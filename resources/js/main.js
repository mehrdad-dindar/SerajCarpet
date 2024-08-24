import "../css/client/client.css";
// import Alpine from 'alpinejs'
//
// window.Alpine = Alpine
//
// Alpine.start()

import '@neshan-maps-platform/mapbox-gl/dist/NeshanMapboxGl.css';
import nmp_mapboxgl from '@neshan-maps-platform/mapbox-gl';

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
    mapKey: 'web.9b720353743c4534a41a4a22df831720',
    poi: false,
    traffic: true,
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

document.querySelector('[wire\\:click="submit"]').addEventListener('click', function() {
    const center = map.getCenter();
    marker.setLngLat(center);

    const centerCoordinates = {
        lng: center.lng.toFixed(12),
        lat: center.lat.toFixed(12)
    };
    console.log(centerCoordinates.lat)
    Livewire.dispatch('updateLocation', {latitude: centerCoordinates.lat,longitude: centerCoordinates.lng});
});

import { driver } from "driver.js";
import "driver.js/dist/driver.css";

const driverObj = driver({
    nextBtnText: 'بعدی',
    prevBtnText: 'قبلی',
    doneBtnText: 'حله',
    showProgress: true,
    steps: [
        { popover: { title: 'سلام 😃👋', description: 'به صفحه ثبت لوکیشن قالی‌شویی سراج خوش آمدید' } },
        { element: 'button.mapboxgl-ctrl-geolocate', popover: { title: 'مکان شما', description: 'با استفاده از این دکمه میتوانید مکان خود را روی نقشه پیدا کنید' } },
        { element: '#button1', popover: { title: 'ثبت موقعیت مکانی', description: 'پس از اطمینان از صحیح بودن موقعیت با استفاده از این دکمه نسبت به ثبت اقدام کنید' } },
    ]
});

driverObj.drive();

let switchers = document.querySelectorAll(".switcher");
if (
  localStorage.getItem("color-theme") === "dark" ||
  (!("color-theme" in localStorage) &&
    window.matchMedia("(prefers-color-scheme: dark)").matches)
) {
  document.documentElement.classList.add("dark");
} else {
  document.documentElement.classList.remove("dark");
}

switchers.forEach((switcher) => {
  switcher.addEventListener("click", function () {
    if (localStorage.getItem("color-theme")) {
      if (localStorage.getItem("color-theme") === "light") {
        document.documentElement.classList.add("dark");
        localStorage.setItem("color-theme", "dark");
      } else {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("color-theme", "light");
      }
    } else {
      if (document.documentElement.classList.contains("dark")) {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("color-theme", "light");
      } else {
        document.documentElement.classList.add("dark");
        localStorage.setItem("color-theme", "dark");
      }
    }
  });
});

/*import Swiper from 'swiper/bundle';
import { Pagination } from 'swiper/modules';
import 'swiper/css/bundle';
import 'swiper/css/pagination';
import 'swiper/css/effect-cards'

const swiper = new Swiper('.proofSlides', {
    effect: "cube",
    cubeEffect : {
        slideShadows: false,
        shadow: false,
        shadowOffset: 20,
        shadowScale: 0.94,
    },
    loop: true,
    // autoplay : {
    //     delay: 3000,
    //     duration : 500
    // },
    grabCursor: true,
    modules: [Pagination],
    centeredSlides: true,
    pagination: {
        el: '.swiper-pagination',
    }
});*/
