<?php
// Füllt den Composer-Dist-Cache aus composer.lock per `git fetch --depth 1` + `git archive`,
// weil GitHub-Zipball/codeload in der Cloud-Sandbox 403 liefern und Composer sonst
// volle Git-Mirrors klont (mehrere GB, phpstan bricht ab).
// Aufruf: php composer-seed.php <composer.lock> [cache-files-dir] [parallel]
$lockFile = $argv[1] ?? 'composer.lock';
$cacheDir = rtrim($argv[2] ?? (getenv('HOME') ?: '/root') . '/.cache/composer/files', '/');
$parallel = (int) ($argv[3] ?? 16);
$lock = json_decode(file_get_contents($lockFile), true);
$jobs = [];
foreach (array_merge($lock['packages'], $lock['packages-dev'] ?? []) as $p) {
    $url = $p['dist']['url'] ?? null;
    $repo = $p['source']['url'] ?? null;
    $ref = $p['source']['reference'] ?? ($p['dist']['reference'] ?? null);
    if (!$url || !$repo || !$ref) {
        continue;
    }
    $zip = "$cacheDir/{$p['name']}/" . sha1($url) . '.zip';
    if (is_file($zip)) {
        continue;
    }
    @mkdir(dirname($zip), 0777, true);
    $tmp = "$zip.tmp";
    $jobs[] = [$p['name'], sprintf(
        'd=$(mktemp -d) && cd "$d" && git init -q && git fetch -q --depth 1 %s %s && git archive --format=zip -o %s FETCH_HEAD && mv %s %s; r=$?; rm -rf "$d"; exit $r',
        escapeshellarg($repo), escapeshellarg($ref), escapeshellarg($tmp), escapeshellarg($tmp), escapeshellarg($zip)
    )];
}
$running = [];
$failed = [];
while ($jobs || $running) {
    while ($jobs && count($running) < $parallel) {
        [$name, $cmd] = array_shift($jobs);
        $running[$name] = proc_open($cmd, [1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']], $pipes);
    }
    foreach ($running as $name => $proc) {
        $status = proc_get_status($proc);
        if (!$status['running']) {
            proc_close($proc);
            if ($status['exitcode'] !== 0) {
                $failed[] = $name;
            }
            unset($running[$name]);
        }
    }
    usleep(50000);
}
fwrite(STDERR, $failed ? 'composer-seed: nicht geseedet: ' . implode(', ', $failed) . "\n" : "composer-seed: ok\n");
exit(0);
