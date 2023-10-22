# https://github.com/maciejslawik/docker-php-fpm-xdebug/blob/master/Dockerfile
# Use a imagem base do PHP com Apache
FROM php:7.4-apache

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

# Instale as dependências necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libxml2-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libpq-dev \
    libicu-dev \
    libxslt-dev \
    libmagickwand-dev \
    imagemagick \
    libmemcached-dev \
    libmemcached11 \
    libmemcachedutil2 \
    libmemcached-tools \
    wget


# Install xdebug
RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --version

RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        vim \
        nano
#    && docker-php-ext-install -j$(nproc) iconv bcmath \
#    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
#    && docker-php-ext-install -j$(nproc) gd

RUN apt-get install -y zlib1g-dev libicu-dev g++ libzip-dev git && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    apt-get purge -y g++


RUN apt upgrade -y

# Instale as extensões PHP
RUN docker-php-ext-install \
    curl \
    gd \
    mbstring \
    xml \
    zip \
    pgsql \
    pdo \
    json \
    xsl \
    calendar \
    exif \
    gettext \
    mysqli \
    shmop \
    soap \
    sockets \
    sysvmsg \
    sysvsem \
    sysvshm \
    pdo_mysql \
    pdo_pgsql \
    bcmath \
    opcache \
    pcntl

# Install newer libsodium
RUN wget https://download.libsodium.org/libsodium/releases/libsodium-1.0.18.tar.gz \
        && tar xfvz libsodium-1.0.18.tar.gz \
        && cd libsodium-1.0.18 \
        && ./configure \
        && make && make install \
        && pecl install -f libsodium
RUN docker-php-ext-install sodium


# Install Redis extension
# RUN pecl install -o -f redis \
#     && rm -rf /tmp/pear \
#     && echo "extension=redis.so" > /usr/local/etc/php/conf.d/docker-php-ext-redis.ini
	
	
# Instale o XDebug para PHP 8
# RUN pecl install xdebug && docker-php-ext-enable xdebug

# Tutorial do XDebug: https://www.youtube.com/watch?v=kbq3FJOYmQ0
# Copie o arquivo de configuração do XDebug para o diretório de configuração do PHP
# COPY 90-xdebug.ini "${PHP_INI_DIR}/conf.d"


# Instale uma versão específica do XDebug compatível com o PHP 7.4
# RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug

# RUN yes | pecl install ${XDEBUG_VERSION} \
#     && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini



# Defina o diretório de trabalho para o diretório padrão do Apache
WORKDIR /var/www/html

# Mantenha o Apache em execução no primeiro plano
CMD ["apache2-foreground"]
