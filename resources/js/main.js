import "../css/client/client.css";
// import Alpine from 'alpinejs'
//
// window.Alpine = Alpine
//
// Alpine.start()

import '@neshan-maps-platform/mapbox-gl/dist/NeshanMapboxGl.css';
import nmp_mapboxgl from '@neshan-maps-platform/mapbox-gl';

const map = new nmp_mapboxgl.Map({
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
