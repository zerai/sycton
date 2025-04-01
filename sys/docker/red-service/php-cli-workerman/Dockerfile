# Arguments can be overridden docker-compose.yml
ARG PHP_BASE_IMAGE_VERSION=8.2-cli-alpine3.21


FROM php:$PHP_BASE_IMAGE_VERSION as php_upstream

# The different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target


FROM php_upstream as php_base

MAINTAINER Zerai Teclai <teclaizerai@gmail.com>

# Add Dependencies
RUN apk add --update --no-cache \
    gcc \
    g++ \
    make \
    python3 \
    supervisor \
    bash


# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
# Install PHP Core Extensions
COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/bin/install-php-extensions
RUN install-php-extensions intl \
    zip \
    bcmath  \
    imap \
    opcache \
    gd \
    pgsql \
    pdo_pgsql \
    pcntl \
    redis \
    sockets \
    amqp

# Configure Extension
RUN docker-php-ext-configure \
    opcache --enable-opcache

# Configure supervisor
ADD supervisord.conf /etc/supervisord.conf

WORKDIR /app



# Dev php image
FROM php_base AS php_dev
# Add DevDependencies
RUN apk add --update --no-cache \
    inotify-tools \
    vim \
    git
# enable if needed
#    nodejs \
#    npm \

ENV XDEBUG_MODE=off

RUN set -eux; \
	install-php-extensions \
		xdebug \
        inotify \
    ;

EXPOSE 8080
COPY --chmod=744 start.sh /start.sh
CMD [ "/start.sh" ]



# Prod php image
FROM php_base AS php_prod

ADD supervisor.d/ /etc/supervisor.d/

EXPOSE 80
EXPOSE 443

COPY --chmod=744 start.sh /start.sh
ENTRYPOINT [ "/start.sh" ]
