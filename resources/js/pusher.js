import Echo from 'laravel-echo';



import Pusher from 'pusher-js';
window.Pusher = Pusher;
Pusher.logToConsole = true; // برای دیباگ

window.Echo = new Echo({

    broadcaster: 'pusher',

    key: 'f3b6ab25958430b4a957',

    cluster: 'ap2',

    forceTLS: true

});
