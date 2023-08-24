ARG PHP_IMAGE=8.2-fpm-bullseye

FROM php:${PHP_IMAGE} as php
RUN apt-get update \
    && apt-get install -y apt-transport-https libpq-dev libzip-dev zip unzip \
    && mkdir -p /app \
    && useradd -m -u 1000 php_user \
    && chown -R 1000:1000 /app \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-install sockets pdo pdo_pgsql pgsql \
    && docker-php-ext-enable sockets pgsql
COPY www.conf /usr/local/etc/php-fpm.d/zz-ddd2.conf    
USER 1000
WORKDIR /app

FROM php AS worker
USER 0
RUN apt-get install -y supervisor
COPY worker.conf /etc/supervisor/conf.d/worker.conf
RUN chmod -R 777 /var/log/supervisor
CMD ["/usr/bin/supervisord"]
