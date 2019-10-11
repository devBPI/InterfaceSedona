#!/bin/sh
set -e
echo "NPM install ----- "
yarn install

if [[ "$APP_ENV" == "prod" ]];
then
  echo "Encore Start "
  yarn encore production

  echo "Encore Start watch dev "
  yarn encore production --watch
else
  echo "Encore Start "
  yarn encore dev

  echo "Encore Start watch dev "
  yarn encore dev --watch
fi
