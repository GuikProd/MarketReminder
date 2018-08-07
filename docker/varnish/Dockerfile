FROM alpine:3.6 as base

RUN apk add --no-cache varnish

COPY conf/default.vcl /etc/varnish/default.vcl
COPY ./start.sh /usr/local/bin/docker-app-start

RUN chmod +x /usr/local/bin/docker-app-start

EXPOSE 80

CMD ["docker-app-start"]

# Production build
FROM base as production

COPY conf/production.vcl /etc/varnish/default.vcl
