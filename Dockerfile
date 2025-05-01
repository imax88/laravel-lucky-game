FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


RUN a2enmod rewrite
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf


WORKDIR /var/www/html


COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80
CMD ["/entrypoint.sh"]
