FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath opcache zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

COPY . .

RUN php artisan key:generate

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN php artisan config:cache

EXPOSE 9000

CMD ["php-fpm"]
