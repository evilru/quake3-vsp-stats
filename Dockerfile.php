FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD ["php", "vsp.php"]
