# Kasawo Yaffe: container for hosting a review copy (Render free plan).
# Build: docker build -t kasawo .   Run: docker run -p 8080:10000 kasawo

# 1. CSS and JavaScript
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
RUN npm ci --ignore-scripts --no-audit --no-fund
COPY resources ./resources
COPY public ./public
RUN npm run build

# 2. PHP packages (no dev tools)
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --ignore-platform-reqs

# 3. The web server
FROM php:8.4-apache
RUN docker-php-ext-install opcache \
 && a2enmod rewrite headers expires \
 && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
 && printf '<Directory /var/www/html/public>\n  AllowOverride All\n</Directory>\n' > /etc/apache2/conf-enabled/kasawo.conf \
 && printf '<IfModule mod_expires.c>\n  ExpiresActive On\n  ExpiresByType image/jpeg "access plus 30 days"\n  ExpiresByType image/svg+xml "access plus 30 days"\n  ExpiresByType text/css "access plus 1 year"\n  ExpiresByType application/javascript "access plus 1 year"\n</IfModule>\n' >> /etc/apache2/conf-enabled/kasawo.conf \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && printf 'opcache.enable=1\nopcache.validate_timestamps=0\nupload_max_filesize=12M\npost_max_size=60M\n' > "$PHP_INI_DIR/conf.d/kasawo.ini"

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
RUN composer dump-autoload --optimize --no-dev --no-interaction \
 && chmod +x docker/start.sh

ENV PORT=10000
EXPOSE 10000
CMD ["docker/start.sh"]
