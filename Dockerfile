FROM dunglas/frankenphp

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /src

ENV SERVER_NAME=":8000"
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

# メモリリーク対策
ENV FRANKENPHP_WORKER_COUNT=2
ENV FRANKENPHP_MAX_REQUESTS=500
ENV MAX_RUNTIME_SECONDS=3600
ENV GOMEMLIMIT=480MiB

# PHPメモリ制限
ENV PHP_INI_MEMORY_LIMIT=192M