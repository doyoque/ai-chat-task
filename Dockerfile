#===================================================
# Composer
#===================================================
FROM composer:2.4.4 AS vendor

WORKDIR /app

COPY database/ database/

COPY composer.json composer.json

COPY composer.lock composer.lock

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev \
    --prefer-dist \
    --ignore-platform-reqs

COPY . .

RUN composer dump-autoload

FROM php:8.1-fpm-alpine

LABEL maintainer="Abiyoga Bayu Primadi"

#--------------------------------------------------
# Timezone
#--------------------------------------------------
ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

WORKDIR /app

#--------------------------------------------------
# APP_ROOT, APP_USER
#--------------------------------------------------
ENV APP_ROOT=/app \
    APP_USER=www-data

RUN sed -ri 's/^www-data:x:82:82:/www-data:x:1000:50:/' /etc/passwd

RUN set -ex \
    && chown -R "${APP_USER}:${APP_USER}" "${APP_ROOT}"

#--------------------------------------------------
# Copy vendor build
#--------------------------------------------------
COPY --from=vendor /app/vendor ./vendor/

COPY . .

#--------------------------------------------------
# PHP
#--------------------------------------------------
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=1 \
    PHP_OPCACHE_SAVE_COMMENTS=1

RUN set -ex \
    && apk add --no-cache --virtual .php-deps \
    libzip-dev \
    icu-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    git \
    supervisor \
    && docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) \
    intl \
    opcache \
    pdo_mysql \
    gd \
    zip \
    xml \
    pcntl

#--------------------------------------------------
# Copy horizon and configure supervisor
#--------------------------------------------------
RUN mkdir -p /var/log/supervisor

RUN mkdir -p /etc/supervisor.d/

COPY ./docker/supervisor/supervisord.conf /etc/supervisord.conf

#--------------------------------------------------
# Copy PHP configurations
#--------------------------------------------------
COPY ./docker/php/*.ini /usr/local/etc/php/conf.d/

COPY entrypoint.sh /usr/local/bin/

RUN chmod u+x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
