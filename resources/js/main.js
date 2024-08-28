import "../css/client/client.css";
// import Alpine from 'alpinejs'
//
// window.Alpine = Alpine
//
// Alpine.start()

import '@neshan-maps-platform/mapbox-gl/dist/NeshanMapboxGl.css';
import nmp_mapboxgl from '@neshan-maps-platform/mapbox-gl';

import L from "leaflet";
import 'leaflet/dist/leaflet.css';

import { driver } from "driver.js";
import "driver.js/dist/driver.css";

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
