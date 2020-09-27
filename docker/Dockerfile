FROM php:7.4-fpm

# RUN set -ex \
#   && apk --no-cache add \
#     postgresql-dev

# RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql	

ENV TZ=Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN apt-get install -y \
        zlib1g-dev libpng-dev libxml2-dev

RUN apt-get install -y libzip-dev libonig-dev libcurl4-openssl-dev

RUN docker-php-ext-install gd soap zip mbstring curl

RUN apt-get install -y libmagickwand-dev --no-install-recommends

RUN pecl install imagick && docker-php-ext-enable imagick

RUN pecl install xdebug-2.9.4 && docker-php-ext-enable xdebug

RUN apt-get install -y git --no-install-recommends

RUN apt install -y ghostscript

#RUN groupadd -g 1000 www-data
#RUN useradd -u 1000 -ms /bin/bash -g www-data www-data
USER www-data

COPY --from=composer /usr/bin/composer /usr/bin/composer

# EXPOSE 9001

# COPY php/php.ini $PHP_INI_DIR/php.ini
