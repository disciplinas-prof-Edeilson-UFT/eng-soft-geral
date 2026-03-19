FROM php:8.4-apache 

# Instala dependências do sistema
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilita mod_rewrite
RUN a2enmod rewrite

# Cria o DIR 
WORKDIR /var/www/html

# Copia os arquivos da aplicação para o DIR
COPY . /var/www/html/

# Instala as dependências do Composer
RUN composer install --no-dev --optimize-autoloader

# Configura permissões
RUN chown -R www-data:www-data /var/www/html