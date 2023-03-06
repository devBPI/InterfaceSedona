FROM registry.sedona.fr/images/php:7.3 as builder
LABEL maintainer="<php@sedona.fr> Sedona Solutions - PHP"

COPY . /var/www/html
WORKDIR /var/www/html

# Check for ignored folders to avoid layer issues, ref: https://github.com/docker/docker/issues/783
RUN if [ -d .git ]; then echo "ERROR: .dockerignore folders detected, exiting" && exit 1; fi

RUN set -ex ; \
    apt update && apt-get install php-ldap ;\
    make package_info.json c-install cc assets ;\
    make dotenv-clear

# création de l'image
FROM registry.sedona.fr/images/php:7-httpd-fpm
LABEL maintainer="<php@sedona.fr> Sedona Solutions - PHP"

ADD .deploy/rancher/app/php-fpm.conf /usr/local/apache2/conf.d/php-fpm.conf
COPY --from=builder /var/www/html /var/www/html

RUN set -xe ;\
    apt update && apt install -y --no-install-recommends xvfb wkhtmltopdf ;\
    apt-get install php7.3-ldap ;\
    rm -rf /var/lib/apt/lists/* ; \
    chown -R www-data /var/www/html ;\
    chmod a+w /var/www/html/var ;\
    echo "IncludeOptional conf.d/*.conf" >> /usr/local/apache2/conf/httpd.conf

WORKDIR /var/www/html
