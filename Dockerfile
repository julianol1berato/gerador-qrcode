FROM php:8.2-apache

# Instala extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ativa módulos do Apache
RUN a2enmod rewrite

# Configura o diretório de trabalho
WORKDIR /var/www/html

# Copia composer.json e composer.lock primeiro (melhor utilização de cache)
COPY composer.json /var/www/html/

# Executa o composer install
RUN composer install --no-dev --no-scripts

# Copia todos os demais arquivos do projeto
COPY . /var/www/html/

# Cria o diretório qrcodes e configura permissões
RUN mkdir -p /var/www/html/qrcodes && \
    chown -R www-data:www-data /var/www/html/qrcodes && \
    chmod -R 755 /var/www/html/qrcodes

# Expõe a porta 80
EXPOSE 12080
