FROM php:8.3-apache

# Instalar dependencias del sistema y extensiones PHP
# Se incluye 'zip', 'unzip' y 'git' como solicitado, y las extensiones 'pdo_mysql' requeridas por tu DatabasePDO.php
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && a2enmod rewrite

# Configurar Apache para que el DocumentRoot apunte a la carpeta 'public'
# Esto es necesario ya que tu estructura de proyecto tien el index.php en 'public'
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar Composer (Opcional según tu snippet, pero recomendado)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código de la aplicación al contenedor
COPY . /var/www/html

# Ajustar los permisos del directorio para el usuario de Apache (www-data)
RUN chown -R www-data:www-data /var/www/html
