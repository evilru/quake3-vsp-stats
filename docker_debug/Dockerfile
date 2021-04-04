# FROM php:7.4.16-apache
FROM php:8-apache
RUN docker-php-ext-install mysqli \
    # && pecl install xdebug-2.9.8 \
    && pecl install xdebug-3.0.3 \
    && docker-php-ext-enable xdebug