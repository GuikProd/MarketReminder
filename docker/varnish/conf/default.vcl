vcl 4.0;

import std;

# Used for Blackfire profiling
acl profile {
    "172.18.0.1";
}

# Default port
backend default {
    .host = "172.18.0.1";
    .port = "8080";
    .probe = {
        .url = "/fr/";
        .timeout = 1s;
        .interval = 5s;
        .window = 5;
        .threshold = 3;
    }
}

sub vcl_recv {
    if (req.http.X-Forwarded-Proto == "https") {
        set req.http.X-Forwarded-Port = "443";
    } else {
        set req.http.X-Forwarded-Port = "8080";
    }

    if (req.http.Cookie) {
        set req.http.Cookie = ";" + req.http.Cookie;
        set req.http.Cookie = regsuball(req.http.Cookie, "; +", ";");
        set req.http.Cookie = regsuball(req.http.Cookie, ";(PHPSESSID)=", "; \1=");
        set req.http.Cookie = regsuball(req.http.Cookie, ";[^ ][^;]*", "");
        set req.http.Cookie = regsuball(req.http.Cookie, "^[; ]+|[; ]+$", "");

        if (req.http.Cookie == "") {
            unset req.http.Cookie;
        }
    }

    set req.http.Surrogate-Capability = "abc=ESI/1.0";

    if (req.http.X-Blackfire-Query && client.ip ~ profile) {
        if (req.esi_level > 0) {
            unset req.http.X-Blackfire-Query;
        } else {
            return (pass);
        }
    }
}

sub vcl_backend_response {
    if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
        unset beresp.http.Surrogate-Control;
        set beresp.do_esi = true;
    }
}
