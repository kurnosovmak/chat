FROM php:8.1-fpm-buster AS php81-fpm

RUN apt-get update && apt-get install -y libpng-dev && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libmcrypt-dev \
        libssl-dev \
        libz-dev \
        libmemcached-dev \
        libxml2-dev \
        libgeoip-dev \
        libxslt1-dev libxslt1.1 \
        libwebp-dev \
        libgd-dev \
        libmagickwand-dev \
        git \
        libzip-dev \
        zip \
        libffi-dev \
        libldb-dev \
        libldap2-dev \
        libpq-dev \
        cron \
        && apt-get clean \
  && pecl channel-update pecl.php.net \
  && pecl install imagick && docker-php-ext-enable imagick \
  && docker-php-ext-install gd \
  && docker-php-ext-install zip \
  && docker-php-ext-install opcache \
  && docker-php-ext-install soap \
  && docker-php-ext-install xsl \
  && docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update && apt-get install -y libc-client-dev libkrb5-dev && rm -r /var/lib/apt/lists/*
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap

RUN ln -s /usr/lib/x86_64-linux-gnu/libldap.so /usr/lib/libldap.so \
    && ln -s /usr/lib/x86_64-linux-gnu/liblber.so /usr/lib/liblber.so

RUN docker-php-ext-install bcmath calendar exif gettext intl ldap pcntl shmop sockets sysvmsg sysvsem sysvshm

RUN docker-php-ext-configure ffi --with-ffi && docker-php-ext-install ffi

ENV EXT_REDIS_VERSION=5.3.6 EXT_IGBINARY_VERSION=3.2.7
RUN docker-php-source extract \
    # igbinary
    && mkdir -p /usr/src/php/ext/igbinary \
    &&  curl -fsSL https://github.com/igbinary/igbinary/archive/$EXT_IGBINARY_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/igbinary --strip 1 \
    && docker-php-ext-install igbinary \
    # redis
    && mkdir -p /usr/src/php/ext/redis \
    && curl -fsSL https://github.com/phpredis/phpredis/archive/$EXT_REDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && docker-php-ext-configure redis --enable-redis-igbinary \
    && docker-php-ext-install redis \
    # cleanup
    && docker-php-source delete

RUN pecl install memcached && docker-php-ext-enable memcached
RUN pecl install msgpack && docker-php-ext-enable msgpack
RUN pecl install pcov && docker-php-ext-enable pcov
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY ./php-fpm.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY ./php.ini /usr/local/etc/php/php.ini

ARG UID
ARG GID

RUN usermod  --uid $UID www-data
RUN chown www-data: /var/www
RUN if [[ "$OSTYPE" == "linux-gnu"* ]] ; then groupmod --gid ${GID} www-data ; fi

########################### CRON
FROM php81-fpm AS php81-cron
COPY crontab ./
RUN cat crontab | crontab -u www-data -
CMD ["cron", "-f"]

########################### HORIZON
FROM php81-fpm AS php81-horizon
USER www-data
CMD php ./artisan horizon

########################### INIT-APP
FROM php81-fpm AS php81-init-app
USER www-data
CMD composer install --optimize-autoloader \
    && php artisan optimize:clear \
    && php artisan migrate --force \
    && php artisan geoip:update \
    && php artisan optimize
