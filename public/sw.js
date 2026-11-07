// Service Worker for Your CPA Expert Staff & Client Portal PWA
const CACHE_NAME = 'cpa-portal-v2';
const STATIC_ASSETS = [
  '/manifest.json',
  '/frontend/theme1/css/bootstrap.min.css',
  '/frontend/theme1/css/style.css'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(STATIC_ASSETS).catch(() => {});
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
      );
    })
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;
  event.respondWith(
    fetch(event.request).catch(() => caches.match(event.request))
  );
});

// Push & Native Mobile System Drawer Notifications
self.addEventListener('push', function(event) {
  let data = {};
  if (event.data) {
    try { data = event.data.json(); } catch(e) { data = { body: event.data.text() }; }
  }
  const title = data.title || 'Your CPA Expert';
  const options = {
    body: data.body || 'New encrypted message in your workspace.',
    icon: data.icon || '/assets/images/logo/logo.png',
    badge: '/assets/images/logo/logo.png',
    vibrate: [250, 100, 250],
    data: { url: data.url || '/' }
  };
  event.waitUntil(self.registration.showNotification(title, options));
});

// Notification Click Handler (Brings browser window to foreground)
self.addEventListener('notificationclick', function(event) {
  event.notification.close();
  const urlToOpen = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/';
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
      for (let i = 0; i < clientList.length; i++) {
        const client = clientList[i];
        if (client.url && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(urlToOpen);
      }
    })
  );
});

