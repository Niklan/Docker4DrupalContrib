#!/usr/bin/env bash

set -eo pipefail

# Clean previous installation.
cd $APP_ROOT
rm -rf ./* ./.*

touch composer.json
build-composer-json
composer -n install

mkdir -p $APP_ROOT/web/modules/custom
ln -fs $DRUPAL_PROJECT_PATH $APP_ROOT/web/modules/custom/$DRUPAL_PROJECT_NAME
