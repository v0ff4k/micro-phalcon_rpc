#!/usr/bin/env bash

echo ' -------- USERS --------  initiated run.sh !!!'

composer install
docker-php-entrypoint php-fpm

./vendor/bin/phalcon migration run --log-in-db