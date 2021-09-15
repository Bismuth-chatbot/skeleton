ARG PHP_VERSION=7.4

FROM php:${PHP_VERSION}-cli-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /usr/src/chatbot

RUN apk add --no-cache --update zlib-dev acl

RUN docker-php-ext-configure sockets && docker-php-ext-install sockets pcntl

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . .

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	chmod +x bin/console; sync

COPY docker/php/entrypoint.sh  /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]

CMD [ "bin/console", "app:server:run" , "-vvv" ]
