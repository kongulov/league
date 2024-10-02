FROM php:8.3-apache

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libgd-dev \
    jpegoptim optipng pngquant gifsicle \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libssl-dev \
    zip \
    unzip \
    libcurl4-openssl-dev \
    supervisor \
    mariadb-client \
    libssl-dev \
    vim \
    openssl \
    wget \
    procps \
    htop \
    libavif-dev

RUN apt-get install -y mariadb-client

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-avif
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl intl

ARG APACHE_USER=www-data
ARG APACHE_GROUP=www-data
ARG USER_ID=1000

COPY composer.json composer.lock /var/www/html/
RUN composer install

COPY ./src /var/www/html

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

ENV APACHE_RUN_GROUP=${APACHE_GROUP}
ENV APACHE_RUN_USER=${APACHE_USER}

RUN id -u $APACHE_USER || useradd -rm -s /bin/sh -g ${APACHE_GROUP} -u ${USER_ID} ${APACHE_USER}