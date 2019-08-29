FROM registry.sedona.fr/images/php:7-fpm

RUN apt-get update && apt-get install php-ldap
