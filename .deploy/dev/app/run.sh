#!/bin/sh

#make assets
#dpkg --configure -a

if ! [ -x "$(command -v wkhtmltopdf)" ]; then
  echo 'Install wkhtmltopdf and xvfb'
  apt-get update
  apt-get install -y xvfb wget
  apt-get install -y openssl libssl-dev libxrender-dev libx11-dev libxext-dev libfontconfig1-dev libfreetype6-dev fontconfig
  wget https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz -O /tmp/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz
  tar xvf /tmp/wkhtmltox*.tar.xz -C /tmp
  mv /tmp/wkhtmltox/bin/wkhtmlto* /usr/bin/
  ln -nfs /usr/bin/wkhtmltopdf /usr/local/bin/wkhtmltopdf
fi

apt-get install libldap2-dev -y
rm -rf /var/lib/apt/lists/*
docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/
docker-php-ext-install ldap