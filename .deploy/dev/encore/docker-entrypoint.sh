#!/bin/sh
set -e

echo "NPM install ----- "
yarn install

echo "Encore Start "
yarn encore dev

echo "Encore Start watch "
yarn encore dev --watch
