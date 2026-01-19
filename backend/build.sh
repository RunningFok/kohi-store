#!/bin/bash
set -e

# Install PHP extensions
apt-get update
apt-get install -y libicu-dev zlib1g-dev libzip-dev
docker-php-ext-install intl zip

# Install Composer dependencies
composer install --optimize-autoloader --no-scripts --no-interaction
