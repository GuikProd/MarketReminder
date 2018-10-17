FROM nginx:alpine as development

ARG WORKFOLDER

ENV WORKPATH ${WORKFOLDER}

COPY conf/core/nginx.conf /etc/nginx/nginx.conf

COPY conf/development/marketReminder.conf /etc/nginx/conf.d/default.conf

COPY . ${WORKPATH}

FROM development as production

COPY conf/production/marketReminder.conf /etc/nginx/conf.d/default.conf
