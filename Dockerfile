FROM registry.sedona.fr/images/php:7.3 as builder
LABEL maintainer="<php@sedona.fr> Sedona Solutions - PHP"

COPY . /var/www/html
WORKDIR /var/www/html

# Check for ignored folders to avoid layer issues, ref: https://github.com/docker/docker/issues/783
RUN if [ -d .git ]; then echo "ERROR: .dockerignore folders detected, exiting" && exit 1; fi

RUN set -ex ; \
    make c-install

# compilation des assets
FROM node:10-alpine

WORKDIR /var/www/html

RUN set -ex ; \
    npm install ;\
    encore production

# création de l'image
FROM registry.sedona.fr/images/php:7-httpd-fpm

ADD .deploy/rancher/app/php-fpm.conf /usr/local/apache2/conf.d/php-fpm.conf

COPY --from=builder /var/www/html /var/www/html
RUN set -xe ;\
    chown -R www-data /var/www/html ;\
    chmod a+w /var/www/html/var ;\
    echo "IncludeOptional conf.d/*.conf" >> /usr/local/apache2/conf/httpd.conf

WORKDIR /var/www/html
