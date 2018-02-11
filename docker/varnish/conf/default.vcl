vcl 4.0;

import std;

# Default port
backend default {
    .host = "nginx";
    .port = "80";
    .probe = {
        .url = "/";
    }
}

# List of hosts authorized to purge request/
acl local {
  "localhost";
  "php-fpm";
}

# Used in order to check the ports used.
sub vcl_recv {
    if (req.http.X-Forwarded-Proto == "https" ) {
        set req.http.X-Forwarded-Port = "443";
    } else {
        set req.http.X-Forwarded-Port = "80";
    }
}

sub vcl_deliver {
  # Don't send cache tags related headers to the client
  unset resp.http.url;
  # Uncomment the following line to NOT send the "Cache-Tags" header to the client (prevent using CloudFlare cache tags)
  #unset resp.http.Cache-Tags;
}
