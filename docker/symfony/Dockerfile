FROM php:8.3-cli

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Install postgres, PDO and other packages
RUN apt-get -y update && apt-get install -y \
    curl git unzip zip libpq-dev wget \
    && docker-php-ext-install pgsql pdo_pgsql

### Install xdebug
RUN if [ -n "$http_proxy" ]; then \
      pear config-set http_proxy ${http_proxy}; \
    fi
RUN pecl channel-update pecl.php.net
RUN pecl install -o -f xdebug \
    && docker-php-ext-enable xdebug

COPY ./php.ini /usr/local/etc/php/
### Fim - Install xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x /usr/local/bin/composer
RUN composer config --global process-timeout 2000

# Definir o diretório de trabalho dentro do container
WORKDIR /app

# Expor a porta que será usada pelo servidor embutido
EXPOSE 8000

# Comando para iniciar o servidor PHP embutido
#CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app"]
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/app/public/"]