FROM registry.sedona.fr/images/php:7.4 as builder
LABEL maintainer="<php@sedona.fr> Sedona Solutions - PHP"

COPY . /var/www/html
WORKDIR /var/www/html

# Check for ignored folders to avoid layer issues, ref: https://github.com/docker/docker/issues/783
RUN if [ -d .git ]; then echo "ERROR: .dockerignore folders detected, exiting" && exit 1; fi

RUN set -ex ; \
    make install-php-ext;\
    make package_info.json c-install cc assets ;\
    make dotenv-clear

# création de l'image
FROM registry.sedona.fr/images/php:7.4-fpm-httpd
LABEL maintainer="<php@sedona.fr> Sedona Solutions - PHP"

ADD .deploy/dev/web/php-fpm.conf /usr/local/apache2/conf.d/php-fpm.conf
ADD .deploy/dev/app/run.sh /docker-entrypoint-init.d/run.sh
COPY --from=builder /var/www/html /var/www/html

WORKDIR /var/www/html

RUN set -xe ;\
    ls -la ;\
    make install-php-ext ;\
    make install-wkhtmltopdf ;\
    chown -R www-data /var/www/html ;\
    chmod a+w /var/www/html/var ;\
    echo "IncludeOptional conf.d/*.conf" >> /usr/local/apache2/conf/httpd.conf

