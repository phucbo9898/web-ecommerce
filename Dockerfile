FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    curl \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    intl \
    gd

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/*.conf

RUN echo "upload_max_filesize=20M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=20M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=256M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 80
