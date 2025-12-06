const CACHE_NAME = 'iafinance-v1';
const ASSETS_TO_CACHE = [
    '/',
    '/index.php',
    '/assets/icons/icon-192.png',
    '/assets/icons/icon-512.png',
    'https://cdn.tailwindcss.com',
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
    'https://cdn.jsdelivr.net/npm/chart.js'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(ASSETS_TO_CACHE))
    );
});

self.addEventListener('fetch', (event) => {
    // Para APIs e PHP Pages: Network First (tenta rede, se falhar, tenta cache se existir, ou offline page)
    if (event.request.method === 'GET') {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    const resClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        // Opcional: Cachear páginas visitadas. Cuidado com dados sensíveis.
                        // cache.put(event.request, resClone);
                    });
                    return response;
                })
                .catch(() => {
                    return caches.match(event.request);
                })
        );
    } else {
        // POST/PUT/DELETE sempre Network Only
        event.respondWith(fetch(event.request));
    }
});
