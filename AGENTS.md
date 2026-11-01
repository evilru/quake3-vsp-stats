# AGENTS.md

PHP web app + CLI importer (`vsp.php`, MariaDB) for Quake 3 VSP stats, targeting PHP 8.5 (`composer.json`: `^8.5`, Docker: `php:8.5-apache`).
Origin: the original code was obfuscated and its author is unknown; it was later deobfuscated with AI. That is why the layout is unusual and the legacy code is kept as it is.
Every merge to `master` builds the Docker image and pushes it to Docker Hub. There is no versioning yet (see the release issues on GitHub).
All repo content (code comments, docs, scripts, commits, issues, PRs) is English. Chat with the maintainer may be German.

## Where work comes from
- Planned work lives in GitHub issues. Smaller findings go to `TODO.md`.
- Development happens locally in the devcontainer (`Taskfile.yml`: `task test`, `task test:unit`, ...) and in the cloud on GitHub issues. In the cloud you can chat with the maintainer as here.
- Unclear or behavior-changing: ask. Extra findings are not fixed silently: note them in `TODO.md` or propose a new issue.

## Git workflow
- One branch per issue, push it and open a PR against `master`. The maintainer merges.
- Conventional Commits, atomic commits (one logical change each), commit and push on your own after every finished, green step. No questions needed for that.
- Bug or new behavior: red test as its own commit first, then the fix as a separate commit.
- Commit bodies explain why (findings, decisions). There is no other run log.

## Principles
- KISS, YAGNI, DRY, clean code. Smallest change that does the job; no unrelated refactoring or formatting.
- Legacy code stays as it is. Behavior-changing bugs are not fixed: keep the old behavior and leave a `TODO(tech-debt)` with the intended fix in the code.
- Tests are real unit tests that protect behavior against future breakage: assert observable results, not implementation details or log-output absence, so they do not block later refactoring.
- Error reporting is not set in the app: the server decides (php.ini; in Docker `PHP_DISPLAY_ERRORS` / `PHP_ERROR_REPORTING` via `docker/vsp.ini`). Tests pass `-d display_errors=1 -d error_reporting=-1`.
- Version gates (`PHP_VERSION_ID`, attributes that are comments on 7.4) stay; new code needs no 7.4 workarounds.

## Glossary
- q3a / q3a-xp / q3a-osp: log types (baseq3, XP mod, OSP mod). Imports: `-l q3a|q3a-xp|q3a-osp` with `baseq3.log|xp.log|osp.log`. savestate: resume marker for incremental imports.

## Gotchas
- `vsp.php` runs on include: not unit-testable, use CLI tests (`tests/ImportCliTest.php`).
- Reset the DB before each import, otherwise games are "ignored".
- PHP 8.5 emits >100k `fgetcsv` deprecations on import: always aggregate them.

## Cloud environment only
Everything below applies only to the cloud sandbox. Locally (devcontainer) `composer install`, Docker and the normal tooling work.
- Never run `composer install` unseeded: GitHub zipballs return 403, Composer clones full mirrors (>20 GB, disk full). Use `scripts/bootstrap.sh` (DB + seeded `vendor/`), with `COMPOSER_ALLOW_SUPERUSER=1`.
- Containers: podman, no Docker daemon. `podman`, `buildah`, `podman-compose` come from apt. Build with `podman build --isolation=chroot --network=host`; images pull from docker.io through the proxy.
- The environment is provisioned by `scripts/setup-cloud.sh`; `php-use 8.5` and `vsp-db-start` are installed to `/usr/local/bin`.
- Scripts in `scripts/`: `check.sh` (tests + 3 imports), `import.sh <type> <log>` (fresh-DB import with deduplicated PHP messages), `variants.py` (import + web crawl for config variants, all must print OK), `compose-test.sh` (podman build + end-to-end check of the Docker stack, incl. unprivileged web container), `composer-seed.php`. Build further tooling in `scripts/` whenever something is needed repeatedly.
- Always `</dev/null` and no `| tail` around `vsp-db-start`/mysqld (blocks on the pipe). `pkill -f` on a pattern also kills your own shell.
- Other MariaDB versions: `podman run --network=host -v /run/mysqld:/run/mysqld -e MARIADB_ROOT_PASSWORD=secretPassword -e MARIADB_DATABASE=vsp mariadb:12.3 --bind-address=127.0.0.1` after `mysqladmin shutdown`. Needs a temporary `~/.my.cnf` with user/password; afterwards run `vsp-db-start` to get the local DB back.
- podman-compose 1.0.6: no `!override`, no `depends_on` conditions, bare `VAR:` entries are not read from `.env` (use `${VAR}`), `tmpfs` takes no `uid=`. Health checks do not run without systemd.
- The auto-mode classifier blocks external apt repositories and executing downloaded binaries/phars; use apt packages and container images.
