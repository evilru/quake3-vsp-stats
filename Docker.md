# VSP - Quake 3 ExcessivePlus Community version

VSP Stats Game Log Processor - Quake 3 ExcessivePlus Community version 0.45-xp-1.1.2, based on the VSP Stats Processor 0.45 by Myrddin.

Related GitHub repository:
<https://github.com/evilru/quake3-vsp-stats>.

## How To run the Docker Compose Stack

1. copy [docker-compose.yml] to your disk
1. configure the container (see below)
1. run the docker compose stack (from the folder where you placed _docker-compose.yml_)

```sh
docker compose up -d
```

The configured games.log is imported when the container starts and then every 15 minutes
(see `IMPORT_INTERVAL`). The stats are available on port 80 of the host
(<http://yourserver.com/pub/themes/bismarck/index.php>).

Podman works too: `podman compose up -d` (with `docker compose` as provider) or `podman-compose up -d`.
Use a `.env` file next to the compose file for the variables below.

### Container security

The web container runs as the unprivileged user `www-data` (Apache listens on port 8080 inside the container, the
compose file maps it to port 80), with a read-only root file system, no Linux capabilities and no new privileges.
Only `logdata` (a volume) and a few temporary directories are writable. The database lives in the named volume
`dbdata`; it is not removed by `docker compose down`, only by `docker compose down -v`.

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

### DB configuration

**Make sure to set username and password for the database!**

```yaml
services:
  db:
    environment:
      MARIADB_USER:
      MARIADB_PASSWORD:
  web:
    environment:
      DB_USERNAME:
      DB_PASSWORD:
```

The database is only reachable from the web service: it sits on an internal network and publishes no ports.
To inspect it, use `docker compose exec db mariadb -u <user> -p <database>`.

_Upgrade note:_ the database variables were renamed from `MYSQL_*` to `MARIADB_*`
(`MARIADB_DATABASE`, `MARIADB_USER`, `MARIADB_PASSWORD`). Rename them in your `.env` or environment.
Existing data directories keep working.

### VSP configuration

The parser can be configured with the following ENV variables. They need to be placed in _environment_ section of the _web_ service.

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
Hostname of the database. This is the name of the database service in the compose file, no need to change that

#### DB_NAME

default: _vsp_  
If not available, this database will be created on the first run.  
Should be the same as _MARIADB_DATABASE_.

#### DB_USERNAME

Username used to connect to the database.  
Should be the same as _MARIADB_USER_.

#### DB_PASSWORD

Password used to connect to the database.  
Should be the same as _MARIADB_PASSWORD_.

#### IMPORT_INTERVAL

default: _900_  
Seconds between two imports of the log file. After a failed import (e.g. the database is not ready yet) the import is
retried after 30 seconds (`IMPORT_RETRY`).

#### GAMES_LIMIT

default: _1000_  
Limit of detailed game stats that will be stored in the database (negative number for unlimited).

#### EXCLUDED_PLAYERS

default: empty (no player excluded)  
Comma-separated list of player names that are excluded from the stats (e.g. _Angel,Biker,Bitterman_).

#### FTP_USERNAME / FTP_PASSWORD

default: _anonymous_ / _anonymous_  
Credentials used when the log file is downloaded from an FTP server.

#### FTP_PASSIVE_MODE

default: _1_  
1 for passive mode, 0 for active mode.

#### FTP_OVERWRITE

default: _0_  
1 to overwrite existing downloaded files, 0 to resume the log.

#### VSP_WEB_PASSWORD

No default value, to enable this feature, set a password with at least 6 characters.

All VSP commands can be called from the commandline and from the browser. The docker image runs the [import loop] in the background.

The webinterface is available under the following url: http://yourserver.com/vsp.php.
Make sure to set a password in [password.inc.php].

### PHP error handling

Errors are controlled by the server, not by the application (see `docker/vsp.ini`). Set these in the _environment_
section of the _web_ service:

```yaml
environment:
  # show errors in the browser / on the console (default: Off, errors are only logged)
  PHP_DISPLAY_ERRORS: "On"
  # numeric error_reporting level (default 32767 = E_ALL, 30719 = E_ALL without deprecations)
  PHP_ERROR_REPORTING: "32767"
```

### VSP Stats page configuration

The VSP Stats page can be configured with the following ENV variables. They need to be placed in _environment_ section of the _web_ service.

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
[import loop]: https://github.com/evilru/quake3-vsp-stats/blob/master/docker/import-loop.sh
[pub/configs/cfg-default.php]: https://github.com/evilru/quake3-vsp-stats/blob/master/pub/configs/cfg-default.php
[import script]: https://github.com/evilru/quake3-vsp-stats/blob/master/docker/import.sh
[password.inc.php]: https://github.com/evilru/quake3-vsp-stats/blob/master/password.inc.php
