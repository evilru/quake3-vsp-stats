<?php

use PHPUnit\Framework\TestCase;

class ImportCliTest extends TestCase
{
    private function importBaseq3(): string
    {
        exec('mysql -e "DROP DATABASE IF EXISTS vsp; CREATE DATABASE vsp" 2>&1', $ignored, $rc);
        if ($rc !== 0) {
            $this->markTestSkipped('MariaDB not available (run vsp-db-start)');
        }
        $root = dirname(__DIR__);
        $cmd = escapeshellarg(PHP_BINARY) . ' vsp.php -c pub/configs/cfg-default.php -l q3a -p savestate 1 baseq3.log 2>&1 </dev/null';
        exec('cd ' . escapeshellarg($root) . ' && ' . $cmd, $lines, $rc);
        return implode("\n", $lines);
    }

    public function testBaseq3ImportDoesNotUseUndefinedLogReadSize(): void
    {
        $this->assertStringNotContainsString('LOG_READ_SIZE', $this->importBaseq3());
    }

    public function testBaseq3ImportEmitsNoFgetcsvDeprecation(): void
    {
        $this->assertStringNotContainsString('fgetcsv', $this->importBaseq3());
    }

    public function testBaseq3ImportHasNoReferenceAssignmentNotice(): void
    {
        $this->assertStringNotContainsString('assigned by reference', $this->importBaseq3());
    }

    public function testBaseq3ImportHasNoUndefinedGameversion(): void
    {
        $this->assertStringNotContainsString('gameversion', $this->importBaseq3());
    }

    public function testBaseq3ImportHasNoPassByReferenceNotice(): void
    {
        $this->assertStringNotContainsString('passed by reference', $this->importBaseq3());
    }

    public function testBaseq3ImportStoresSavestateWithValue(): void
    {
        $this->importBaseq3();
        exec('mysql -N -e "SELECT value FROM vsp.vsp_savestate WHERE logfile LIKE \'%baseq3.log\'" 2>&1', $rows);
        $this->assertCount(1, $rows);
        $this->assertStringContainsString('last_shutdown_end_position', $rows[0]);
    }
}
