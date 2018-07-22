FROM php:7.2.8-cli-alpine3.7
RUN apk update && \
  docker-php-ext-install -j$(nproc) pdo_mysql mysqli
WORKDIR /usr/src
RUN wget https://phar.phpunit.de/phpunit-7.2.7.phar
