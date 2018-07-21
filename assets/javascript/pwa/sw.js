let cacheName = 'app-shell';
let filesToCache = [
    '/favicon.ico',
    '/icons/android-icon-36x36.png',
    '/icons/android-icon-48x48.png',
    '/icons/android-icon-72x72.png',
    '/icons/android-icon-96x96.png',
    '/icons/android-icon-144x144.png',
    '/icons/android-icon-192x192.png',
    '/icons/apple-icon-57x57.png',
    '/icons/apple-icon-60x60.png',
    '/icons/apple-icon-72x72.png',
    '/icons/apple-icon-76x76.png',
    '/icons/apple-icon-114x114.png',
    '/icons/apple-icon-120x120.png',
    '/icons/apple-icon-144x144.png',
    '/icons/apple-icon-152x152.png',
    '/icons/apple-icon-180x180.png',
    '/icons/apple-icon-precomposed.png',
    '/icons/favicon-16x16.png',
    '/icons/favicon-32x32.png',
    '/icons/favicon-96x96.png',
    '/icons/ms-icon-70x70.png',
    '/icons/ms-icon-144x144.png',
    '/icons/ms-icon-150x150.png',
    '/icons/ms-icon-310x310.png',
    '/pwa/_icons/48.png',
    '/pwa/_icons/72.png',
    '/pwa/_icons/96.png',
    '/pwa/_icons/144.png',
    '/pwa/_icons/168.png',
    '/pwa/_icons/192.png',
    '/pwa/_icons/256.png',
    '/pwa/_icons/384.png',
    '/pwa/_icons/512.png',
    '/build/app-shell.css',
    '/build/manifest.json',
    '/build/navigation.js',
    // '/build/core.css',
    // '/build/register.css',
    // '/build/form.js',
    // '/build/sw.js',
    // '/build/serviceWorker.js',
    'https://fonts.googleapis.com/css?family=Roboto',
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
