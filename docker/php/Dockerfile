FROM php:7.4-fpm

MAINTAINER Tomasz Tarnawski <tarnawski27@gmail.com>

RUN apt-get update && apt-get install -y libicu-dev
RUN docker-php-ext-install intl opcache pdo_mysql
