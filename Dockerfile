FROM php:7.4.16-apache
# FROM php:8-apache

# supervisor web gui would be available on port 9001
# EXPOSE 9001

ENV LOGTYPE q3a-osp

RUN docker-php-ext-install mysqli \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && apt-get update && apt-get -y install \
    cron \
    supervisor \
    vim \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

COPY . /vsp

WORKDIR /vsp

RUN chmod +x docker/import.sh \
 && mv docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf \
 && crontab docker/import-cron \
 && sed -ri -e 's!/var/www/html!/vsp/pub!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!/vsp/pub!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
 && sed -ri -e 's!(\['\''table_prefix'\''\]\s*=\s*)"vsp_"(;)!\1getenv("TABLE_PREFIX")\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''hostname'\''\]\s*=\s*)"localhost"(;)!\1getenv("HOSTNAME")\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''dbname'\''\]\s*=\s*)"vsp"(;)!\1getenv("DBNAME")\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''username'\''\]\s*=\s*)"root"(;)!\1getenv("USERNAME")\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''password'\''\]\s*=\s*)"secretPassword"(;)!\1getenv("PASSWORD")\2!g' pub/configs/cfg-default.php

CMD /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
