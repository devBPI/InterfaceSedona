#!/bin/sh

make assets

if ! [ -x "$(command -v wkhtmltopdf)" ]; then
  echo 'Install wkhtmltopdf and xvfb'
  apt-get install -y xvfb wkhtmltopdf
fi

apt-get update && apt-get install libldap2-dev -y && docker-php-ext-install ldap
