FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd

# Install libpq-dev and the pdo_pgsql extension
RUN apt-get update && apt-get install -y libpq-dev \ && docker-php-ext-install pdo pdo_pgsql pdo_mysql

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create directories and set permissions before running the app
RUN mkdir -p /var/www/storage/logs /var/www/storage/framework/views /var/www/storage/framework/sessions /var/www/storage/framework/cache /var/www/bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache


# Copy the custom nginx config
COPY nginx.conf /etc/nginx/sites-available/default

# Copy the deployment script
COPY deploy.sh /usr/local/bin/deploy.sh
RUN chmod +x /usr/local/bin/deploy.sh

# Expose port 80
EXPOSE 80

# Run deployment script
CMD ["/usr/local/bin/deploy.sh"]

# This ensures all necessary Laravel folders are created and writable
RUN mkdir -p /var/www/storage/logs \
             /var/www/storage/framework/sessions \
             /var/www/storage/framework/views \
             /var/www/storage/framework/cache \
             /var/www/bootstrap/cache
