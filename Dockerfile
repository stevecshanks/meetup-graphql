FROM php:7.3-cli

RUN curl -sS https://getcomposer.org/installer | php \
    && chmod +x composer.phar\
    && mv composer.phar /usr/local/bin/composer

RUN pecl install mongodb

RUN docker-php-ext-enable mongodb

WORKDIR /usr/src/app
