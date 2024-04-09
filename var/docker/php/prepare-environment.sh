#!/usr/bin/env bash

set -eo pipefail

# Clean previous installation.
cd $APP_ROOT
find . -mindepth 1 -not -path "./project*" -not -path "./composer.json" -depth -delete

touch composer.json
build-composer-json
composer -n install
