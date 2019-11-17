#!/bin/bash

if [[ "$APP_ENV" = "prod" ]]; then
    composer install --no-dev --no-progress --prefer-dist --no-interaction --no-suggest --classmap-authoritative --quiet
else
    if [[ "$OPCACHE_ENABLED" = "" ]]; then
        OPCACHE_ENABLED=0
    fi
fi

if [[ "$XDEBUG_ENABLED" = "1" ]]; then
    docker-php-ext-enable xdebug
fi

if [[ "$OPCACHE_ENABLED" = "0" ]]; then
    rm -f /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
fi

# If you use another framework, take care about warming up caches for appropriate environment
if [[ "$SKIP_WARMUP" != "1" ]]; then
    bin/console cache:warmup --quiet
fi

if [[ "$FPM_MAX_CHILDREN" = "" ]]; then
    FPM_MAX_CHILDREN=8
fi
echo "pm.max_children = $FPM_MAX_CHILDREN" >> /usr/local/etc/php-fpm.d/zzzz_runtime.conf

exec "$@"
