# AGENTS.md

PHP 8.5 web app + CLI importer (`vsp.php`, MariaDB) for Quake 3 VSP stats.
Origin: obfuscated original, author unknown, later deobfuscated with AI. Hence the odd layout; legacy code stays.
Merge to `master` builds and pushes the Docker image. No versioning yet (see release issues).

## Invariants
System properties that must always hold.
- Target is PHP 8.5; no 7.4 workarounds. Existing version gates stay.
- Legacy code stays unchanged (no refactoring); behavior-changing bugs get `TODO(tech-debt)`, not a fix.
- The app sets no error reporting; the server decides (`docker/vsp.ini`).
- The web container is unprivileged (www-data, Apache on 8080, read-only root fs).
- Tests assert observable behavior, not implementation details.

## Rules
- Be concise: chat, comments, commits, docs, this file.
- Repo content is English; chat may be German.
- KISS, YAGNI, DRY, clean code. Smallest change; no unrelated formatting.
- Conventional Commits, atomic commits.
- Bug or new behavior: red test commit first, fix in a separate commit.
- Tests pass `-d display_errors=1 -d error_reporting=-1`.
- Unclear or behavior-changing: ask. Extra findings go to `TODO.md` or a new issue, never fixed silently.

## Workflow
- Work comes from GitHub issues (cloud, chat possible) or the devcontainer (`task test`).
- One branch per issue, PR against `master`; the maintainer merges.
- Commit and push on your own after each green step.
- Commit bodies explain why.

## Glossary
- q3a / q3a-xp / q3a-osp: log types. Import: `-l <type>` with `baseq3.log|xp.log|osp.log`.
- savestate: resume marker for incremental imports.

## Gotchas
- `vsp.php` runs on include: test via CLI (`tests/ImportCliTest.php`).
- Reset the DB before each import, else games are "ignored".
- Imports emit >100k `fgetcsv` deprecations: aggregate them.

## Cloud only
Locally, composer and Docker work normally.
- Never `composer install` unseeded (403 on zipballs, >20 GB mirror clones). Use `scripts/bootstrap.sh` with `COMPOSER_ALLOW_SUPERUSER=1`.
- Podman, no Docker daemon: `podman build --isolation=chroot --network=host`; `podman-compose`.
- Provisioned by `scripts/setup-cloud.sh`; `php-use`, `vsp-db-start` in `/usr/local/bin`.
- Scripts: `check.sh` (tests + 3 imports), `import.sh <type> <log>`, `variants.py` (all must print OK), `compose-test.sh`, `composer-seed.php`. Add scripts for anything repeated.
- `vsp-db-start`/mysqld: always `</dev/null`, never pipe to `tail`. `pkill -f` kills your own shell.
- Other MariaDB: `podman run --network=host -v /run/mysqld:/run/mysqld -e MARIADB_ROOT_PASSWORD=secretPassword -e MARIADB_DATABASE=vsp mariadb:12.3 --bind-address=127.0.0.1` after `mysqladmin shutdown`; needs a temporary `~/.my.cnf`; restore with `vsp-db-start`.
- podman-compose 1.0.6: no `!override`, no `depends_on` conditions, use `${VAR}` not bare `VAR:`, `tmpfs` without `uid=`, no health checks without systemd.
- Classifier blocks external apt repos and downloaded binaries/phars.
