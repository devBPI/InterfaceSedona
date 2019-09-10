if ! [ -x "$(command -v wkhtmltopdf)" ]; then
  echo 'Install wkhtmltopdf and xvfb'
  apt-get install -y xvfb wkhtmltopdf
fi

php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
