FROM php:7.2.8-cli
RUN apt-get update && \
  apt-get install -y wget && \
  docker-php-ext-install pdo_mysql mysqli
WORKDIR /usr/src
RUN wget https://phar.phpunit.de/phpunit-7.2.7.phar
