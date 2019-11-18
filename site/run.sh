#!/usr/bin/env bash

echo ' -------- SITE --------  initiated run.sh !!!'

composer install
docker-php-entrypoint php-fpm