// Dearly — Vintage Digital Pen-Pal Sanctuary Service Worker
self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

// Handle push notification received from server (VAPID / Web Push)
self.addEventListener('push', (event) => {
    let data = {
        title: 'Dearly Post Office',
        body: 'A vintage letter has arrived in your mailbox.',
        icon: '/favicon-32x32.png',
        badge: '/favicon-16x16.png',
        url: '/letters/received'
    };

    if (event.data) {
        try {
            data = Object.assign(data, event.data.json());
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body,
        icon: data.icon || '/favicon-32x32.png',
        badge: data.badge || '/favicon-16x16.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/letters/received'
        },
        actions: [
            { action: 'open', title: 'Open Mailbox' },
            { action: 'close', title: 'Dismiss' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// Handle click on device notification
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'close') {
        return;
    }

    const targetUrl = event.notification.data && event.notification.data.url 
        ? event.notification.data.url 
        : '/letters/received';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url.includes(self.location.origin) && 'focus' in client) {
                    client.navigate(targetUrl);
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
