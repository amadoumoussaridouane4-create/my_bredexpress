FROM php:8.2-apache

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copier les fichiers
COPY . /var/www/html/

# Activer mod_rewrite
RUN a2enmod rewrite

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
