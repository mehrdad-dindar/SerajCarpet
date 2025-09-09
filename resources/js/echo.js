import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

Echo.channel('calls')
    .listen('IncomingCall', (e) => {
        // نمایش نوتیفیکیشن
        alert(`تماس ورودی از مشتری: ${e.customer.name}`);
        // یا استفاده از filament-notifications
        window.Livewire.emit('openNotification', {
            title: 'تماس جدید',
            description: `از ${e.customer.name} - کلیک برای هدایت`,
            action: {
                label: 'مشاهده',
                url: `/admin/customers/${e.customer.id}`
            }
        });
    });
