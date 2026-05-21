FROM php:8.2-cli-bullseye AS php-deps

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    git \
    curl \
    unzip \
    libsqlite3-dev \
    libonig-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-install -j$(nproc) \
    pdo_sqlite \
    mbstring \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-interaction --no-progress --prefer-dist

FROM node:24-bullseye AS node-build
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./vite.config.js
COPY tailwind.config.js ./tailwind.config.js
COPY public ./public
RUN npm run build

FROM php:8.2-cli-bullseye

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    curl \
    sqlite3 \
    libsqlite3-0 \
    libonig5 \
    && rm -rf /var/lib/apt/lists/*

COPY --from=php-deps /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=php-deps /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /app
COPY --from=php-deps /app/vendor ./vendor
COPY . .
RUN rm -f bootstrap/cache/*.php
COPY --from=node-build /app/public/build ./public/build

RUN mkdir -p bootstrap/cache storage/logs storage/framework/sessions storage/framework/views storage/framework/cache \
    && chown -R www-data:www-data bootstrap/cache storage public

EXPOSE 8000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8000/ || exit 1
