FROM php:8.3-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y --no-install-recommends \
    ca-certificates curl gnupg git unzip zip cron supervisor procps \
    netcat-traditional default-mysql-client \
 && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
 && apt-get install -y --no-install-recommends nodejs \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql zip exif pcntl intl sockets gd redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist

COPY package.json ./
RUN npm install

COPY . /var/www
RUN npm run build && rm -rf node_modules \
 && composer dump-autoload --optimize \
 && cp -a /var/www/public/build /opt/app-build

COPY ./docker/php/supervisord/supervisord.conf /etc/supervisor/
COPY ./docker/php/supervisord/scripts/* /etc/supervisor/conf.d/
COPY ./docker/php/crons/* /etc/cron.d/
RUN for f in /etc/cron.d/*; do tr -d '\r' < "$f" > "$f.tmp" && mv "$f.tmp" "$f" && chmod 0644 "$f"; done \
 && crontab /etc/cron.d/schedule

RUN mkdir -p /var/log/supervisor /var/www/storage/logs \
 && touch /var/log/cron.log /var/www/storage/logs/crontab.log /var/www/storage/logs/supervisord.log \
 && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

COPY ./docker/php/init.sh /usr/local/bin/init.sh
RUN tr -d '\r' < /usr/local/bin/init.sh > /usr/local/bin/init.sh.tmp \
 && mv /usr/local/bin/init.sh.tmp /usr/local/bin/init.sh \
 && chmod +x /usr/local/bin/init.sh

EXPOSE 9000

CMD ["/usr/local/bin/init.sh"]
