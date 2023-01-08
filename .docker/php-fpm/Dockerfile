FROM phpdockerio/php:8.2-fpm
WORKDIR "/var/www"

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        php8.2-mysql; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
#
## Copy existing application directory contents
#COPY . /var/www
#
#RUN chown -R www-data:www-data /var/www/public
