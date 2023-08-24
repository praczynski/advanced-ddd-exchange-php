ARG PHP_IMAGE=8.2.8-bookworm

FROM php:${PHP_IMAGE} as php
RUN apt-get update \
    && apt-get install -y \
    apt-transport-https \
    libmagickwand-dev \
    unzip \
    zlib1g-dev \
    libzip-dev \
    && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash \
    && apt-get install -y symfony-cli libpq-dev \
    && mkdir -p /app \
    && useradd -m -u 1000 php_user \
    && chown -R 1000:1000 /app \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-install sockets pdo pdo_pgsql pgsql zip \
    && docker-php-ext-enable sockets pgsql
USER 1000
WORKDIR /app
CMD ["symfony",  "server:start"]

FROM php AS worker
USER 0
RUN apt-get install -y supervisor
COPY worker.conf /etc/supervisor/conf.d/worker.conf
RUN chmod -R 777 /var/log/supervisor
CMD ["/usr/bin/supervisord"]
