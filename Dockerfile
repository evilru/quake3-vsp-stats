FROM php:7.4.16-apache
# FROM php:8-apache

# supervisor web gui would be available on port 9001
# EXPOSE 9001

ENV LOGTYPE q3a-osp

RUN docker-php-ext-install mysqli &&\
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" &&\
    apt-get update &&\
    apt-get -y install cron &&\
    apt-get -y install supervisor &&\
    apt-get -y install vim &&\
    apt-get clean

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY . /vsp

WORKDIR /vsp

RUN chmod +x import.sh &&\
    touch /vsp/logdata/import.log &&\
    crontab /vsp/import-cron &&\
    sed -ri -e 's!/var/www/html!/vsp/pub!g' /etc/apache2/sites-available/*.conf &&\
    sed -ri -e 's!/var/www/!/vsp/pub!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

CMD /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
