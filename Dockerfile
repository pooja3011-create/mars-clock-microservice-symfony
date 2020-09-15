FROM php:7.2-fpm

MAINTAINER Ahmet Akbana

RUN apt-get update && apt-get install -y \
    build-essential \
    vim \
    git \
    libzip-dev \
    zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Install extensions
RUN docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip

# Add console alias
RUN echo 'alias console="php bin/console"' >> ~/.bashrc

WORKDIR /app
