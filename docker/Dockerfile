FROM php:8.2-cli

# Install Xdebug
RUN pecl install xdebug-3.2.2 \
    && docker-php-ext-enable xdebug

# Install unzip for composer dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip \
    && apt-get autoremove -y

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /root/.composer

# Use a Composer version compatible with PHP 8.2
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /application

CMD ["php"]
