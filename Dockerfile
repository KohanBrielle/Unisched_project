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

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Ensure the storage and cache directories exist
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache

# Set ownership to the web server user
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Set correct permissions
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