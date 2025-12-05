FROM php:8.2-apache

RUN a2enmod ssl && a2enmod rewrite

RUN apt-get update && apt-get install -y --no-install-recommends tzdata \
    && rm -rf /var/lib/apt/lists/*

COPY ssl/ /etc/ssl/private/
COPY conf/default.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80 443

CMD ["apache2-foreground"]
