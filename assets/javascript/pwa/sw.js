self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open('static assets')
            .then(function(cache) {
                cache.add('/build/core.css');
                cache.add('/build/registration.css');
                cache.add('/build/serviceWorker.js');
                cache.add('/build/form.js');
                cache.add('https://unpkg.com/material-components-web@latest/dist/material-components-web.css');
                cache.add('https://unpkg.com/material-components-web@latest/dist/material-components-web.js');
                cache.add('https://fonts.googleapis.com/icon?family=Material+Icons');
            })
    );
});
