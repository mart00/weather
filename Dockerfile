# syntax=docker/dockerfile:1

FROM composer:lts AS deps
WORKDIR /app
RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \    
#    --mount=type=secret,id=aws \
#    AWS_SHARED_CREDENTIALS_FILE=/run/secrets/aws \
#    aws s3 cp ... \
    composer install --no-dev --no-interaction

FROM php:8.2-apache AS final
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-enable mysqli 
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --from=deps app/vendor/ /var/www/html/vendor
COPY ./src /var/www/html
ENV PASSWORD_FILE_PATH=/run/secrets/db-password
USER www-data   