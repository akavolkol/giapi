FROM alpine:3.8

RUN apk --update add \
  php7 \
  php7-pdo \
  php7-xml \
  php7-json \
  php7-openssl \
  php7-pdo_mysql \
  php7-mcrypt \
  php7-pdo_sqlite \
  php7-ctype \
  php7-zlib \
  php7-curl \
  php7-phar \
  php7-opcache \
  php7-intl \
  php7-dom \
  php7-xmlreader \
  php7-simplexml \
  php7-fileinfo \
  php7-tokenizer \
  php7-mbstring \
  php7-iconv \
  php7-session \
  php7-exif \
  php7-gd \
  curl \
  php7-xmlwriter \
  php7-xdebug \
  php7-zip \
  make \
  && rm -rf /var/cache/apk/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
ADD php.ini /etc/php7/php.ini
RUN mkdir /www
RUN addgroup -S php -g 1000 && adduser -u 1000 -S -G php php

WORKDIR /www
EXPOSE 80 9000
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]
