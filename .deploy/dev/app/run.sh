#!/bin/sh

#make assets
#dpkg --configure -a

if ! [ -x "$(command -v wkhtmltopdf)" ]; then
  echo 'Install wkhtmltopdf and xvfb'
  apt-get install -y xvfb wkhtmltopdf
fi
