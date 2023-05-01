FROM php:8.1-apache

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

COPY ./src /var/www/public
RUN chown -R www-data:www-data /var/www

RUN sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf && \
  sed -i "s/:80/:${PORT:-80}/g" /etc/apache2/sites-enabled/*

EXPOSE 80

CMD ["apache2-foreground"]