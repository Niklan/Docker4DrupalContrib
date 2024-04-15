#!/usr/bin/env bash

set -eo pipefail

cd $APP_ROOT
rm composer.json
touch composer.json
build-composer-json
composer -n install

cd $APP_ROOT/web/core
yarn install --frozen-lockfile
