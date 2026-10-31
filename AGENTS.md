# AGENTS.md

Migrating this PHP app + CLI importer (`vsp.php`, MariaDB) from PHP 7.4.33 to 8.5 on `feature/php8`.
All repo content (code comments, docs, scripts, commits) is English. The branch and environment are mine: build tooling in `scripts/` whenever something is needed repeatedly.

## Workflow (one issue per run)
- Find the first error on 8.5, check it on 7.4.33. Only 8.5 -> path A: test green on 7.4, red on 8.5, fix. Also 7.4 / plainly wrong -> path B: red test, minimal fix, no compat work.
- Separate commits for test and fix (Conventional Commits), push `feature/php8`. No PR unless asked. Unclear or behavior-changing -> ask; other findings are only noted here.
- Run history lives only in commit messages (bodies explain why, findings, decisions), nowhere else.

## Tools (`scripts/`)
- `php-use 7.4|8.5`, `vsp-db-start` (installed to /usr/local/bin by setup-cloud.sh)
- `bootstrap.sh` DB + vendor/ (seeded composer cache), `check.sh [ver]` tests + 3 imports, `import.sh <type> <log>` fresh-DB import with deduplicated PHP messages
- `composer-seed.php`, `setup-cloud.sh` (the cloud environment setup script)
- Imports: `-l q3a|q3a-xp|q3a-osp` with `baseq3.log|xp.log|osp.log`.

## Glossary
- q3a / q3a-xp / q3a-osp: log types (baseq3, XP mod, OSP mod). savestate: resume marker for incremental imports.

## Invariants
- Code must run on 7.4.33 and 8.5: no syntax or functions one of them lacks.
- Smallest change that turns the test green; no refactoring or formatting.
- Tests must be real unit tests that protect behavior against future breakage: assert observable behavior/results, not implementation details or log-output absence, so they do not block later refactoring.
- `error_reporting(E_ALL)` in pub/configs/cfg-default.php is temporary: restore the commented original when the migration is done.

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

## Open findings (priority order)
1. `LOG_READ_SIZE` is undefined in the unused `vsp-q3a.php` methods `saveShutdownState()`/`verifySavestate()` (and `vsp-client.php` defines it conditionally); would throw on 8.5 if ever called.
2. Theme pages and all three CLI imports run without PHP messages on 8.5 (full error reporting is intentionally on in the web path). Not yet exercised: other configs/themes, Docker build, other MariaDB versions.
