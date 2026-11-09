// Service Worker for Your CPA Expert Universal PWA (Admin, Client & Staff)
const CACHE_NAME = 'cpa-portal-v3';
const STATIC_ASSETS = [
  '/manifest.json',
  '/admin-manifest.json',
  '/client-manifest.json',
  '/staff-manifest.json',
  '/upload/settings/logo.png',
  '/frontend/theme1/css/bootstrap.min.css',
  '/frontend/theme1/css/style.css',
  '/backend/assets/dist/css/bootstrap.min.css'
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
  const url = new URL(event.request.url);

  // Skip dynamic endpoints, API polling, WebRTC signaling, security gates
  if (
    url.pathname.includes('/poll') ||
    url.pathname.includes('/activity-poll') ||
    url.pathname.includes('/portal/call') ||
    url.pathname.includes('/security') ||
    url.pathname.includes('/conversation') ||
    url.pathname.includes('/webrtc') ||
    url.searchParams.has('expired') ||
    url.searchParams.has('t')
  ) {
    return;
  }

  // Network first with fallback to cache for HTML pages; Cache first for static assets
  const isStatic = url.pathname.match(/\.(css|js|png|jpg|jpeg|svg|webp|woff|woff2|ttf|ico)$/i);
  if (isStatic) {
    event.respondWith(
      caches.match(event.request).then((cached) => {
        return cached || fetch(event.request).then((response) => {
          if (response && response.status === 200) {
            const clone = response.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
          }
          return response;
        });
      }).catch(() => fetch(event.request))
    );
  } else {
    event.respondWith(
      fetch(event.request).catch(() => caches.match(event.request))
    );
  }
});

// Push & Native Mobile System Drawer Notifications
self.addEventListener('push', function(event) {
  let data = {};
  if (event.data) {
    try { data = event.data.json(); } catch(e) { data = { body: event.data.text() }; }
  }
  const title = data.title || 'Your CPA Expert';
  const isCall = title.toLowerCase().includes('call') || (data.type && data.type.includes('call'));
  const options = {
    body: data.body || 'New encrypted message in your workspace.',
    icon: data.icon || '/upload/settings/logo.png',
    badge: '/upload/settings/logo.png',
    vibrate: isCall ? [500, 250, 500, 250, 1000, 500, 500, 250, 500, 250, 1000] : [200, 100, 200],
    tag: isCall ? 'incoming-call' : ('cpa-msg-' + Date.now()),
    renotify: true,
    requireInteraction: isCall,
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
          if ('navigate' in client && urlToOpen !== '/') {
            client.navigate(urlToOpen);
          }
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(urlToOpen);
      }
    })
  );
});
