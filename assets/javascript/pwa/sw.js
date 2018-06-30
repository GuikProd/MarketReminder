var cacheName = 'app-shell';
var filesToCache = [
    '/build/core.css',
    '/build/registration.css',
    '/build/form.js',
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
