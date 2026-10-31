<?php

use PHPUnit\Framework\TestCase;

class ImportCliTest extends TestCase
{
    private function importBaseq3(string $type = 'q3a', string $log = 'baseq3.log'): string
    {
        exec('mysql -e "DROP DATABASE IF EXISTS vsp; CREATE DATABASE vsp" 2>&1', $ignored, $rc);
        if ($rc !== 0) {
            $this->markTestSkipped('MariaDB not available (run vsp-db-start)');
        }
        $root = dirname(__DIR__);
        $cmd = escapeshellarg(PHP_BINARY) . ' -d display_errors=1 -d error_reporting=-1 vsp.php -c pub/configs/cfg-default.php -l ' . $type . ' -p savestate 1 ' . $log . ' 2>&1 </dev/null';
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

    public function testBaseq3ImportReportsWholeSecondsWithoutFloatConversion(): void
    {
        $output = $this->importBaseq3();
        $this->assertMatchesRegularExpression('/processed in \d+m \d+s \(/', $output);
        $this->assertStringNotContainsString('Implicit conversion from float', $output);
    }

    public function testBaseq3ImportHasNoArrayOffsetWarningInVspPhp(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/array offset on (?:value of type )?\w+ in \S+vsp\.php on line/',
            $this->importBaseq3()
        );
    }

    public function testBaseq3ImportAwardSqlHasNoArrayOffsetWarning(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            "/array offset on (?:value of type )?\\w+ in \\S+ : eval\\(\\)'d code/",
            $this->importBaseq3()
        );
    }

    public function testXpImportHasNoUndefinedOpponentPlayerStats(): void
    {
        $this->assertDoesNotMatchRegularExpression(
            '/Undefined (?:array key|index:) "?ExcessivePlayer/',
            $this->importBaseq3('q3a-xp', 'xp.log')
        );
    }

    public function testXpChatDuringGameIsStoredAsPlayerQuote(): void
    {
        $lines = file(dirname(__DIR__) . '/xp.log');
        array_splice($lines, 1021, 0, ["  3:39 say: Bitterman: hello there friend 7\n"]);
        $log = tempnam(sys_get_temp_dir(), 'xpchat');
        file_put_contents($log, implode('', $lines));

        $this->importBaseq3('q3a-xp', escapeshellarg($log));
        unlink($log);

        exec('mysql -N -e "SELECT dataValue FROM vsp.vsp_playerdata WHERE playerID=\'Bitterman\' AND dataName=\'quote\'" 2>&1', $rows);
        $this->assertSame(['hello there friend'], $rows);
    }
}
