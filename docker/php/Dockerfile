# syntax=docker/dockerfile:1
FROM unit:1.32.1-php8.3
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN <<EOF
apt-get update
apt-get install -y --no-install-recommends \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  libzip-dev \
  libcurl4-openssl-dev \
  zip \
  unzip \
  locales
apt-get clean
rm -rf /var/lib/apt/lists/*
locale-gen en_US.UTF-8
localedef -f UTF-8 -i en_US en_US.UTF-8
pecl install redis-6.0.2
pecl install brotli-0.15.0
docker-php-ext-configure opcache --enable-opcache
docker-php-ext-install \
  opcache \
  pdo_mysql \
  bcmath \
  intl \
  gd \
  zip
docker-php-ext-enable \
  redis \
  brotli
EOF

ENV LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8
