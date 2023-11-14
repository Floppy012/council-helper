FROM node:21-alpine3.17 as npm_build

RUN mkdir -p /work
WORKDIR /work

COPY . .
RUN npm install
RUN npm run build

FROM webdevops/php-nginx:8.2-alpine
ENV WEB_DOCUMENT_ROOT=/app/public

ENV PHP_DISMOD=bz2,calendar,exiif,ffi,intl,gettext,ldap,mysqli,imap,soap,sockets,sysvmsg,sysvsm,sysvshm,shmop,xsl,zip,gd,apcu,vips,yaml,imagick,mongodb,amqp

WORKDIR /app

COPY . .
COPY --from=npm_build /work/public/build ./public/build
COPY docker/provision.sh /opt/docker/provision/entrypoint.d/
COPY docker/supervisor/horizon.conf /opt/docker/etc/supervisor.d/
COPY docker/crontab /opt/docker/etc/cron/

RUN docker-service-enable horizon

RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN php artisan horizon:publish
RUN chown -R application:application .

