FROM nginx:alpine

RUN (getent group www-data || addgroup -S www-data) \
 && adduser -S -D -H -G www-data www-data \
 && getent passwd www-data

COPY . /var/www

RUN rm -rf /var/www/public/storage \
 && ln -s /var/www/storage/app/public /var/www/public/storage

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/conf.d/ /etc/nginx/conf.d/

EXPOSE 80
EXPOSE 443
