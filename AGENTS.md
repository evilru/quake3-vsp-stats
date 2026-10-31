# AGENTS.md

Migrated this PHP app + CLI importer (`vsp.php`, MariaDB) from PHP 7.4.33 to 8.5 on `feature/php8`. The repo now targets PHP 8.5 (`composer.json`: `^8.5`, Docker: `php:8.5-apache`).
All repo content (code comments, docs, scripts, commits) is English. The branch and environment are mine: build tooling in `scripts/` whenever something is needed repeatedly.

## Workflow (one issue per run)
- Find the first error on 8.5. Path A (compat) vs B (plain bug) was decided by running the old code on 7.4.33; PHPUnit no longer starts on 7.4 (composer platform check), so compare via the CLI (`php-use 7.4`, `scripts/import.sh`) or a `git worktree` of an old commit. Path B: red test, minimal fix. Path A: red test on 8.5, fix.
- Separate commits for test and fix (Conventional Commits), push `feature/php8`. No PR unless asked. Unclear or behavior-changing -> ask; other findings are only noted here.
- Run history lives only in commit messages (bodies explain why, findings, decisions), nowhere else.

## Tools (`scripts/`)
- `php-use 7.4|8.5`, `vsp-db-start` (installed to /usr/local/bin by setup-cloud.sh)
- `bootstrap.sh` DB + vendor/ (seeded composer cache), `check.sh [ver]` tests + 3 imports (default 8.5; 7.4 only works for the imports), `import.sh <type> <log>` fresh-DB import with deduplicated PHP messages
- `variants.py` import + web crawl for config/settings variants (all must print OK), `compose-test.sh` podman build + podman-compose end-to-end check of the Docker stack
- `composer-seed.php`, `setup-cloud.sh` (the cloud environment setup script)
- Imports: `-l q3a|q3a-xp|q3a-osp` with `baseq3.log|xp.log|osp.log`.

## Containers (podman, no Docker daemon)
- `podman`, `buildah`, `podman-compose` come from apt (setup-cloud.sh). Build with `podman build --isolation=chroot --network=host`; images pull from docker.io through the proxy.
- Other MariaDB versions: `podman run --network=host -v /run/mysqld:/run/mysqld -e MARIADB_ROOT_PASSWORD=secretPassword -e MARIADB_DATABASE=vsp mariadb:12.3 --bind-address=127.0.0.1` after stopping the local DB (`mysqladmin shutdown`); the socket then appears on the host. Needs `~/.my.cnf` with user/password (remove afterwards) and `vsp-db-start` to get the local DB back.
- podman-compose 1.0.6: no `!override`, no `depends_on` conditions, bare `VAR:` entries are not read from `.env` (use `${VAR}`), `tmpfs` takes no `uid=`. Health checks do not run without systemd.
- The web container is unprivileged (www-data, Apache on 8080, read-only root fs); `scripts/compose-test.sh` checks that.
- External apt repositories (e.g. mirror.mariadb.org) are blocked by the auto-mode classifier; use apt packages and container images.

## Glossary
- q3a / q3a-xp / q3a-osp: log types (baseq3, XP mod, OSP mod). savestate: resume marker for incremental imports.

## Invariants
- Target is PHP 8.5. Existing version gates (`PHP_VERSION_ID`, attributes that are comments on 7.4) stay; do not add 7.4 workarounds for new code.
- Smallest change that turns the test green; no refactoring or formatting.
- Tests must be real unit tests that protect behavior against future breakage: assert observable behavior/results, not implementation details or log-output absence, so they do not block later refactoring.
- Error reporting is not set in the app any more: the server decides (php.ini; in Docker `PHP_DISPLAY_ERRORS` / `PHP_ERROR_REPORTING` via `docker/vsp.ini`). Tests pass `-d display_errors=1 -d error_reporting=-1` explicitly.

## Gotchas
- Never run `composer install` unseeded: GitHub zipballs return 403, Composer clones full mirrors (>20 GB, disk full). Use `scripts/bootstrap.sh`; set `COMPOSER_ALLOW_SUPERUSER=1`.
- Always `</dev/null` and no `| tail` around `vsp-db-start`/mysqld (blocks on the pipe).
- Reset the DB before each import (`scripts/import.sh` does), otherwise games are "ignored".
- 8.5 emits >100k `fgetcsv` deprecations: always aggregate (`scripts/import.sh`).
- Executing downloaded binaries/phars is blocked by the auto-mode classifier.
- `pkill -f` on a pattern also kills the own shell.
- vsp.php runs on include: not unit-testable, use CLI tests (`tests/ImportCliTest.php`).

## Policy
- Behavior-changing bugs are never fixed during the migration: keep the old behavior and leave a `TODO(tech-debt)` with the intended fix in the code.

## Open findings
See TODO.md. The migration itself is done: tests, the three imports, the web pages, 21 config/settings variants (`scripts/variants.py`) and the Docker stack (`scripts/compose-test.sh`) are clean on PHP 8.5 and MariaDB 10.11/12.3. The repo now requires PHP ^8.5 (7.4 can no longer run PHPUnit); code still avoids 8.5-only syntax where it was written for 7.4.
