#!/bin/sh

make assets
#dpkg --configure -a

if ! [ -x "$(command -v wkhtmltopdf)" ]; then
  echo 'Install wkhtmltopdf and xvfb'
  apt-get install -y xvfb wkhtmltopdf
fi

if ! [ -x "$(command -v libldap2-dev)" ]; then
  echo 'Install libldap2 and ldap php extension'
  apt-get update && apt-get install libldap2-dev -y && docker-php-ext-install ldap
fi
