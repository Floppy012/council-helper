#!/bin/bash

php artisan optimize
php artisan migrate --force
