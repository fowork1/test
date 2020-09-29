#!/bin/bash

php artisan config:cache

php artisan route:clear
php artisan cache:clear
php artisan config:clear

composer dump-autoload
#composer update

