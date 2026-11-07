# TODO

Inbox: findings without a ticket yet. Remove an entry once it has an issue.

## Docker / Compose

- **Secrets in the environment.** `DB_PASSWORD` and `VSP_WEB_PASSWORD` are plain environment variables and visible via
  `docker inspect`. MariaDB supports `MARIADB_PASSWORD_FILE`; the app reads `$_ENV`, so it needs a `_FILE` fallback
  (or Docker secrets) first.
- **`sed` rewrites the config at build time.** The `Dockerfile` patches `cfg-default.php`, `settings.php`,
  `password.inc.php` and the exclude list with ~25 `sed` lines. If a line in the config changes the `sed` silently stops
  matching and the build stays green. Make the config read `getenv()` directly instead.
- **Two processes in one container** (Apache and the import loop under supervisor). Works, but health checks and
  restarts affect both. Option: second container (same image, `command: docker/import-loop.sh`) and drop supervisor.
- **Unpinned image.** `docker-compose.yml` uses `evilru/quake3-vsp-stats:latest`; the workflow could also push a
  version tag so users can pin and roll back.
- **TLS.** The stack serves plain HTTP on port 80. Read-only stats pages are not secret, but without TLS anyone on the
  path can read or modify them, and the `vsp.php` web login (if enabled) sends its password in clear text. Put a reverse
  proxy with TLS in front, or document it.
- **Time zone.** `TZ: Europe/Berlin` is hard-coded. To follow the host use `TZ: ${TZ:-UTC}` and mount
  `/etc/localtime:/etc/localtime:ro`. Note: PHP ignores the `TZ` variable; it uses `date.timezone` (default UTC). To make
  PHP follow it add `date.timezone = ${TZ:-UTC}` to `docker/vsp.ini` (behavior change: `date()` in `vsp-q3a.php`
  and `adodb_mktime` would then use that zone; check how log timestamps are interpreted first).
- **Database hardening.** `cap_drop: ALL` / `read_only` are applied to `web` only. The MariaDB image needs `CHOWN`,
  `DAC_OVERRIDE`, `SETGID`, `SETUID` at first start; try `cap_drop: ALL` + those caps and a read-only root file system.
- **Healthcheck gating differs per tool.** `docker compose` waits for `service_healthy`; `podman-compose` 1.0.6 ignores
  the condition (the import loop retries after `IMPORT_RETRY`). Podman only runs health checks with systemd.
- **Untested here:** `.devcontainer`, the GitHub workflow, `docker compose` against a real Docker daemon, the arm64
  image.
- `docker-compose.local.yml` still expects an external network `workspace_vsp` and `VSP_DB_*` variables.

## Application

- **Hitbox image is empty.** `imagejpeg($img, '', 100)` never wrote an image (7.4: warning, 8.x: ValueError, now caught).
  Intended: `imagejpeg($bodyImage, null, 100)` (see the TODO in `pub/themes/bismarck/hitbox.php`). Since the image now has
  `gd`, `playerstat.php` shows the (broken) image.
- **`LOG_READ_SIZE`** is undefined in the unused `vsp-q3a.php` methods `saveShutdownState()` / `verifySavestate()`
  (`save_savestate()` / `check_savestate()` in `vsp.php` also use `md5("")` instead of the last 1024 bytes).
- **Help text of `vsp.php`** still describes savestate files in `logdata/`; the savestate is stored in the database
  (table `savestate`). The `logdata/` write check is a leftover of the file-based variant.
- Behavior-preserving fixes carry a `TODO(tech-debt)` in the code with the intended fix (unknown players/games/awards,
  empty player id, award SQL with empty averages, unknown opponent in `playerstat`, `ExcessivePlayer` ...).
  Find them with `grep -rn "TODO(tech-debt)" .`.

## Tooling

- `phpmd` 2.15 emits PHP 8.5 deprecations from its own code; `phpmd` and `phpstan` still report findings.
- `composer.json` has an empty PSR-4 mapping for `src/` (directory does not exist).
- `error_reporting(E_ALL)` is gone from the app; `pub/configs/cfg-default.php` has no error settings any more. Production
  default in Docker is `display_errors=Off` (see `docker/vsp.ini`).


## Security (open; fixed so far: sort whitelist, hist_rank, root-page query string, PHP_SELF, search heading, unknown-skin redirect, web login comparison, skin path, GUID/alias output, map/mod/game type/country output, game list start_from, FTP download path, repository root page query string)

- **Web login of `vsp.php`** still sends the password in clear text and compares plain text from `password.inc.php`; hashing the
  stored password would change the config format (ask first). In the Docker image Apache's document root is `/vsp`, so
  `vsp.php`, `*.log` and `logdata/` are reachable over HTTP: check what should be served and restrict the rest.
- **Name search:** `index.php` builds a `REGEXP` from user input (escaped with `preg_quote`); check the escaping for MariaDB
  regex and the cost of crafted patterns.
- **`start_from` in `index.php:713`:** not affected (a non-numeric string is compared as a string and falls back to a number); `gamestat.php` is fixed.
- **Raw prints of config-sourced values** (no log input, so only hardening): award names/categories in `awardstat.php:104`,
  `playerstat.php:683,783`, `index.php:453`; `$GLOBALS['config']` in links/forms (must name an existing file in `pub/configs/`);
  hit location names in `playerstat.php:1476` (only mods that log hit locations; not reachable with the sample logs).
- **Docker document root is the whole repository** (`Dockerfile`: `COPY . /vsp`, Apache root `/vsp`). Reachable over HTTP: `vsp.php`,
  `sql/`, `docker/`, `pub/include/` (`scripts/`, docs and readmes are now in `.dockerignore`); Debian's `apache2.conf` sets `Options Indexes` for the directory, so
  folders without an index file are listed (not verified in a running container). A mounted `games.log` (commented example in
  `docker-compose.yml`) or files in `logdata/` / `ftplogs/` would be downloadable and contain player IPs and GUIDs. Fix: document
  root `/vsp/pub` plus an alias for what is needed, or `Require all denied` for everything but `pub/`, and `Options -Indexes`.
- **`pub/info.php`** only contains a commented `phpinfo();` (harmless); remove the file or keep it commented.
- **Name search cost:** `index.php` repeats ``(`#[0-9a-fA-F]{6})*`` before every character of the search text; a very long search text builds a very
  long regular expression (no length limit). Not injectable; consider limiting the length.
- **Server settings list** in `gamestat.php:657` prints `$var: $val` raw; unreachable today (only `_v_*` keys are stored), encode it if
  other server variables are stored one day.
- **`composer audit`:** dev-only advisories, no runtime impact: `phpunit/phpunit` < 9.6.33 (CVE-2026-24765, PHPT coverage) and
  `squizlabs/php_codesniffer` < 3.13.6 (CVE-2026-67434). Update the lock file (changes `composer.lock`, ask first).
- **Not reviewed yet:** ADOdb, FTP code path, `vsp.php:232` (`getPlayerSkill()` uses `secureString()`, i.e. `qstr`, so it is quoted: looks fine).
  Reviewed: the log parser quotes all values with `qstr()` on the way into the database; the output side was the problem (fixed above).
