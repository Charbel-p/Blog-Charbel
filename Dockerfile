# Fallback si Railway utilise Docker — build complet en une image
FROM php:8.2-cli-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip curl \
    libpq-dev libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql pdo_pgsql mbstring zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=node:20-bookworm-slim /usr/local/bin /usr/local/bin
COPY --from=node:20-bookworm-slim /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install \
    && npm run build \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker/entrypoint.sh

ENV PORT=8080
EXPOSE 8080
CMD ["sh", "docker/entrypoint.sh"]
