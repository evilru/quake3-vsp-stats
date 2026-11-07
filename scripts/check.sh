#!/usr/bin/env bash
# Tests and the three CLI imports on 8.5. Exit 0 = clean.
# Usage: scripts/check.sh [8.5]   (PHPUnit no longer starts on 7.4: composer requires ^8.5)
cd "$(dirname "$0")/.." || exit 1
rc=0
scripts/bootstrap.sh >/dev/null 2>&1 || { echo "bootstrap failed"; exit 2; }
for v in ${1:-8.5}; do
  scripts/php-use "$v" >/dev/null
  echo "== PHPUnit on PHP $(php -r 'echo PHP_VERSION;')"
  vendor/bin/phpunit --no-coverage 2>&1 | tail -6; [ "${PIPESTATUS[0]}" -eq 0 ] || rc=1
done
if [ "${1:-8.5}" = "8.5" ]; then
  for pair in "q3a baseq3.log" "q3a-xp xp.log" "q3a-osp osp.log"; do
    echo "== Import $pair on PHP $(php -r 'echo PHP_VERSION;')"
    out=$(TOP=5 scripts/import.sh $pair); echo "$out"; [ -z "$out" ] || rc=1
  done
fi
exit $rc
