<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Set Your Location</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link href="{{asset("css/mapbox-gl.css")}}" rel="stylesheet"/>
    <script src="{{asset("js/mapbox-gl.js")}}"></script>
</head>
<body class="py-16 bg-blue-950">
<div class="container m-auto px-6 text-gray-500 md:px-12 xl:px-0">
    <form class="mx-auto w-full" method="post" action="{{ route('create.address') }}">
        @csrf
        <div
            class="border border-gray-100 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 p-6 shadow-2xl shadow-gray-600/10 dark:shadow-none sm:px-12 lg:px-8">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">با استفاده از نقشه محل تحویل را انتخاب
                کنید <span class="text-xs text-red-500">(الزامی)</span></h3>
            <div id="map" class="w-100 min-h-80 rounded-lg mb-6"></div>
            <pre id="coordinates" class="hidden"></pre>
            <input type="hidden" name="latitude" id="latitude" value="">
            <input type="hidden" name="longitude" id="longitude" value="">
            <input type="hidden" name="id" value="{{$hashid}}">
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="state"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">State</label>
                    <input type="text" id="state" name="state"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-0"
                           required readonly/>
                </div>
                <div>
                    <label for="city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">City</label>
                    <input type="text" id="city" name="city"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white focus:ring-0"
                           required readonly/>
                </div>
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-6">
                <div class="md:col-span-5">
                    <label for="address"
                           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                    <input type="text" id="address" name="address"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white focus:ring-0"
                           required readonly/>
                </div>
                <div>
                    <label for="no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No.</label>
                    <input type="text" id="no" name="no"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"/>
                </div>
                <div class="md:col-span-6">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your
                        message</label>
                    <textarea id="message" rows="4" name="note"
                              class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                              placeholder="Write your message here..."></textarea>
                </div>

            </div>
            <button type="submit"
                    class="text-white bg-[rgb(245_158_11)] hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                Submit
            </button>
        </div>
    </form>
</div>
<script src="{{asset("js/jquery-3.7.1.min.js")}}" type="text/javascript"></script>
<script>
    mapboxgl.accessToken = '{{env("MAPBOX_ACCESS_TOKEN")}}';
    const coordinates = document.getElementById('coordinates');
    mapboxgl.setRTLTextPlugin(
        'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.2.3/mapbox-gl-rtl-text.js',
        null,
        true // Lazy load the plugin
    );
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/navigation-day-v1',
        center: [51.38971730879854, 35.70368539428047],
        zoom: 9,
        localIdeographFontFamily: 'inherit',
        rtl: true
    });

    var marker = new mapboxgl.Marker({
        draggable: true,
        rotation: 22,
        color: '#40E0D0' // color it turquoise blue
    });

    function add_marker(event) {
        var coordinates = event.lngLat;
        var latitude = coordinates.lat;
        var longitude = coordinates.lng;
        $("#longitude").val(longitude)
        $("#latitude").val(latitude)
        marker.setLngLat(coordinates).addTo(map);


        $.ajax({
            type: "POST",
            url: "{{route("getFullAddress")}}",
            cache: false,
            data: {
                "_token": "{{ csrf_token() }}",
                "latitude": latitude,
                "longitude": longitude
            },
            error: function (xhr) {
                alert(xhr.responseText);
            },
            success: function (data) {
                // Check the output of ajax call on firebug console
                //console.log(data);
                //alert(data);
                $('#address').val(data['formatted_address']);
                $('#state').val(data['state']);
                $('#city').val(data['city']);
            }
        });

    }

    map.on('click', add_marker);
</script>
</body>
</html>
