import Alpine from 'alpinejs'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
})

import './alpine-stores'
import './alpine-data'

window.Echo.channel('orders')
    .listen('.order.updated', (e) => {
        if (e.stats) {
            window.Alpine.$store.admin.setStats(e.stats)
        }
        if (e.order) {
            window.Alpine.$store.app.setNotifications(window.Alpine.$store.app.activeOrders)
        }
    })
    .listen('.service.requested', (e) => {
        window.Alpine.$store.app.activeServiceRequest = e.request
    })

window.Alpine = Alpine
Alpine.start()
