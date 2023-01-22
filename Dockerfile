FROM php:8.1-fpm-alpine as php
RUN apk add --no-cache \
	net-tools \
	iputils

RUN docker-php-ext-install \
	pdo_mysql \
	sockets

RUN chmod 777 -R /var/www

FROM nginx:latest as nginx
RUN apt-get update -y && \
	apt-get install -y net-tools && \
	apt-get install -y iputils-ping

