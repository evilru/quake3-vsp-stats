#!/bin/bash
set -e

ENV_FILE="${DOCKER_ENV_FILE:-/etc/profile.d/docker_env.sh}"

# dump environment variables (export -p quotes values safely; the file contains passwords)
umask 077
export -p > "$ENV_FILE"

exec cron -f
