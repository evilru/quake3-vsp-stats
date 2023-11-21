# VSP - Quake 3 ExcessivePlus Community version

VSP Stats Game Log Processor - Quake 3 ExcessivePlus Community version 0.45-xp-1.1.2, based on the VSP Stats Processor 0.45 by Myrddin.

Related GitHub repository:
<https://github.com/evilru/quake3-vsp-stats>.

## How To run the Docker Compose Stack

1. copy [docker-compose.yml] and [docker-compose.override.yml] to your disk into the same folder
1. configure the container (see below)
1. run the docker stack

```sh
docker-compose up -d
```

The configured games.log will be checked every 15 minutes for new games.

## Configuration

The following configuration options need to be done in the [docker-compose.yml].

### Logfile

Mount your games.log.  
Needs to be placed in the _volumes_ section of the _web_ service.

```yaml
services:
  web:
    volumes:
        - /path/to/your/games.log:/vsp/games.log
```

### VSP configuration

The parser can be configured with the following ENV variabels. They need to be placed in _evnironment_ section of the _web_ service.

```yaml
services:
  web:
    environment:
      DB_NAME: vsp
      # use the same value as for MYSQL_USER
      DB_USERNAME:
      # use the same value as for MYSQL_PASSWORD
      DB_PASSWORD:
```

#### LOGTYPE

default: _q3a-osp_  
The parser supports the following values:

```txt
q3a              Quake 3 Arena
q3a-battle       Quake 3 Arena BattleMod
q3a-cpma         Quake 3 Arena CPMA (Promode)
q3a-freeze       Quake 3 Arena (U)FreezeTag etc.
q3a-lrctf        Quake 3 Arena Lokis Revenge CTF
q3a-osp          Quake 3 Arena OSP
q3a-ra3          Quake 3 Arena Rocket Arena 3
q3a-threewave    Quake 3 Arena Threewave
q3a-ut           Quake 3 Arena UrbanTerror
q3a-xp           Quake 3 Arena Excessive Plus
```

#### CHECK_UNIQUE_GAMEID

default: _1_  
Check uniqueness of game start date, set to 0 if log doesn't have server date information

#### TABLE_PREFIX

default: _vsp\__  
The table prefix used in the database

#### DB_HOSTNAME

default: _db_  
This is the internal hostname of the dockerhost, no need to change that

#### DB_NAME

default: _vsp_  
If not available, this database will be created on the first run.  
Should be the same as _MYSQL_DATABASE_.

#### DB_USERNAME

Username used to connect to the database.  
Should be the same as _MYSQL_USER_.

#### DB_PASSWORD

Password used to connect to the database.  
Should be the same as _MYSQL_PASSWORD_.

#### VSP_WEB_PASSWORD

No default value, to enable this feature, set a password with at least 6 characters.

All VSP commands can be called from the commandline and from the browser. The docker image comes with the following [cronjob].

The webinterface is available under the following url: http://yourserver.com/vsp.php.

### VSP Stats page configuration

The VSP Stats page can be configured with the following ENV variabels. They need to be placed in _evnironment_ section of the _web_ service.

#### SERVER_TITLE

default: _HERE GOES YOUR SERVER TITLE_

#### SERVER_NAME_IP

default: _Your Server Name and IP goes here_

#### SERVER_GAME_MOD

default: _Your Game and Mod type goes here_

#### SERVER_ADMINS

default: _List your admins here_

#### SERVER_EMAIL_IM

default: _List your E-Mail and/or IM account here_

#### WEB_SITE_ADDRESS

default: _http://my.web_site_goes_here.com_

#### WEB_SITE_NAME

default: _My web site name goes here_

#### SERVER_QUOTE

default: _My quote goes here_

#### DEFAULT_SKIN

default: _fest_  
The skin value will be stored in a cookie, this value will be used if no cookie is present.

The theme supports the following values:

* avalanche
* avalanche-b
* avalanche-i
* camo
* cyber
* evilsmurfs
* f8tal-b
* fest
* ignited
* sssp
* swat
* twat
* xp

#### Header images

Mount your own images.  
Needs to be placed in the _volumes_ section of the _web_ service.

```yaml
services:
  web:
    volumes:
      - /path/to/your/server.gif:/vsp/pub/images/server.gif
      - /path/to/your/logo.gif:/vsp/pub/images/logo.gif
```

## Extended configuration with multiple logfiles

You can configure the vsp container like you would do it without docker by adding additional configuration files.  
Please note: only the cfg-default.php in the container will pickup the environment variables.

1. Create a copy of [pub/configs/cfg-default.php], update the database configuration and set a different table_prefix.  
Mount it into the image.

    ```yaml
    services:
      web:
        volumes:
          - /path/to/your/cfg-ra3.php:/vsp/pub/configs/cfg-ra3.php
    ```

1. Mount your games.log

    ```yaml
    services:
      web:
        volumes:
            - /path/to/your/ra3.log:/vsp/ra3.log
    ```

1. Copy the [import script] and add a call for your configuration

    ```sh
    php /vsp/vsp.php -c /vsp/pub/configs/cfg-ra3.php -l q3a-ra3 -p savestate 1 ra3.log
    ```

1. Mount your import script

    ```yaml
    services:
      web:
        volumes:
            - /path/to/your/import.sh:/vsp/docker/import.sh
    ```

1. Call it from the browser

    <http://yourserver.com/pub/themes/bismarck/index.php?config=cfg-ra3.php>

[docker-compose.yml]: https://github.com/evilru/quake3-vsp-stats/blob/master/docker-compose.yml
[docker-compose.override.yml]: https://github.com/evilru/quake3-vsp-stats/blob/master/docker-compose.override.yml
[cronjob]: https://github.com/evilru/quake3-vsp-stats/blob/master/docker/import-cron
[pub/configs/cfg-default.php]: https://github.com/evilru/quake3-vsp-stats/blob/master/pub/configs/cfg-default.php
[import script]: https://github.com/evilru/quake3-vsp-stats/blob/master/docker/import.sh
