#!/usr/bin/env bash

echo "Running composer install..."
composer install --no-dev --working-dir=/var/www/html --ignore-platform-reqs

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache


