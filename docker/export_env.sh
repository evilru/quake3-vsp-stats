#!/bin/bash
set -e

# dump environment variables
printenv | sed 's/^\([^=]*=\)\(.*\)$/export \1"\2"/g' > /etc/profile.d/docker_env.sh

exec cron -f
