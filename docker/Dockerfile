FROM php:8.3-cli

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/root/.composer

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /application

CMD ["php"]
