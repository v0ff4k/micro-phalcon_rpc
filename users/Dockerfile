FROM php:7.2-fpm
#### must start exactly:   FROM bla:bla

ENV PHP_VER=7.2 \
    PHALCON_VERSION=3.4.4 \
    PHALCON_EXT_PATH=php7/64bits \
    TZ=Asia/Bishkek

RUN apt update && apt install -y \
#    && docker-php-ext-install opcache \
    curl \
    zip

# APCu
#RUN pecl install -y apcu-5.1.8
#    && docker-php-ext-enable apcu
#RUN echo "extension=apcu.so" > /usr/local/etc/php/conf.d/apcu.ini

# ext-apcu
RUN mkdir -p /usr/src/php/ext/apcu \
    && curl -fsSL https://github.com/krakjoe/apcu/archive/v5.1.8.tar.gz | tar xvz -C /usr/src/php/ext/apcu --strip 1 \
    && docker-php-ext-install apcu \
    # ext-bcmath, ext-sockets
    && docker-php-ext-install bcmath sockets \
    ## cleanup
    && docker-php-source delete

# Phalcon install
RUN set -xe && \
        cd /tmp && \
        # Compile Phalcon
        curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && \
        tar xzf /tmp/v${PHALCON_VERSION}.tar.gz && \
        docker-php-ext-install -j $(getconf _NPROCESSORS_ONLN) /tmp/cphalcon-${PHALCON_VERSION}/build/${PHALCON_EXT_PATH} && \
        # Remove all temp files
        rm -r \
            /tmp/v${PHALCON_VERSION}.tar.gz \
            /tmp/cphalcon-${PHALCON_VERSION}

# Timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#RUN service php${PHP_VER}-fpm-sp restart

# Default stuff
ADD run.sh /run.sh

RUN chmod 755 /run.sh

WORKDIR "/var/www/html"

CMD ["/run.sh"]
