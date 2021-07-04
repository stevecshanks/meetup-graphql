FROM php:7.4-rc-cli

RUN curl -sS https://getcomposer.org/installer | php \
    && chmod +x composer.phar \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /tmp

ARG MONGODB_VERSION=1.5.5

RUN curl -LsS https://github.com/mongodb/mongo-php-driver/releases/download/${MONGODB_VERSION}/mongodb-${MONGODB_VERSION}.tgz -o mongodb.tgz \
    && docker-php-source extract \
    && tar zxf mongodb.tgz \
    && mv mongodb-${MONGODB_VERSION} /usr/src/php/ext/mongodb \
    && docker-php-ext-install mongodb \
    && rm mongodb.tgz

WORKDIR /usr/src/app
