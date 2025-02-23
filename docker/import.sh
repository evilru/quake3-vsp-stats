#!/bin/bash -l
set -e

source /etc/profile.d/docker_env.sh || {
    echo "Failed to source environment" >&2
    exit 1
}

php /vsp/vsp.php -c /vsp/pub/configs/cfg-default.php -l "$LOGTYPE" -p savestate 1 games.log
