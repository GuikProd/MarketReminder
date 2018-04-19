self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open('static assets')
            .then(function(cache) {
                cache.add('/build/core.css');
                cache.add('/build/registration.css');
                cache.add('/build/serviceWorker.js');
                cache.add('/build/form.js');
            })
    );
});

self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request)
              .then(function (response) {
                  if (response) {
                      return response;
                  }

                  return fetch(event.request);
              })
    );
});
