#!/bin/bash

# dump environment variables
printenv | sed 's/^\([^=]*=\)\(.*\)$/export \1"\2"/g' >> /etc/profile.d/docker_env.sh

cron -f
