FROM wodby/php:7.2-dev-4.7.4

COPY source /var/www/html

CMD [ "composer install" ]
