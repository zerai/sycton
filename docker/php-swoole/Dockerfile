FROM php:8.1-cli-alpine3.16 as step_1

MAINTAINER Zerai Teclai <teclaizerai@gmail.com>

# Install Dependencies
#RUN apk update
RUN apk add --no-cache supervisor vim autoconf gcc make g++ inotify-tools bash git
RUN apk add --no-cache libzip-dev curl-dev icu-dev oniguruma-dev imap-dev postgresql-dev
RUN apk add --no-cache libpng-dev openssl-dev nghttp2-dev hiredis-dev rabbitmq-c-dev

## Install Pecl Extension
#RUN pecl channel-update pecl.php.net
#RUN pecl install igbinary inotify
#RUN pecl install https://github.com/0x450x6c/php-amqp/raw/7323b3c9cc2bcb8343de9bb3c2f31f6efbc8894b/amqp-1.10.3.tgz
#RUN pecl install swoole --enable-sockets --enable-openssl --enable-async-redis --enable-http2 --enable-mysqlnd
#RUN pecl bundle redis && cd redis && phpize && ./configure --enable-redis-igbinary && make && make install
#RUN docker-php-ext-enable igbinary redis swoole inotify amqp




FROM step_1 as step_2
# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer


FROM step_2 as step_3

# Install PHP Core Extensions
#RUN docker-php-ext-install curl intl mbstring zip bcmath imap opcache gd pdo_pgsql pcntl iconv sockets
#RUN docker-php-ext-enable curl intl mbstring zip bcmath imap opcache gd pdo_pgsql pcntl iconv sockets

#RUN docker-php-ext-install curl intl mbstring zip bcmath imap opcache gd pdo_pgsql pcntl sockets ampq
#RUN docker-php-ext-enable curl intl mbstring zip bcmath imap opcache gd pdo_pgsql pcntl sockets
COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/bin/install-php-extensions
RUN install-php-extensions curl intl mbstring zip bcmath imap opcache gd pdo_pgsql pcntl sockets iconv amqp igbinary redis swoole inotify


# Cleaning
RUN docker-php-source delete

# Configure supervisor
ADD supervisord.conf /etc/supervisord.conf


ADD start.sh /start.sh
RUN chmod +x /start.sh

WORKDIR /app

EXPOSE 8080

CMD ["/start.sh"]
