FROM alpine:3.14

# ARG NOVA_USERNAME
# ENV USERNAME $NOVA_USERNAME

# ARG NOVA_PASSWORD
# ENV PASSWORD $NOVA_PASSWORD

# Install packages and remove default server definition
RUN apk --no-cache add \
  curl \
  nginx \
  php8 \
  php8-ctype \
  php8-curl \
  php8-dom \
  php8-fpm \
  php8-gd \
  php8-intl \
  php8-json \
  php8-mbstring \
  php8-mysqli \
  php8-pdo_pgsql\
  php8-pdo_mysql \
  php8-pgsql \
  php8-opcache \
  php8-openssl \
  php8-phar \
  php8-session \
  php8-xml \
  php8-xmlreader \
  php8-zlib \
  php8-simplexml\
  php8-pdo\
  php8-soap\
  php8-fileinfo \
  php8-tokenizer\
  php8-xmlwriter\
  php8-pcntl\
  php8-posix\
  php8-iconv\
  php8-zip\
  php8-exif\
  php8-redis \
  php8-bcmath\
  php8-sockets\
  supervisor\
  git

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Create symlink so programs depending on `php` still function
RUN ln -s /usr/bin/php8 /usr/bin/php

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Configure nginx
COPY .docker/nginx.conf /etc/nginx/nginx.conf
ADD .docker/default.crt /etc/ssl/certs/laravel.crt
ADD .docker/default.key /etc/ssl/private/laravel.key

# Configure PHP-FPM
COPY .docker/fpm-pool.conf /etc/php8/php-fpm.d/www.conf
COPY .docker/php.ini /etc/php8/conf.d/custom.ini

# Configure supervisord
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup document root
RUN mkdir -p /var/www/html
RUN mkdir -p /.composer
RUN mkdir -p /.config

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx  && \
  chown -R nobody.nobody /etc/ssl/certs/ && \
  chown -R nobody.nobody /etc/ssl/private/ && \
  chown -R nobody.nobody /.composer  &&\
  chown -R nobody.nobody /.config

# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/html
COPY --chown=nobody . /var/www/html/

# RUN composer config http-basic.nova.laravel.com $USERNAME $PASSWORD

RUN composer install

RUN php artisan horizon:publish

# Expose the port nginx is reachable on
EXPOSE 80 443

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1/fpm-ping
