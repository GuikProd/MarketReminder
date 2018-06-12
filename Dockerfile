FROM php:fpm-alpine

ENV WORKPATH "/var/www/marketReminder"

RUN set -xe \
          	&& apk add --no-cache --virtual .build-deps \
          		$PHPIZE_DEPS \
          		icu-dev \
          		postgresql-dev \
          		zlib-dev \
          		gnupg \
          		graphviz \
          		make \
          		autoconf \
          		git \
          	&& docker-php-ext-install \
          		intl \
          		pdo_pgsql \
          		zip \
          	&& pecl install apcu \
                   && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
                   && docker-php-ext-install pdo_mysql opcache json pdo_pgsql pgsql mysqli \
                   && docker-php-ext-enable apcu mysqli \
          	&& runDeps="$( \
          		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
          			| tr ',' '\n' \
          			| sort -u \
          			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
          	)" \
          	&& apk add --no-cache --virtual .php-phpexts-rundeps $runDeps \
          	&& apk del .build-deps

COPY docker/php/conf/php.ini /usr/local/etc/php/php.ini

# Composer
ENV COMPOSER_ALLOW_SUPERUSER 1
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
    && rm -Rf /tmp/blackfire \
    && curl -OLsS http://get.blackfire.io/blackfire-player.phar \
    && chmod +x blackfire-player.phar \
    && mv blackfire-player.phar /usr/local/bin/blackfire-player

# PHP-CS-FIXER & Deptrac
RUN wget http://cs.sensiolabs.org/download/php-cs-fixer-v2.phar -O php-cs-fixer \
    && chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer \
    && curl -LS http://get.sensiolabs.de/deptrac.phar -o deptrac.phar \
    && chmod +x deptrac.phar \
    && mv deptrac.phar /usr/local/bin/deptrac

RUN mkdir -p ${WORKPATH}

RUN rm -rf ${WORKDIR}/vendor \
    && ls -l ${WORKDIR}

RUN mkdir -p \
		${WORKDIR}/var/cache \
		${WORKDIR}/var/logs \
		${WORKDIR}/var/sessions \
	&& chown -R www-data ${WORKDIR}/var \
	&& chown -R www-data /tmp/

RUN chown www-data:www-data -R ${WORKPATH}

WORKDIR ${WORKPATH}

COPY . ./

EXPOSE 9000

CMD ["php-fpm"]
