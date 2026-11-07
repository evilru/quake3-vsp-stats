#!/usr/bin/env bash
# Run a CLI import on a fresh DB and print deduplicated PHP messages.
# Usage: scripts/import.sh <q3a|q3a-xp|q3a-osp> <log>   (PHP version: current `php`)
cd "$(dirname "$0")/.." || exit 1
mysql -e "DROP DATABASE IF EXISTS vsp; CREATE DATABASE vsp" || exit 2
php vsp.php -c pub/configs/cfg-default.php -l "$1" -p savestate 1 "$2" </dev/null 2>&1 \
  | grep -aoE "(Warning|Notice|Deprecated|Fatal error): .{0,140}" | sed -E "s/: +/: /" | sort | uniq -c | sort -rn | head -${TOP:-10}
