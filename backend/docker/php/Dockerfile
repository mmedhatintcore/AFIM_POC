# Multi-stage build.
#
#   base    -> OS packages + PHP extensions, shared by every PHP stage so the
#              expensive extension layer is built once and reused.
#   vendor  -> composer dependencies (cached on composer.json/composer.lock)
#   assets  -> node + vite build (npm layer cached on package.json)
#   builder -> assembles source + vendor + optimized autoloader
#   runtime -> final image. No node, no npm, no composer, no build toolchain.
#
# Previously this was a single stage: node and the whole build toolchain shipped
# to production and every source change re-ran composer install and npm install.
#
# IMPORTANT: the composer stages are built FROM base, not FROM composer:2/latest.
# The composer image ships PHP 8.5 without pcntl/gd/intl, so composer would
# resolve platform requirements against the wrong PHP and fail on the lock file.

########################  base  ########################
FROM php:8.3-fpm AS base

WORKDIR /var/www

# Runtime packages only. node/npm/git/unzip live in the build stages.
RUN apt-get update && apt-get install -y --no-install-recommends \
      ca-certificates curl cron supervisor procps \
      netcat-traditional default-mysql-client \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# mlocati's installer pulls precompiled extensions when available and handles
# system deps automatically, instead of compiling intl/gd from source.
# Version is PINNED on purpose: with .../releases/latest/... any upstream release
# changes this layer, and rebuilding it costs ~45 min of extension compiling on
# this host. Bump deliberately, not by accident.
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/download/2.11.12/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql zip exif pcntl intl sockets gd redis

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

########################  composer-base  ########################
FROM base AS composer-base

RUN apt-get update && apt-get install -y --no-install-recommends git unzip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

########################  vendor  ########################
FROM composer-base AS vendor

WORKDIR /var/www
# composer.lock* is a glob on purpose: Pfandox-backend has no committed lock
# file, so composer resolves fresh there. Committing a lock would make that
# build both faster and reproducible.
COPY composer.json composer.lock* ./

RUN --mount=type=cache,target=/tmp/composer-cache,sharing=locked \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    composer install \
      --no-dev \
      --no-scripts \
      --no-autoloader \
      --prefer-dist \
      --no-interaction \
      --no-progress

########################  assets  ########################
FROM node:20-alpine AS assets

WORKDIR /app
COPY package.json package-lock.json* ./
RUN --mount=type=cache,target=/root/.npm,sharing=locked \
    npm install --no-audit --no-fund

# Copy the whole source: the vite/tailwind configs in these repos scan paths
# that vary per project, so a narrower copy would silently drop styles.
COPY . .
COPY --from=vendor /var/www/vendor ./vendor
RUN npm run build

########################  builder  ########################
FROM composer-base AS builder

COPY --from=vendor /var/www/vendor /var/www/vendor
COPY . /var/www
COPY --from=assets /app/public/build /var/www/public/build

# Classmap needs the full source, so it runs here rather than in the vendor stage.
RUN composer dump-autoload --optimize --no-dev --no-scripts --no-interaction

########################  runtime  ########################
FROM base AS runtime

COPY --from=builder /var/www /var/www

COPY ./docker/php/wait-for-it.sh /usr/local/bin/wait-for-it.sh
COPY ./docker/php/init.sh /usr/local/bin/init.sh
COPY ./docker/php/supervisord/supervisord.conf /etc/supervisor/
COPY ./docker/php/supervisord/scripts/* /etc/supervisor/conf.d/
COPY ./docker/php/crons/* /etc/cron.d/

# Strip CRLF from anything that gets executed, then register the crontab.
RUN for f in /usr/local/bin/wait-for-it.sh /usr/local/bin/init.sh /etc/cron.d/*; do \
      tr -d '\r' < "$f" > "$f.tmp" && mv "$f.tmp" "$f"; \
    done \
 && chmod +x /usr/local/bin/wait-for-it.sh /usr/local/bin/init.sh \
 && chmod 0644 /etc/cron.d/* \
 && crontab /etc/cron.d/schedule

# .dockerignore drops runtime state from the context; recreate the directories
# Laravel expects, since COPY does not create empty directories.
RUN mkdir -p /var/log/supervisor \
             /var/www/storage/logs \
             /var/www/storage/framework/cache \
             /var/www/storage/framework/sessions \
             /var/www/storage/framework/views \
             /var/www/bootstrap/cache \
             /var/www/public/uploads \
 && touch /var/log/cron.log \
          /var/www/storage/logs/crontab.log \
          /var/www/storage/logs/supervisord.log \
 && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/public/uploads \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/public/uploads

# init.sh copies this into the shared public-build volume at boot.
RUN cp -a /var/www/public/build /opt/app-build

EXPOSE 9000

CMD ["/usr/local/bin/init.sh"]
