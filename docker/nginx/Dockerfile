# Development build
FROM nginx:alpine as base

ARG WORKFOLDER

ENV WORKPATH ${WORKFOLDER}

COPY conf/core/nginx.conf /etc/nginx/nginx.conf

COPY conf/development/marketReminder.conf /etc/nginx/conf.d/default.conf

RUN mkdir -p ${WORKPATH}

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]

# Production build
FROM base as production

COPY conf/production/marketReminder.conf /etc/nginx/conf.d/default.conf
