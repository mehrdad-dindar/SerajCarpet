<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Set Your Location</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @livewireStyles
    <wireui:scripts/>
    <link rel="stylesheet" href="{{ asset("css/neshansdk-mapbox.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css/driver.css") }}"/>
    {{--    <x-livewire-alert::scripts />--}}
</head>
<body>
{{$slot}}
@livewireScripts
{{--<x-livewire-alert::scripts />--}}
<script src="{{ asset("js/neshansdk-mapbox.js") }}"></script>
<script src="{{ asset("js/driver.js.iife.js") }}"></script>
<script src="{{ asset("js/sweetalert2.all.min.js") }}"></script>
<x-livewire-alert::scripts />
@stack('scripts')
</body>
</html>
