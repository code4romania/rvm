FROM code4romania/php:8.4 AS vendor

COPY --chown=www-data:www-data . /var/www/html

RUN set -ex; \
    composer install \
    --optimize-autoloader \
    --no-interaction \
    --no-plugins \
    --no-dev \
    --prefer-dist

FROM node:24-alpine AS assets

WORKDIR /build

COPY \
    package.json \
    package-lock.json \
    postcss.config.js \
    tailwind.config.js \
    vite.config.js \
    ./

RUN set -ex; \
    npm ci --no-audit --ignore-scripts

COPY --from=vendor /var/www/html /build

RUN set -ex; \
    npm run build

FROM vendor

ARG VERSION
ARG REVISION

RUN echo "$VERSION (${REVISION:0:7})" > /var/www/html/.version

COPY --from=assets --chown=www-data:www-data /build/public/build /var/www/html/public/build

ENV APP_ENV=production
ENV APP_DEBUG=false

ENV QUEUE_ENABLED=true
ENV QUEUE_MAX_JOBS=5
ENV QUEUE_SLEEP=10
ENV QUEUE_REST=1
ENV QUEUE_TIMEOUT=600
ENV QUEUE_TRIES=1

ENV PHP_FPM_PM_MAX_CHILDREN=32
