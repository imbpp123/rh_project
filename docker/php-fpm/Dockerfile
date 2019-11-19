FROM php:7.3-fpm as base
COPY --from=composer:1.9.0 /usr/bin/composer /usr/local/bin/composer

RUN apt update && apt install -y \
    curl \
    git \
    libzip-dev \
    unzip \
    && apt clean

RUN docker-php-ext-install zip pdo pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache

COPY docker/php-fpm/php.ini /usr/local/etc/php/php.ini
COPY docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
ENV LOG_STREAM="php://stdout"

# By default, php-fpm clears all environment variables.
# We need to keep it because we would like to pass them from Apache
RUN echo 'clear_env = no' > /usr/local/etc/php-fpm.d/zzz_clear_env.conf

# We need it to enable/disable extensions and write configs in entrypoint for www-data user
RUN chown -R www-data:www-data /usr/local/etc/php/conf.d/ /usr/local/etc/php-fpm.d/

# Make entrypoint. Skip symfony warmup since we do not have symfony at this moment
ENV SKIP_WARMUP=1
COPY docker/php-fpm/entrypoint.sh /
ENTRYPOINT ["/entrypoint.sh"]
CMD php-fpm

# www-data user should have access to application directory and its home directory
RUN mkdir /app && chown www-data:www-data /app /var/www
WORKDIR /app

# ----------------------------------------------------------------------------------------------------------------------

FROM base AS builder

ARG BUILDER_UID=1000
ARG BUILDER_GID=1000

# Create a user with same UID/GID, as docker host
RUN groupadd -g $BUILDER_GID builder \
    && useradd -u $BUILDER_UID -g $BUILDER_GID builder \
    && mkdir -p /home/builder/build \
    && chown -R builder:builder /home/builder

ENTRYPOINT ["/usr/bin/env"]
CMD bash

WORKDIR /home/builder/build
USER builder

RUN composer global require hirak/prestissimo --no-progress

# ----------------------------------------------------------------------------------------------------------------------

FROM base AS app
USER www-data
ENV SKIP_WARMUP=0
