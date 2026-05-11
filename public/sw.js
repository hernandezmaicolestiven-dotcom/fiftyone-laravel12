// FiftyOne Service Worker — PWA offline support
const CACHE_NAME = 'fiftyone-v2'; // Cambiado para forzar actualización
const STATIC_ASSETS = ['/', '/catalogo'];

// Instalar: cachear assets estáticos
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
  );
  self.skipWaiting();
});

// Activar: limpiar caches viejos
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
    )
  );
  self.clients.claim();
});

// Fetch: network-first, fallback a cache
self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;
  if (event.request.url.includes('/admin')) return; // No cachear el panel admin
  if (event.request.url.includes('/api/')) return; // NO CACHEAR API
  if (event.request.url.includes('/orders')) return; // NO CACHEAR ÓRDENES
  if (event.request.url.includes('/wompi')) return; // NO CACHEAR WOMPI

  event.respondWith(
    fetch(event.request)
      .then((response) => {
        const clone = response.clone();
        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
        return response;
      })
      .catch(() => caches.match(event.request))
  );
});
