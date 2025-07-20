# Gunakan base image PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Install Node.js dan NPM
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get install -y nodejs

# Install Composer (dependency manager untuk PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur direktori kerja di dalam container
WORKDIR /var/www/html

# Salin semua file proyek Anda ke dalam container
COPY . .

# Jalankan perintah build (Composer & NPM)
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# Atur izin folder yang benar untuk Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 untuk Apache
EXPOSE 80

# Selesai!