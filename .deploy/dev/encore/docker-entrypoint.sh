#!/bin/sh
set -e

echo "NPM install ----- "
npm install

echo "Encore Start "
yarn encore dev

echo "Encore Start watch "
yarn encore dev --watch
