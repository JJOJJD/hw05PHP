FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

ENV APACHE_DOCUMENT_ROOT /var/www/html/Public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

COPY . /var/www/html/

RUN echo "Listen \${PORT:-80}" > /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:\${PORT:-80}>/g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD echo "const SUPABASE_URL = '${SUPABASE_URL}'; const SUPABASE_ANON_KEY = '${SUPABASE_ANON_KEY}';" > /var/www/html/Public/js/config.js && apache2-foreground
