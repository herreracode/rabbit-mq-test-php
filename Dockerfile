#lib rabbitMQ need version 8.0.0 or minor php-amqplib/php-amqplib
FROM php:8.0.0

RUN apt-get update\
 && apt-get install -y autoconf libmcrypt-dev pkg-config apt-utils git vim openssl zip libssl-dev unzip\
 && apt-get install vim\
 && docker-php-ext-install pdo pdo_mysql\
 #ext need rabbitMQ
 && docker-php-ext-install sockets

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . /usr/src/myapp

WORKDIR /usr/src/myapp
