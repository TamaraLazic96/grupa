# Dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (for Tailwind CLI)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g tailwindcss

# Set working directory
WORKDIR /var/www/symfony

# Copy project files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader

#RUN php bin/console cache:clear --env=prod && php bin/console cache:warmup --env=prod

# Install Tailwind binary via SymfonyCasts Tailwind bundle
#RUN php bin/console tailwind:init

# Build Tailwind CSS using the local binary
#RUN php bin/console cache:clear && \
#    php bin/console assets:install public && \
#    php bin/console tailwind:build && \
#    php bin/console asset-map:compile && \
#    php bin/console cache:clear

# Set permissions
RUN chown -R www-data:www-data /var/www/symfony/var /var/www/symfony/public/uploads
RUN chown -R www-data:www-data var
RUN chmod -R 775 var

# Copy the entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

# Ensure it is executable (just in case)
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set as the container's entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]

# Expose port
EXPOSE 9000

CMD ["php-fpm"]