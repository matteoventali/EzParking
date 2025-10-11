FROM php:8.2-apache

# Modules for https
RUN a2enmod ssl && a2enmod rewrite

# Copying SSL certificates 
COPY ssl/ /etc/ssl/private/
COPY conf/default.conf /etc/apache2/sites-available/000-default.conf

# Timezone
ENV TZ=Europe/Rome

# Ports exposed
EXPOSE 80 443

CMD ["apache2-foreground"]
