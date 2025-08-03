FROM php:8.3-apache

VOLUME /var/www
EXPOSE 80 443
USER root

ENV APACHE_DOCUMENT_ROOT=/var/www/public

RUN apt-get update
RUN apt-get install -y \
        openssh-client \
        openssh-server \
        locales-all \
        git
RUN apt-get install -y \
        unzip \
        iputils-ping \
        imagemagick \
        graphicsmagick \
        ghostscript

# Install Mysqldump (included in Mysql-Client)
RUN curl -LO https://dev.mysql.com/get/mysql-apt-config_0.8.34-1_all.deb \
    && apt-get install -y lsb-release wget gnupg \
    && echo "Ok"|dpkg -i mysql-apt-config_0.8.34-1_all.deb \
    && rm -f mysql-apt-config_0.8.34-1_all.deb \
    && apt update \
    && apt install -y mysql-client

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN ln -sfr /etc/apache2/mods-available/expires.load /etc/apache2/mods-enabled/expires.load && \
    ln -sfr /etc/apache2/mods-available/headers.load /etc/apache2/mods-enabled/headers.load
# install composer
ENV COMPOSER_BINARY=/usr/local/bin/composer \
    COMPOSER_HOME=/usr/local/composer
ENV PATH=$PATH:$COMPOSER_HOME

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar "$COMPOSER_BINARY" && \
    chmod +x "$COMPOSER_BINARY"

##################################################################################
# configuration according to https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/Administration/Installation/SystemRequirements/Index.html#using-typo3-with-docker-based-environments
##################################################################################
# Enable Apache mod_rewrite
RUN a2enmod rewrite

RUN apt-get install -y \
      libzip-dev libpng-dev libxml2-dev libonig-dev libicu-dev unzip git libfreetype6-dev libjpeg62-turbo-dev
RUN docker-php-ext-install pdo_mysql mysqli intl zip exif
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Set recommended PHP settings
COPY php.ini /usr/local/etc/php/
##################################################################################


RUN docker-php-ext-install calendar
RUN docker-php-ext-install bcmath

#RUN docker-php-ext-install xmlrpc   https://php.watch/versions/8.0/xmlrpc#alternatives

RUN apt-get install -y \
        zlib1g-dev

RUN apt-get install -y \
        libgmp-dev \
    && docker-php-ext-install gmp

# https://github.com/xdebug/xdebug/releases/tag/3.4.4
RUN pecl install xdebug-3.4.4 \
    && docker-php-ext-enable xdebug

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Document root
WORKDIR /var/www



# docker run -it --rm -u root --entrypoint "/bin/bash" php:8.3-apache

    
#docker run -p 80:80 typo3apachephp
#chmod a+w /var/www
#composer create-project typo3/cms-base-distribution tom "^13"
#chown -R www-data:www-data tom
#mv tom/* .
#touch /var/www/public/FIRST_INSTALL 

#docker cp .htaccess 1afc1dcc7538:/var/www/public