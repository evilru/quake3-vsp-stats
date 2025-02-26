# syntax=docker/dockerfile:1.4
FROM php:7.4.33-apache
# FROM php:8-apache

# supervisor web gui would be available on port 9001
# EXPOSE 9001

# Application Settings
ENV LOGTYPE="q3a-osp" \
    SERVER_TITLE="HERE GOES YOUR SERVER TITLE" \
    SERVER_NAME_IP="Your Server Name and IP goes here" \
    SERVER_GAME_MOD="Your Game and Mod type goes here" \
    SERVER_ADMINS="List your admins here" \
    SERVER_EMAIL_IM="List your E-Mail and/or IM account here" \
    WEB_SITE_ADDRESS="http://my.web_site_goes_here.com" \
    WEB_SITE_NAME="My web site name goes here" \
    SERVER_QUOTE="My quote goes here"

# Theme Settings
ENV DEFAULT_SKIN="fest" \
    CHECK_UNIQUE_GAMEID="1"

# Database Settings
ENV TABLE_PREFIX="vsp_" \
    DB_HOSTNAME="db" \
    DB_NAME="vsp"
    # Uncomment and set these in docker-compose or at runtime
    # DB_USERNAME="" \
    # DB_PASSWORD="" \
    # VSP_WEB_PASSWORD=""

# FTP settings
ENV FTP_USERNAME="anonymous" \
    FTP_PASSWORD="anonymous" \
    # 1 for passive mode, 0 for active mode
    FTP_PASSIVE_MODE="1" \
    # 1 to overwrite existing files, 0 to skip
    FTP_OVERWRITE="0"

# Additional Settings
ENV EXCLUDED_PLAYERS="comma-separated list of players (e.g. Angel,Biker,Bitterman,Bones,Cadavre)" \
   # limit of detailed game stats that will be stored on the database (negative number for unlimited)
   GAMES_LIMIT="1000"

RUN docker-php-ext-install mysqli \
#  && pecl install xdebug-2.9.8 \
#  && docker-php-ext-enable xdebug \
#  && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
 && apt-get update && apt-get -y install \
    cron \
    supervisor \
    # git \
    # vim \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

COPY . /vsp

WORKDIR /vsp

RUN chmod +x docker/import.sh \
 && chmod 777 -R logdata \
 && crontab docker/import-cron \
 && sed -ri -e 's!/var/www/html!/vsp!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!/vsp!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
 && sed -ri -e 's!(\['\''table_prefix'\''\]\s*=\s*)"vsp_"(;)!\1$_ENV["TABLE_PREFIX"]\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''hostname'\''\]\s*=\s*)"localhost"(;)!\1$_ENV["DB_HOSTNAME"]\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''dbname'\''\]\s*=\s*)"vsp"(;)!\1$_ENV["DB_NAME"]\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''username'\''\]\s*=\s*)"root"(;)!\1$_ENV["DB_USERNAME"]\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''password'\''\]\s*=\s*)"secretPassword"(;)!\1$_ENV["DB_PASSWORD"]\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''ftp'\''\]\['\''username'\''\]\s*=\s*).+(;)!\1$_ENV["FTP_USERNAME"]\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''ftp'\''\]\['\''password'\''\]\s*=\s*).+(;)!\1$_ENV["FTP_PASSWORD"]\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''ftp'\''\]\['\''pasv'\''\]\s*=\s*).+(;)!\1$_ENV["FTP_PASSIVE_MODE"]\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''ftp'\''\]\['\''overwrite'\''\]\s*=\s*).+(;)!\1$_ENV["FTP_OVERWRITE"]\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''games_limit'\''\]\s*=\s*).+(;)!\1$_ENV["GAMES_LIMIT"]\2!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!(\['\''password'\''\]\s*=\s*)"vsp"(;)!\1$_ENV["VSP_WEB_PASSWORD"]\2!g' password.inc.php \
 && sed -ri -e 's!HERE GOES YOUR SERVER TITLE!{$_ENV["SERVER_TITLE"]}!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!Your Server Name and IP goes here!{$_ENV["SERVER_NAME_IP"]}!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!Your Game and Mod type goes here!{$_ENV["SERVER_GAME_MOD"]}!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!List your admin\(s\) here!{$_ENV["SERVER_ADMINS"]}!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!List your E-Mail and/or IM account here!{$_ENV["SERVER_EMAIL_IM"]}!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!http://My.web_site_goes_here.com!{$_ENV["WEB_SITE_ADDRESS"]}!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!My web site name goes here!{$_ENV["WEB_SITE_NAME"]}!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!My quote goes here!{$_ENV["SERVER_QUOTE"]}!g' pub/configs/cfg-default.php \
 && sed -ri -e 's!http://My_STATS_Page_Goes_Here.com!/!g' pub/themes/bismarck/all.inc.php \
 && sed -ri -e 's!My Server Name Goes Here!<?php print $_ENV["SERVER_TITLE"];?>!g' pub/themes/bismarck/all.inc.php \
 && sed -ri -e 's!(\['\''default_skin'\''\]\s*=\s*)'\''fest'\''(;)!\1$_ENV["DEFAULT_SKIN"]\2!g' pub/themes/bismarck/settings.php \
 && sed -ri -e 's!(\['\''check_unique_gameID'\''\]\s*=\s*)1!\1$_ENV["CHECK_UNIQUE_GAMEID"]!g' pub/configs/cfg-default.php \
 && sed -ri -e '/\$player_exclude_list=array\(/,/\);/c\$player_exclude_list=explode\('\'','\'', $_ENV["EXCLUDED_PLAYERS"]\);' pub/include/playerExcludeList-default.inc.php


CMD ["supervisord", "-c", "/vsp/docker/supervisord.conf"]
