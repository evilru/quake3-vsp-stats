# AGENTS.md – Arbeitsnotizen für die PHP-8.5-Migration

## Was ich wissen muss
- Projekt: PHP-Webapp + CLI-Importer `vsp.php` (MariaDB). Migration PHP 7.4.33 -> 8.5 auf Branch `feature/php8`.
- Pro Durchlauf genau EIN Fehler: Weg A (nur 8.5 kaputt: Test unter 7.4 grün, unter 8.5 rot, Fix) oder Weg B (Bug auch unter 7.4: Test rot, Fix). Test und Fix getrennte Commits, Conventional Commits, push auf `feature/php8`.
- Befehle: `php-use 7.4|8.5`, `vsp-db-start`, `scripts/bootstrap.sh` (DB + vendor), `scripts/check.sh [Version]` (Tests + Imports).
- Imports: `php vsp.php -c pub/configs/cfg-default.php -l q3a|q3a-xp|q3a-osp -p savestate 1 baseq3.log|xp.log|osp.log`.
- Webserver (`php -S 0.0.0.0:8000 -t pub/`) erst, wenn CLI-Läufe fehlerfrei sind.

## Glossary
- VSP: Quake-3-Log-Parser/Statistik-Projekt. q3a / q3a-xp / q3a-osp: Logtypen (Baseq3, XP-Mod, OSP-Mod).
- Weg A = Kompatibilitätsproblem, Weg B = Bug im Originalcode.
- savestate: Parser-Parameter, speichert Importstand.

## Invariants
- Code muss unter 7.4.33 UND 8.5 laufen; keine Syntax/Funktionen, die eine Version nicht kennt.
- Nur kleinste Änderung; kein Refactoring, keine Formatierung. Weitere Funde nur notieren.
- Nie auf anderen Branch pushen; kein PR ohne Auftrag.

## Gotchas
- `vsp-db-start` im Vordergrund mit `| tail` aufgerufen blockiert (mysqld erbt die Pipe) -> immer `</dev/null`, bei Bedarf `setsid nohup ... &`.
- `composer install` bekommt in der Cloud keine GitHub-Dist-Downloads ("Could not authenticate") und fällt auf `git clone` zurück: sehr langsam (>15 min). Setup-Skript wärmt den Composer-Cache vor; `vendor/` danach vorhanden halten.
- Nicht `pkill -f "composer install"` aus der Shell: trifft die eigene Shell (Exit 144).
- Mehrere parallele `composer install` vermeiden (Cache-Locks).
- composer.json verlangt `php ^7.4`; vendor/composer/platform_check.php kann unter 8.5 abbrechen -> ggf. eigener Fund (Weg A).
- Foreground-Befehle über 120 s werden in den Hintergrund geschoben; langes mit run_in_background + Monitor.
- Scheduled Run: keine Rückfragen möglich; unklare Verhaltensänderungen nur melden.

## Status
- Run 1: Setup/Tooling vorbereitet (scripts/, AGENTS.md); Fehlersuche unter 8.5 noch nicht abgeschlossen, da vendor/ fehlte.
