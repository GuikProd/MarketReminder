vcl 4.0;

import std;

# Default port
backend default {
    .host = "172.19.0.1";
    .port = "8080";
}

# Production
# backend default {
#     .host = "localhost";
#     .port = "8080";
# }

# Used in order to check the ports used.
sub vcl_recv {
    if (req.http.X-Forwarded-Proto == "https" ) {
        set req.http.X-Forwarded-Port = "443";
    } else {
        set req.http.X-Forwarded-Port = "80";
    }
}

sub vcl_backend_response {

}

sub vcl_deliver {

}