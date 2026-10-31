<?php

use PHPUnit\Framework\TestCase;

class ImportCliTest extends TestCase
{
    public function testBaseq3ImportDoesNotUseUndefinedLogReadSize(): void
    {
        exec('mysql -e "DROP DATABASE IF EXISTS vsp; CREATE DATABASE vsp" 2>&1', $ignored, $rc);
        if ($rc !== 0) {
            $this->markTestSkipped('MariaDB not available (run vsp-db-start)');
        }
        $root = dirname(__DIR__);
        $cmd = escapeshellarg(PHP_BINARY) . ' vsp.php -c pub/configs/cfg-default.php -l q3a -p savestate 1 baseq3.log 2>&1 </dev/null';
        exec('cd ' . escapeshellarg($root) . ' && ' . $cmd, $lines, $rc);
        $output = implode("\n", $lines);

        $this->assertStringNotContainsString('LOG_READ_SIZE', $output);
    }
}
