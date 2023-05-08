FROM php:8-fpm-alpine

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql

RUN wget https://getcomposer.org/installer -O - | php -- --install-dir=/usr/local/bin --filename=composer

ADD php.ini /usr/local/etc/php/php.ini
ADD php-fpm.conf /etc/php/php-fpm.d/

WORKDIR /var/www/user-api

EXPOSE 9000

CMD ["php-fpm", "-F"]
