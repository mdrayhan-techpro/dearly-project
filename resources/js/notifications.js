// Dearly — Device & Postal Notification Center
export function initNotificationCenter() {
    // 1. Register Service Worker for Device Web Push
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js', { scope: '/' })
            .then((registration) => {
                // Service worker registered successfully
            })
            .catch((error) => {
                console.debug('Dearly Service Worker registration skipped:', error);
            });
    }

    // 2. Synthesized Vintage Postal Bell Chime (Web Audio API)
    function playPostalChime() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            
            // Primary bell chime frequency
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(880, ctx.currentTime); // A5 note

            // Overtone harmonic for vintage bronze bell resonance
            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(1760, ctx.currentTime); // A6 overtone

            gain1.gain.setValueAtTime(0.2, ctx.currentTime);
            gain1.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 1.2);

            gain2.gain.setValueAtTime(0.08, ctx.currentTime);
            gain2.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.8);

            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);

            osc1.start();
            osc2.start();
            osc1.stop(ctx.currentTime + 1.2);
            osc2.stop(ctx.currentTime + 1.2);
        } catch (e) {
            // Audio context not allowed until user interaction
        }
    }

    // 3. Trigger native device notification
    function triggerDeviceNotification(title, message, actionUrl) {
        if (!('Notification' in window)) return;
        if (Notification.permission !== 'granted') return;

        playPostalChime();

        const options = {
            body: message,
            icon: '/favicon-32x32.png',
            badge: '/favicon-16x16.png',
            data: { url: actionUrl || '/letters/received' },
            vibrate: [150, 80, 150]
        };

        if (navigator.serviceWorker && navigator.serviceWorker.controller) {
            navigator.serviceWorker.ready.then((reg) => {
                reg.showNotification(title, options);
            });
        } else {
            const notif = new Notification(title, options);
            notif.onclick = function() {
                window.focus();
                if (actionUrl) {
                    window.location.href = actionUrl;
                }
                notif.close();
            };
        }
    }

    // 4. Register Alpine Component for Notification Dropdown Center
    if (window.Alpine) {
        window.Alpine.data('notificationCenter', (initialUnread = 0) => ({
            isOpen: false,
            unreadCount: initialUnread,
            notifications: [],
            loading: false,
            lastKnownId: null,
            devicePermission: ('Notification' in window) ? Notification.permission : 'unsupported',
            hasRequestedPermission: false,

            init() {
                this.fetchFeed(false);
                // Periodic polling for realtime letter dispatch notifications (every 40 seconds)
                setInterval(() => {
                    this.fetchFeed(true);
                }, 40000);
            },

            async fetchFeed(isBackground = false) {
                if (!isBackground) this.loading = true;
                try {
                    const res = await fetch('/notifications/feed', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        const previousCount = this.unreadCount;
                        this.unreadCount = data.unread_count;
                        
                        // Check if new unread notification arrived
                        if (isBackground && data.unread_count > previousCount && data.notifications.length > 0) {
                            const latest = data.notifications[0];
                            if (!latest.is_read && latest.id !== this.lastKnownId) {
                                triggerDeviceNotification(latest.title, latest.message, latest.action_url);
                            }
                        }

                        if (data.notifications.length > 0) {
                            this.lastKnownId = data.notifications[0].id;
                        }
                        this.notifications = data.notifications;
                    }
                } catch (e) {
                    console.debug('Notification feed fetch skipped:', e);
                } finally {
                    if (!isBackground) this.loading = false;
                }
            },

            async requestDeviceAlerts() {
                if (!('Notification' in window)) {
                    alert('Your browser does not support device notifications.');
                    return;
                }

                try {
                    const permission = await Notification.requestPermission();
                    this.devicePermission = permission;
                    if (permission === 'granted') {
                        triggerDeviceNotification(
                            '⚜️ Postal Chimes Activated',
                            'Your device will now receive vintage alerts when letters arrive in your mailbox.',
                            '/letters/received'
                        );
                    }
                } catch (err) {
                    console.error('Error requesting notification permission:', err);
                }
            },

            async markAsRead(id, actionUrl) {
                try {
                    const token = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
                    await fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        }
                    });

                    // Update local state
                    const notif = this.notifications.find(n => n.id === id);
                    if (notif && !notif.is_read) {
                        notif.is_read = true;
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }

                    if (actionUrl) {
                        window.location.href = actionUrl;
                    }
                } catch (e) {
                    if (actionUrl) window.location.href = actionUrl;
                }
            },

            async markAllAsRead() {
                try {
                    const token = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
                    const res = await fetch('/notifications/read-all', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        }
                    });
                    if (res.ok) {
                        this.unreadCount = 0;
                        this.notifications.forEach(n => n.is_read = true);
                    }
                } catch (e) {
                    console.error('Failed to mark all as read:', e);
                }
            }
        }));
    }
}
