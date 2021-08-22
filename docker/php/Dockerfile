FROM php:7.3-fpm

# install php ext
RUN apt-get update && apt-get install -y \
        zip \
        unzip \
        libzip-dev \
        libicu-dev \
        libbz2-dev \
        libpng-dev \
        libjpeg-dev \
        libmcrypt-dev \
        libreadline-dev \
        libfreetype6-dev \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install \
        bz2 \
        intl \
        iconv \
        bcmath \
        opcache \
        calendar \
        mbstring \
        pdo_mysql \
        zip
