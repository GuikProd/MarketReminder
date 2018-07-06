let cacheName = 'app-shell';
let filesToCache = [
    '/build/theme.css',
    '/build/core.css',
    '/build/registration.css',
    '/build/form.js',
    '/build/sw.js',
    '/build/serviceWorker.js',
    'https://unpkg.com/material-components-web@latest/dist/material-components-web.css',
    'https://unpkg.com/material-components-web@latest/dist/material-components-web.js',
    'https://fonts.googleapis.com/icon?family=Material+Icons'
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(cacheName).then(function (cache) {
            return cache.addAll(filesToCache);
        })
    )
});

self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.open(cacheName).then(function(cache) {
            return cache.match(event.request).then(function(response) {
                let fetchPromise = fetch(event.request).then(function(networkResponse) {
                    cache.put(event.request, networkResponse.clone());
                    return networkResponse;
                });
                return response || fetchPromise;
            })
        })
    );
});
