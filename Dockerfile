# Development build
FROM php:fpm-alpine as base

ARG WORKFOLDER

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV WORKPATH ${WORKFOLDER}

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev postgresql-dev gnupg graphviz make autoconf git zlib-dev curl chromium go \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install zip intl pdo_pgsql pdo_mysql opcache json pdo_pgsql pgsql mysqli \
    && pecl install apcu redis \
    && docker-php-ext-enable apcu mysqli redis

COPY docker/php/conf/php.ini /usr/local/etc/php/php.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Blackfire (Docker approach) & Blackfire Player
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/alpine/amd64/$version \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp \
    && mv /tmp/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && mkdir -p /tmp/blackfire \
    && curl -A "Docker" -L https://blackfire.io/api/v1/releases/client/linux_static/amd64 | tar zxp -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire /usr/bin/blackfire \
    && rm -Rf /tmp/blackfire

# PHP-CS-FIXER & Deptrac
RUN wget http://cs.sensiolabs.org/download/php-cs-fixer-v2.phar -O php-cs-fixer \
    && chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer \
    && curl -LS http://get.sensiolabs.de/deptrac.phar -o deptrac.phar \
    && chmod +x deptrac.phar \
    && mv deptrac.phar /usr/local/bin/deptrac

RUN mkdir -p ${WORKPATH} \
    && chown -R www-data /tmp/

WORKDIR ${WORKPATH}

COPY --chown=www-data:www-data . ./

EXPOSE 9000

CMD ["php-fpm"]

## Used for build the frontend assets
FROM node:8 as front_assets

COPY . ./

RUN yarn install \
    && yarn build \
    && rm -rf ./node_modules

# Production build
FROM base as production

COPY --from=front_assets public/build ./public/build

COPY docker/php/conf/production/php.ini /usr/local/etc/php/php.ini

RUN rm -rf /usr/local/bin/deptrac \
    && rm -rf /usr/local/bin/php-cs-fixer

